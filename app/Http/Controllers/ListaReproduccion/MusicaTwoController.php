<?php

namespace App\Http\Controllers\ListaReproduccion;

use App\Http\Controllers\Controller;
use App\Http\Resources\MusicTwoResource;
use App\Models\MusicaTwo;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class MusicaTwoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $musicatwo=MusicaTwo::all();
        return $this->sendResponse(message: 'MusicTwo list generated successfully', result: [
            'musics' => MusicTwoResource::collection($musicatwo),
       
        ]);
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
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'listTwo']);
        $url = $uploadedFileUrl->getSecurePath();
        $uploadedFileUrl1=Cloudinary::upload($file2->getRealPath(),['folder'=>'AudiosTwo']);
        $url1=$uploadedFileUrl1->getSecurePath();
         MusicaTwo::create(
            [
                "tema"=>$request->tema,
                "genero"=>$request->genero,
                "descripcion"=>$request->descripcion,
                "duracion"=>$request->duracion,
                "imagen"=>$url1,
                "audio"=>$url,
            ]
         );
         return $this->sendResponse('MusicTwo created succesfully',204);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MusicaTwo $musictwo)
    {
        //
        return $this->sendResponse(message: 'Musics details', result: [
            'musicsTwo' => new MusicTwoResource($musictwo)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
  
    public function update(Request $request, MusicaTwo $musictwo)
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
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'listOne']);
        $url = $uploadedFileUrl->getSecurePath();
        $uploadedFileUrl1=Cloudinary::upload($file2->getRealPath(),['folder'=>'AudiosOne']);
        $url1=$uploadedFileUrl1->getSecurePath();
        $musictwo->update([
                "tema"=>$request->tema,
                "genero"=>$request->genero,
                "descripcion"=>$request->descripcion,
                "duracion"=>$request->duracion,
                "imagen"=>$url1,
                "audio"=>$url,
        ]);
        return $this->sendResponse("MusicTwo update succesfully", 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MusicaTwo $musictwo)
    {
        //
        $musictwo->delete();
        return $this->sendResponse("MusicTwo delete succesfully", 200);
    }
}
