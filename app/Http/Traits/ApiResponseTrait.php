<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponseTrait
{

    public function successResponse($data=null, $msg="OK", $code = ResponseAlias::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'message'   => $msg,
            'data' => $data,
            'status_code' => $code,
        ], $code);
    }


    public function errorResponse($message="An error occurred", $code = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'status_code' => $code
        ], $code );
    }


    public function successObject($data="", $msg="OK", $statusCode=""): array
    {
        return  $response = [
            'status' => true,
            'message' => $msg,
            'data' => $data,
            'statusCode' => $statusCode
        ];
    }


    public function errorObject($msg='error'): array
    {
        return  $response = [
            'status' => false,
            'message' => $msg,
        ];
    }
}
