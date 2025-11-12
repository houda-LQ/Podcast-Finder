<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function register(RegisterRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role,
        ]);

        return response()->json([
            "message" => "user registered successfully",
            "user" => $user
        ], 201);
    }

  public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response()->json([
                "message" => "invalid"
            ], 401);
        }

        $user = User::where("email", $request->email)->firstOrFail();
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "message" => "login successful",
            "user" => $user,
            "token" => $token
        ], 200);
    }
    
    
    


     public function logout(){
       auth()->user()->currentAccessToken()->delete();
        return response()->json(["message"=>"logout successful",
                                
     ]);

    }

}
