<?php

namespace App\Http\Controllers\ListaReproduccion;

use App\Http\Controllers\Controller;
use App\Http\Resources\MusicaFourResource;
use App\Models\MusicFour;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class MusicaFourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $banner=MusicFour::all();
        return $this->sendResponse(message: 'Music Four list generated successfully', result: [
            'musics' => MusicaFourResource::collection($banner),
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
         MusicFour::create(
            [
                "tema"=>$request->tema,
                "genero"=>$request->genero,
                "descripcion"=>$request->descripcion,
                "duracion"=>$request->duracion,
                "imagen"=>$url1,
                "audio"=>$url,
            ]
         );
         return $this->sendResponse('MusicFour created succesfully',204);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MusicFOur $musicfour)
    {
        //
        return $this->sendResponse(message: 'Musics details', result: [
            'musicsFour' => new MusicaFourResource($musicfour)
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
    public function update(Request $request, MusicFour $musicfour)
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
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'listFour']);
        $url = $uploadedFileUrl->getSecurePath();
        $uploadedFileUrl1=Cloudinary::upload($file2->getRealPath(),['folder'=>'AudiosFour']);
        $url1=$uploadedFileUrl1->getSecurePath();
        $musicfour->update([
                "tema"=>$request->tema,
                "genero"=>$request->genero,
                "descripcion"=>$request->descripcion,
                "duracion"=>$request->duracion,
                "imagen"=>$url1,
                "audio"=>$url,
        ]);
        return $this->sendResponse("MusicFour update succesfully", 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MusicFour $musicfour)
    {
        //
        $musicfour->delete();
        return $this->sendResponse("MusicOne delete succesfully", 200);
    }
}
