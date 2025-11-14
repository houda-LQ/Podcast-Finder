<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    

  
    /**
 * @OA\Get(
 *     path="/api/podcasts/{podcast_id}/episodes",
 *     summary="Liste des épisodes d’un podcast",
 *     description="Récupère tous les épisodes associés à un podcast donné.",
 *     tags={"Episodes"},
 *
 *     @OA\Parameter(
 *         name="podcast_id",
 *         in="path",
 *         required=true,
 *         description="ID du podcast",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Episodes récupérés avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="episodes",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=3),
 *                     @OA\Property(property="title", type="string", example="Episode 1"),
 *                     @OA\Property(property="audio", type="string", example="https://cloudinary.com/audio.mp3"),
 *                     @OA\Property(property="podcast_id", type="integer", example=1),
 *                     @OA\Property(property="created_at", type="string", example="2025-01-10"),
 *                     @OA\Property(property="updated_at", type="string", example="2025-01-10")
 *                 )
 *             )
 *         )
 *     ),
 *
 *  
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la récupération des épisodes"),
 *             @OA\Property(property="error", type="string", example="Server error")
 *         )
 *     )
 * )
 */

 public function index($podcast_id)
{
    try{

    $episodes = Episode::where("podcast_id", $podcast_id)->get();

    return response()->json([
       "message" => "Episodes récupérés avec succès",
        "episodes" =>$episodes
    ],200);
    }catch(Exception $e) {

      return response()->json([
       "message" => "Erreur lors de la récupération des épisodes",
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
 *     path="/api/podcasts/{podcast_id}/episodes",
 *     summary="Créer un nouvel épisode",
 *     description="Crée un épisode pour un podcast spécifique. Accessible uniquement aux utilisateurs autorisés.",
 *     tags={"Episodes"},
 * 
 *     security={{ "sanctum": {} }},
 * 
 *     @OA\Parameter(
 *         name="podcast_id",
 *         in="path",
 *         required=true,
 *         description="ID du podcast auquel ajouter l'épisode",
 *         @OA\Schema(type="integer")
 *     ),
 * 
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="form-data",
 *             @OA\Schema(
 *                 required={"title", "description"},
 *                 @OA\Property(property="title", type="string", example="Episode 1"),
 *                 @OA\Property(property="description", type="string", example="Introduction du podcast"),
 *                 @OA\Property(property="audio", type="string", format="binary", description="Fichier audio (optional)")
 *             )
 *         )
 *     ),
 * 
 *     @OA\Response(
 *         response=201,
 *         description="Épisode créé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Episode créé avec succès"),
 *             @OA\Property(property="episode", type="object")
 *         )
 *     ),
 * 
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur lors de la création",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur lors de la création"),
 *             @OA\Property(property="error", type="string", example="Message d'erreur")
 *         )
 *     )
 * )
 */

   public function store(StoreEpisodeRequest $request, $podcast_id)
{

    try {
         $this->authorize('create',Episode::class);

    $data = $request->validated();
    $data['podcast_id'] = $podcast_id;

   if ($request->hasFile('audio')) { 

    $uploadedFile = Cloudinary::uploadFile( $request->file('audio')->getRealPath(), 
    ['resource_type' => 'auto'] ); 

    $data['audio'] = $uploadedFile->getSecurePath(); }

    $episode = Episode::create($data);

    return response()->json([
        'message' => 'Episode créé avec succès',
        'episode' =>  $episode
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
 *     path="/api/episodes/{id}/details",
 *     summary="Afficher les détails d'un épisode",
 *     description="Récupère les informations d'un épisode spécifique par son ID.",
 *     tags={"Episodes"},
 * 
 *     security={{ "sanctum": {} }},
 * 
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'épisode",
 *         @OA\Schema(type="integer")
 *     ),
 * 
 *     @OA\Response(
 *         response=200,
 *         description="Détails de l'épisode récupérés avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Episode 1"),
 *             @OA\Property(property="description", type="string", example="Introduction du podcast"),
 *             @OA\Property(property="audio", type="string", example="https://res.cloudinary.com/..."),
 *             @OA\Property(property="podcast", type="object", description="Podcast associé")
 *         )
 *     ),
 * 
 *     @OA\Response(
 *         response=404,
 *         description="Épisode non trouvé",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Episode] 1")
 *         )
 *     )
 * )
 */

    public function show($id)
{
    $episode = Episode::with("podcast")->findOrFail($id);

    return response()->json($episode);
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Episode $episode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    /**
 * @OA\Put(
 *     path="/api/episodes/{id}/update",
 *     summary="Modifier un épisode",
 *     description="Met à jour les informations d'un épisode spécifique par son ID.",
 *     tags={"Episodes"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'épisode",
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Nouvel Episode"),
 *             @OA\Property(property="description", type="string", example="Description mise à jour"),
 *             @OA\Property(property="audio", type="string", description="Fichier audio (optionnel)"),
 *             @OA\Property(property="image", type="string",  description="Image de l'épisode (optionnel)")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Épisode modifié avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Episode modifié"),
 *             @OA\Property(property="episode", type="object")
 *         )
 *     ),
 *
 *    
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur modification"),
 *             @OA\Property(property="error", type="string", example="Exception message")
 *         )
 *     )
 * )
 */

    public function update(UpdateEpisodeRequest $request, $id)
{
    try {
        $episode = Episode::findOrFail($id);

     $this->authorize('update', $episode);

    $data = $request->validated();
    

     if ($request->hasFile('image')) {
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $data['image'] = $uploadedFileUrl;
    }

    $episode->update($data);

    return response()->json([
        'message' => 'Episode modifié',
        'episode' =>  $episode
    ],200);
    
    } catch (Exception $e) {
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
 *     path="/api/episodes/{id}",
 *     summary="Supprimer un épisode",
 *     description="Supprime un épisode spécifique par son ID.",
 *     tags={"Episodes"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'épisode",
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Épisode supprimé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Episode supprimé")
 *         )
 *     ),
 *
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur"
 *     )
 * )
 */

     public function destroy($id){
    try {
         $episode=Episode::findOrFail($id);
        $this->authorize('delete', $episode);

        $episode->delete();
        return response()->json(["message"=>" Episode supprimé"],200);

    } catch (Exception $e) {
        return response()->json([
            "message"=>" Erreur dans suppression",
           'error' =>  $e->getMessage()
        ]
        ,500);
    }
       
    }



    /**
 * @OA\Get(
 *     path="/api/episodes/search",
 *     summary="Recherche des épisodes",
 *     description="Recherche des épisodes par titre, titre du podcast ou date de création.",
 *     tags={"Episodes"},
 *
 *     security={{ "sanctum": {} }},
 *
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         description="Texte de recherche",
 *         @OA\Schema(type="string", example="Podcast")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Résultat de la recherche",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Episodes récupérés"),
 *             @OA\Property(property="episodes", type="array",
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

    $episodes = Episode::with('podcast') 
        ->where('title', 'like', "%{$search}%") 
        ->orWhereHas('podcast', function ($query) use ($search) { 
            $query->where('title', 'like', "%{$search}%");
        })
        ->orWhereDate('created_at', $search) 
        ->get();

    return response()->json($episodes,200);    

    } catch (Exception $e) {
       return response()->json([
            "message"=>" Erreur dans la recherhe",
           'error' =>  $e->getMessage()
        ]
        ,500);    }
   
}

}
