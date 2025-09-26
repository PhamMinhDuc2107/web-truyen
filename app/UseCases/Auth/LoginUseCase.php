<?php

namespace App\UseCases\Auth;

use App\Services\Auth\AuthService;

class LoginUseCase
{
   public function __construct(private AuthService $authService) {}

   public function execute(string $email, string $password): array
   {
      return $this->authService->login($email, $password);
   }
}
