<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use App\Enums\Token;
class AuthService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }
        $accessTokenExpires = Carbon::now()->addMinutes(10);
        $refreshTokenExpires = Carbon::now()->addDays(30);  
        // access token
        $accessToken = $user->createToken(
            Token::ACCESS->value,
            abilities: ['*'],
            expiresAt: $accessTokenExpires
        )->plainTextToken;

        // refresh token
        $refreshToken = $user->createToken(
            Token::REFRESH->value,
            abilities: ['*'],
            expiresAt: $refreshTokenExpires
        )->plainTextToken;

        return [
            'user' => $user,
            'token' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => $accessTokenExpires->timestamp,
                'refresh_expires_in' => $refreshTokenExpires->timestamp,
            ]
        ];
    }
}
