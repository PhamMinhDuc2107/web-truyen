<?php
namespace App\Enums;

enum Token: string {
   case ACCESS = 'access-token';
   case REFRESH = 'refresh-token';
}
