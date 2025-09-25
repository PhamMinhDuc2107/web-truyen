<?php

namespace App\UseCases\Auth;

use App\Services\AuthService;

class LoginUseCase
{
   public function __construct(private AuthService $authService) {}

   public function execute(string $email, string $password): array
   {
      return $this->authService->login($email, $password);
   }
}
