<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\UseCases\Auth\LoginUseCase;
use App\Http\Responses\ResponseFormatter;

class AuthController extends Controller
{
    public function __construct(private LoginUseCase $loginUseCase) {}

    public function login(LoginRequest $request)
    {
         $data = $request->validated();
         
         $result = $this->loginUseCase->execute($data['email'], $data['password']);

         return ResponseFormatter::success($result, 'Login thành công');
    }
}
