<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/hosts",
 *     summary="Liste de tous les animateurs",
 *     description="Récupère la liste complète des utilisateurs ayant le rôle 'Animateur'",
 *     tags={"Users"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Liste des animateurs récupérée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="role", type="string"),
 *                 @OA\Property(property="created_at", type="string"),
 *                 @OA\Property(property="updated_at", type="string")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur lors de la récupération",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la récupération des animateurs"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

   public function index()
{
    try {
      $hosts = User::where('role', 'Animateur')->get();

    return response()->json($hosts,200);
    
    } catch (Exception $e) {

      return response()->json([
       "message" => "Erreur lors de la récupération des aniimateurs",
        "error" =>$e->getMessage()
    ],500);
    }
    
}


/**
 * @OA\Get(
 *     path="/api/hosts/{id}/details",
 *     summary="Détails d'un animateur",
 *     description="Récupère les informations d'un animateur, y compris ses podcasts et les épisodes de chaque podcast",
 *     tags={"Users"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'animateur",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Animateur récupéré avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="role", type="string"),
 *             @OA\Property(property="created_at", type="string"),
 *             @OA\Property(property="updated_at", type="string"),
 *             @OA\Property(
 *                 property="podcasts",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="title", type="string"),
 *                     @OA\Property(property="description", type="string"),
 *                     @OA\Property(property="image", type="string"),
 *                     @OA\Property(
 *                         property="episodes",
 *                         type="array",
 *                         @OA\Items(
 *                             @OA\Property(property="id", type="integer"),
 *                             @OA\Property(property="title", type="string"),
 *                             @OA\Property(property="audio", type="string"),
 *                             @OA\Property(property="created_at", type="string"),
 *                             @OA\Property(property="updated_at", type="string")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur lors de la récupération",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */


public function show($id)
{
    try {
        $host = User::with("podcasts.episodes")->where("role", "Animateur")->findOrFail($id);

    return response()->json($host,200);

    } catch (Exception $e ) {
 return response()->json([
        'message' => 'Erreur  ',
        'error' =>  $e->getMessage()
    ], 500);
    }
    
}

/**
 * @OA\Post(
 *     path="/api/users",
 *     summary="Créer un nouvel utilisateur / animateur",
 *     description="Permet à l'admin de créer un nouvel utilisateur ou animateur avec rôle",
 *     tags={"Users"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","role"},
 *             @OA\Property(property="name", type="string", example="Camilia"),
 *             @OA\Property(property="email", type="string", format="email", example="camilia@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
 *             @OA\Property(property="role", type="string", example="Animateur")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Utilisateur créé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Animateur créé avec succès"),
 *             @OA\Property(
 *                 property="user",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="role", type="string"),
 *                 @OA\Property(property="created_at", type="string"),
 *                 @OA\Property(property="updated_at", type="string")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur lors de la création",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la création"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

public function store(StoreUserRequest $request)
{

    try {
        $this->authorize('create', User::class);

    $data = $request->validated();

    $data['password'] = Hash::make($data['password']);

$user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $data['password'],
        'role' => $data['role'], 
    ]);

    return response()->json([
        'message' => 'Animateur créé avec succès',
        'user' => $user
    ], 201);

    } catch(Exception $e ) {
 return response()->json([
        'message' => 'Erreur lors de la création ',
        'error' =>  $e->getMessage()
    ], 500);    }
    
}



/**
 * @OA\Put(
 *     path="/api/hosts/{id}/update",
 *     summary="Modifier un utilisateur",
 *     description="Permet à l'admin de modifier un utilisateur ou animateur, y compris le mot de passe",
 *     tags={"Users"},
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'utilisateur à modifier",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Camilia.L"),
 *             @OA\Property(property="email", type="string", format="email", example="camiliaL@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
 *             @OA\Property(property="role", type="string", example="Animateur")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur modifié avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Utilisateur modifié avec succès"),
 *             @OA\Property(
 *                 property="user",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="role", type="string"),
 *                 @OA\Property(property="created_at", type="string"),
 *                 @OA\Property(property="updated_at", type="string")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur lors de la modification",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur modification"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

 public function update(UpdateUserRequest $request, $id)
{
    try {
         $host = User::findOrFail($id);

     $this->authorize('update', $host);

     
    $data = $request->validated();
    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']); 
    }

    $host->update($data);

    return response()->json([
        'message' => 'Utilisateur modifié avec succès',
        'user' => $host
    ]);
    } catch (Exception $e) {
        return response()->json([
        'message' => 'Erreur modification',
        'error' =>  $e->getMessage()
    ],500);
    }
}



/**
 * @OA\Delete(
 *     path="/api/hosts/{id}",
 *     summary="Supprimer un utilisateur",
 *     description="Permet à l'admin de supprimer un utilisateur ou un animateur",
 *     tags={"Users"},
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'utilisateur à supprimer",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur supprimé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Utilisateur supprimé")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur lors de la suppression",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur dans suppression"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */


 public function destroy($id){

    try {
        $host=User::findOrFail($id);
        $this->authorize('delete', $host);

        $host->delete();
        return response()->json(["message"=>" Utilisateur supprimé"]);

    } catch (Exception $e) {
        return response()->json([
            "message"=>" Erreur dans suppression",
           'error' =>  $e->getMessage()
        ],500);
        
    }
}
    


/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Lister tous les utilisateurs",
 *     description="Permet à l'admin de récupérer la liste de tous les utilisateurs (y compris animateurs, sans distinction)",
 *     tags={"Users"},
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Liste des utilisateurs récupérée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Hajar"),
 *                 @OA\Property(property="email", type="string", example="Hajar@example.com"),
 *                 @OA\Property(property="role", type="string", example="Animateur"),
 *                 @OA\Property(property="created_at", type="string", example="2025-11-14T11:57:07.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", example="2025-11-14T11:57:07.000000Z")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur lors de la récupération",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la récupération des utilisateurs"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function allUsers()
{
    try {
        $this->authorize('viewAny', User::class);

    $users = User::all();

    return response()->json($users,200);

    } catch (Exception $e) {

      return response()->json([
       "message" => "Erreur lors de la récupération des utilisateurs",
        "error" =>$e->getMessage()
    ],500);
    }
    
}



}
