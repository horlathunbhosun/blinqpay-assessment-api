<?php

namespace App\Http\Services;

use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use ApiResponseTrait;

    // Register user method
    public function register($request): array
    {
        try {
            // Create user record
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            // Return success response
            return $this->successObject($user , 'User Created Successfully', 201);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    // Login user method
    public function login($request): array
    {
        try {
            // Check if user exists
            $user = User::where('email', $request->email)->first();
            // Check if password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorObject('Invalid Credentials');
            }
            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;
            //prepare data to return
            $data = [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ];
            // Return success response
            return $this->successObject($data, 'User Logged In Successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    // Logout user method
    public function logout($request): array
    {
        try {
            // Revoke user token
            $request->user()->tokens()->delete();
            // Return success response
            return $this->successObject([], 'User Logged Out Successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }
}
