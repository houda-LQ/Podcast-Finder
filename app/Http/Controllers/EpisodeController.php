<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index($podcast_id)
{
    $episodes = Episode::where("podcast_id", $podcast_id)->get();

    return response()->json($episodes);
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
   public function store(StoreEpisodeRequest $request, $podcast_id)
{
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
}
    


    /**
     * Display the specified resource.
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
    public function update(UpdateEpisodeRequest $request, $id)
{
    $episode = Episode::findOrFail($id);

     $this->authorize('update', $episode);

    $data = $request->validated();
    

     if ($request->hasFile('image')) {
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $data['image'] = $uploadedFileUrl;
    }


    $episode->update($data);

    return response()->json($episode);
}


    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id){
        $episode=Episode::findOrFail($id);
        $this->authorize('delete', $episode);

        $episode->delete();
        return response()->json(["message"=>" Episode supprimé"]);
    }

public function search(Request $request)
{
    $search = $request->input('query');

    $episodes = Episode::with('podcast') 
        ->where('title', 'like', "%{$search}%") 
        ->orWhereHas('podcast', function ($query) use ($search) { 
            $query->where('title', 'like', "%{$search}%");
        })
        ->orWhereDate('created_at', $search) 
        ->get();

    return response()->json($episodes);
}

}
