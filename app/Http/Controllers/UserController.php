<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function index()
{
    $hosts = User::where('role', 'Animateur')->get();

    return response()->json($hosts);
}

public function show($id)
{
    $host = User::with("podcasts.episodes")->where("role", "Animateur")->findOrFail($id);

    return response()->json($host);
}

public function store(StoreUserRequest $request)
{
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
}

 public function update(UpdateUserRequest $request, $id)
{
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
}

 public function destroy($id){
        $host=User::findOrFail($id);
        $this->authorize('delete', $host);

        $host->delete();
        return response()->json(["message"=>" Utilisateur supprimé"]);
    }
    
    public function allUsers()
{
    $this->authorize('viewAny', User::class);

    $users = User::all();

    return response()->json($users);
}



}
