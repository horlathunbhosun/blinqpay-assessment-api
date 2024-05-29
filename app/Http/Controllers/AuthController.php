<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegistrationRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->userService->register($request);
        if(isset($data['status']) && $data['status'] === false) {
            return $this->errorResponse($data['message'],"", 400);
        }
        return $this->successResponse(new UserResource($data['data']),$data['message'], $data['statusCode']);
    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->userService->login($request);
        if(isset($data['status']) && $data['status'] === false) {
            return $this->errorResponse($data['message'],"", 400);
        }
        return $this->successResponse(new UserResource($data['data']),$data['message'], $data['statusCode']);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->userService->logout($request);
        if(isset($data['status']) && $data['status'] === false) {
            return $this->errorResponse($data['message'],"", 400);
        }
        return $this->successResponse($data['data'],$data['message'], $data['statusCode']);
    }

}
