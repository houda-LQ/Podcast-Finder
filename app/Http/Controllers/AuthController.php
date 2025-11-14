<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{

    /**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Créer un nouveau compte utilisateur",
 *     tags={"Authentification"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","role"},
 *             @OA\Property(property="name", type="string", example="Houda"),
 *             @OA\Property(property="email", type="string", example="houda@gmail.com"),
 *             @OA\Property(property="password", type="string", example="123456"),
 *             @OA\Property(property="role", type="string", example="admin")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Inscription réussie"
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur lors de l'inscription"
 *     )
 * )
 */

   public function register(RegisterRequest $request)
{
    try {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            "message" => "Inscription réussie !",
            "user" => $user
        ], 201);

    } catch (Exception $e) {
        return response()->json([
            "message" => "Erreur lors de l'inscription",
            "error" => $e->getMessage()
        ], 500);
    }
}


/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="Connexion utilisateur",
 *     tags={"Authentification"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", example="houda@gmail.com"),
 *             @OA\Property(property="password", type="string", example="123456")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Connexion réussie (retourne un token)"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Email ou mot de passe incorrect"
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur lors de la connexion"
 *     )
 * )
 */

  public function login(LoginRequest $request)
{
    try {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response()->json([
                "message" => "Email ou mot de passe incorrect"
            ], 401);
        }

        $user = User::where("email", $request->email)->firstOrFail();
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "message" => "Connexion réussie",
            "user" => $user,
            "token" => $token
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            "message" => "Erreur lors de la connexion",
            "error" => $e->getMessage()
        ], 500);
    }
}

    /**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Déconnexion de l'utilisateur",
 *     tags={"Authentification"},
 *     security={{"sanctum":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Déconnexion réussie"
 *     ),
 *
 *   
 * )
 */

     public function logout(){
       auth()->user()->currentAccessToken()->delete();
        return response()->json(["message"=>"Déconnexion réussie",
                                
     ]);

    }

}
