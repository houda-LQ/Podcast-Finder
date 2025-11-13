<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Podcast::all());
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
    public function store(StorePodcastRequest $request)
{
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
}
    

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $podcast = Podcast::with(['host', 'episodes'])->findOrFail($id);

    return response()->json($podcast);
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
   public function update(UpdatePodcastRequest $request, $id)
{
    $podcast = Podcast::findOrFail($id);

     $this->authorize('update', $podcast);

    $data = $request->validated();
    

     if ($request->hasFile('image')) {
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $data['image'] = $uploadedFileUrl;
    }


    $podcast->update($data);

    return response()->json($podcast);
}


        
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $podcast=Podcast::findOrFail($id);
        $this->authorize('delete', $podcast);

        $podcast->delete();
        return response()->json(["message"=>"Podcast supprimé"]);
    }

public function search(Request $request)
{
    $search = $request->input('query');

    $podcasts = Podcast::with('host') 
        ->where('title', 'like', "%{$search}%")
        ->orWhereHas('host', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->get();

    return response()->json($podcasts);
}




}
