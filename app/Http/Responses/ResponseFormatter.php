<?php
namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ResponseFormatter
{
   public static function success(array $data = [], string $message = 'Success', int $code = 200, array $extra = []): JsonResponse
   {
       return response()->json(array_merge([
           'status'  => 'success',  
           'code'    => $code,
           'message' => $message,
           'data'    => $data,
           'errors'  => [],
       ], $extra), $code);
   }
   

    public static function error(string $message = 'Error', int $code = 400,array $errors = []): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
            'data'    => [],
        ], $code);
    }
}
