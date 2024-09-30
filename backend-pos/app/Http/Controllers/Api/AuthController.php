<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role'     => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        };

        try {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);
    
            $user->assignRole($request->role);
            $user->roles;
    
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Register Success!',
                'user'    => $user,
                'token'   => $token
            ], 201);
        } catch (QueryException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Register Failed!',
                'error' => $exception->errorInfo[2]
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password is incorrect'
            ], 400);
        }

        $user = auth()->guard('api')->user();
        $user->roles;

        return response()->json([
            'success' => true,
            'user'    => $user,
            'token'   => $token,
            'message' => 'Login Success!'
        ], 201);
    }

    public function getUser()
    {
        $user = auth()->user();
        $user->roles;
        $token = JWTAuth::getToken();
        $payload = JWTAuth::getPayload($token);
        $exp = $payload['exp'];
        $remainingTime = $exp - time();

        return response()->json([
            'success' => true,
            'user'    => $user,
            'expires_in' => $remainingTime,
        ], 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('api')->refresh());
        // return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ]);
    }


    public function logout()
    {
        $removeToken  = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message'    => "Successfully Logged Out!",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message'    => "Logout Failed!",
            ], 400);
        }
    }


    public function checkTokenExpired(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $token = $request->input('token');
            $payload = JWTAuth::setToken($token)->getPayload();
            $exp = $payload->get('exp');
            $remainingTime = $exp - now()->timestamp;

            return response()->json([
                'Token Remaining Time' => $remainingTime,
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token Expired'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to verify token'], 400);
        }
    }
}
