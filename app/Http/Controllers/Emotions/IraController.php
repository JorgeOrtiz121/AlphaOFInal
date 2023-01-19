<?php

namespace App\Http\Controllers\Emotions;

use App\Http\Controllers\Controller;
use App\Http\Resources\IraResource;
use App\Http\Resources\MusicaOneResource;
use App\Models\Ira;
use App\Models\MusicaOne;
use Cloudinary\Api\Upload\UploadApi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class IraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ira=Ira::all();
        return $this->sendResponse(message: 'Emotions list generated successfully', result: [
            'emociones' => IraResource::collection($ira),
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
            'Tema' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            
        ]);

        $ira= $request ->validate([
            'video' => ['file'],
        ]);
        $file = $ira['video'];
        $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(),['folder'=>'emotions']);
        $url = $uploadedFileUrl->getSecurePath();
       // $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(),['folder'=>'emotions','resource_type' => 'video',
        //'public_id' => 'myfolder/mysubfolder/dog_closeup',
        //'chunk_size' => 6000000,
        //'eager' => [
        //  ['width' => 300, 'height' => 300, 'crop' => 'pad'], 
         // ['width' => 160, 'height' => 100, 'crop' => 'crop', 'gravity' => 'south']], 
        //'eager_async' => true, ]);
        //$url = $uploadedFileUrl->getSecurePath();
        //dd($url);
       //$file = $ira['video'];
        //$url=(new UploadApi())->upload($file,['folder'=>'emotions','resource_type'=>'video','chunk_size'=>6000000]);
      
         Ira::create(
            [
                "Tema"=>$request->Tema,
                "descripcion"=>$request->descripcion,
                "video"=>$url
            ]
         );
         return $this->sendResponse('Emotion created succesfully',204);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ira $ira)
    {
        //
        $musica=MusicaOne::all();
        return $this->sendResponse(message: 'Emotion details', result: [
            'iras' => new IraResource($ira),
            'music'=> MusicaOneResource::collection( $musica),

        ]);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Ira $emotion)
    {
        //
        $request->validate([
            'Tema' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            
        ]);

        $ira= $request -> validate([
            'video' => ['nullable','file','mimes:mp4','max:6000000'],
        ]);
        $file = $ira['video'];
        $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(),['folder'=>'emotions']);
        $url = $uploadedFileUrl->getSecurePath();

         $emotion->update([
            "Tema"=>$request->Tema,
            "descripcion"=>$request->descripcion,
            "video"=>$url
         ]);
         return $this->sendResponse('Emotion update succesfully',200);
    }


       
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ira $ira)
    {
        //
        $ira->delete();
        return $this->sendResponse("Emotion delete succesfully", 200);
    }
}
