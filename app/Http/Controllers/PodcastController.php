<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
 * @OA\Get(
 *     path="/api/podcasts",
 *     summary="Liste de tous les podcasts",
 *     description="Récupère tous les podcasts disponibles.",
 *     tags={"Podcasts"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Liste des podcasts",
 *         @OA\JsonContent(
 *             type="array",
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la récupération des podcasts"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function index()
    {
  try {

 return response()->json(Podcast::all()); 

} catch(Exception $e) {

      return response()->json([
       "message" => "Erreur lors de la récupération des podcasts",
        "error" =>$e->getMessage()
    ],500);
  }
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
 * @OA\Post(
 *     path="/api/podcasts",
 *     summary="Créer un nouveau podcast",
 *     description="Permet à l'admin de créer un podcast avec un titre, une description et une image.",
 *     tags={"Podcasts"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","description"},
 *             @OA\Property(property="title", type="string", example="Mon premier podcast"),
 *             @OA\Property(property="description", type="string", example="Description de mon podcast"),
 *             @OA\Property(property="image", type="string",  description="Fichier image du podcast")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Podcast créé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Podcast créé avec succès"),
 *             @OA\Property(property="podcast")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la création"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function store(StorePodcastRequest $request)
{
    try {
   $this->authorize('create', Podcast::class);

    $data = $request->validated();

    if ($request->hasFile('image')) {
        $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath());
        $data['image'] = $uploadedFile->getSecurePath(); 
    }

    $podcast = Podcast::create($data);

    return response()->json([
        'message' => 'Podcast créé avec succès',
        'podcast' => $podcast
    ], 201);   
 
} catch (Exception $e ) {
 return response()->json([
        'message' => 'Erreur lors de la création ',
        'error' =>  $e->getMessage()
    ], 500);    }
    
}
    

    /**
     * Display the specified resource.
     */
    /**
 * @OA\Get(
 *     path="/api/podcasts/{id}/details",
 *     summary="Afficher les détails d'un podcast",
 *     description="Permet de récupérer un podcast par son ID, avec l'animateur et ses épisodes.",
 *     tags={"Podcasts"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID du podcast à afficher",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Podcast récupéré avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Mon premier podcast"),
 *             @OA\Property(property="description", type="string", example="Description du podcast"),
 *             @OA\Property(property="host", type="object"),
 *             @OA\Property(property="episodes", type="array",
 *                 @OA\Items(ref="#/components/schemas/Episode")
 *             )
 *         )
 *     ),
 *
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la récupération"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function show($id)
{
    try {
        $podcast = Podcast::with(['host', 'episodes'])->findOrFail($id);

    return response()->json($podcast,200);

    } catch (Exception $e ) {
 return response()->json([
        'message' => 'Erreur  ',
        'error' =>  $e->getMessage()
    ], 500);    }
    
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Podcast $podcast)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    /**
 * @OA\Put(
 *     path="/api/podcasts/{id}",
 *     summary="Mettre à jour un podcast",
 *     description="Permet à l'admin ou à l'animateur du podcast de modifier ses informations et son image.",
 *     tags={"Podcasts"},
 * 
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID du podcast à modifier",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Titre du podcast mis à jour"),
 *             @OA\Property(property="description", type="string", example="Nouvelle description"),
 *             @OA\Property(property="image", type="string", format="binary", description="Nouvelle image du podcast")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Podcast mis à jour avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Titre du podcast mis à jour"),
 *             @OA\Property(property="description", type="string", example="Nouvelle description"),
 *             @OA\Property(property="image", type="string", example="https://res.cloudinary.com/..."),
 *             @OA\Property(property="user_id", type="integer", example=1)
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur modification"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

   public function update(UpdatePodcastRequest $request, $id)
{
    try {
       $podcast = Podcast::findOrFail($id);

     $this->authorize('update', $podcast);

    $data = $request->validated();
    

     if ($request->hasFile('image')) {
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $data['image'] = $uploadedFileUrl;
    }


    $podcast->update($data);

    return response()->json($podcast,200);
    }catch (Exception $e) {
        return response()->json([
        'message' => 'Erreur modification',
        'error' =>  $e->getMessage()
    ],500);
    }
    
}


        
    

    /**
     * Remove the specified resource from storage.
     */
    /**
 * @OA\Delete(
 *     path="/api/podcasts/{id}",
 *     summary="Supprimer un podcast",
 *     description="Permet à l'admin ou à l'animateur du podcast de supprimer un podcast existant.",
 *     tags={"Podcasts"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID du podcast à supprimer",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Podcast supprimé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Podcast supprimé")
 *         )
 *     ),
 *
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur dans suppression"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function destroy($id){
        try {
            $podcast=Podcast::findOrFail($id);
        $this->authorize('delete', $podcast);

        $podcast->delete();
        return response()->json(["message"=>"Podcast supprimé"],200);
        } catch (Exception $e) {
        return response()->json([
            "message"=>" Erreur dans suppression",
           'error' =>  $e->getMessage()
        ],500);
        }
        
    }


/**
 * @OA\Get(
 *     path="/api/podcasts/search",
 *     summary="Recherche des podcasts",
 *     description="Recherche des podcasts par titre ou par nom de l'animateur",
 *     tags={"Podcasts"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         description="Mot-clé pour la recherche (titre du podcast ou nom de l'animateur)",
 *         required=true,
 *         @OA\Schema(type="string", example="Podcast")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Résultats de la recherche",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="description", type="string"),
 *                 @OA\Property(property="image", type="string"),
 *                 @OA\Property(property="user_id", type="integer"),
 *                 @OA\Property(property="created_at", type="string"),
 *                 @OA\Property(property="updated_at", type="string"),
 *                 @OA\Property(
 *                     property="host",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="name", type="string")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur dans la recherche"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */


public function search(Request $request)
{
    try {
         $search = $request->input('query');

    $podcasts = Podcast::with('host') 
        ->where('title', 'like', "%{$search}%")
        ->orWhereHas('host', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->get();

    return response()->json($podcasts,200);

    }  catch (Exception $e) {
       return response()->json([
            "message"=>" Erreur dans la recherhe",
           'error' =>  $e->getMessage()
        ]
        ,500);    }
    }
   
}





