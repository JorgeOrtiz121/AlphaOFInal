<?php

namespace App\Http\Controllers\ListaReproduccion;

use App\Http\Controllers\Controller;
use App\Http\Resources\MusicaFiveResource;
use App\Models\MusicFive;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class MusicaFiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $banner=MusicFive::all();
        return $this->sendResponse(message: 'Music Five list generated successfully', result: [
            'musics' => MusicaFiveResource::collection($banner),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'tema' => ['required', 'string', 'min:3', 'max:45'],
            'genero' => ['required', 'string', 'min:3', 'max:200'],
            'descripcion' => ['required', 'string', 'min:3', 'max:500'],
            'duracion' => ['required', 'numeric'],
        ]);

        $list1= $request -> validate([
            'audio' => ['required','file','mimes:mp3','max:1000000000000'],
        ]);
        $list2=$request->validate([
            'imagen'=>['required','image','mimes:jpg,png,jpeg', 'max:10000'],
        ]);
        $file = $list1['audio'];
        $file2=$list2['imagen'];
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'listFour']);
        $url = $uploadedFileUrl->getSecurePath();
        $uploadedFileUrl1=Cloudinary::upload($file2->getRealPath(),['folder'=>'AudiosFour']);
        $url1=$uploadedFileUrl1->getSecurePath();
         MusicFive::create(
            [
                "tema"=>$request->tema,
                "genero"=>$request->genero,
                "descripcion"=>$request->descripcion,
                "duracion"=>$request->duracion,
                "imagen"=>$url1,
                "audio"=>$url,
            ]
         );
         return $this->sendResponse('MusicFive created succesfully',204);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MusicFive $musicfive)
    {
        //
        return $this->sendResponse(message: 'Musics details', result: [
            'musicsFive' => new MusicaFiveResource($musicfive)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MusicFive $musicfive)
    {
        //

        $request->validate([
            'tema' => ['required', 'string', 'min:3', 'max:45'],
            'genero' => ['required', 'string', 'min:3', 'max:200'],
            'descripcion' => ['required', 'string', 'min:3', 'max:500'],
            'duracion' => ['required', 'numeric'],
        ]);
        
        $list1= $request -> validate([
            'audio' => ['nullable','file','mimes:mp3','max:1000000000000'],
        ]);
        $list2=$request->validate([
            'imagen'=>['nullable','image','mimes:jpg,png,jpeg', 'max:10000'],
        ]);
        $file = $list1['audio'];
        $file2=$list2['imagen'];
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'listFive']);
        $url = $uploadedFileUrl->getSecurePath();
        $uploadedFileUrl1=Cloudinary::upload($file2->getRealPath(),['folder'=>'AudiosFive']);
        $url1=$uploadedFileUrl1->getSecurePath();
        $musicfive->update([
            "tema"=>$request->tema,
                "genero"=>$request->genero,
                "descripcion"=>$request->descripcion,
                "duracion"=>$request->duracion,
                "imagen"=>$url1,
                "audio"=>$url,
        ]);
        return $this->sendResponse("MusicFive update succesfully", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MusicFive $musicfive)
    {
        //
        $musicfive->delete();
        return $this->sendResponse("MusicFive delete succesfully", 200);
    }
}
