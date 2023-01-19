<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\ReferenciaBannerResource;
use App\Models\Banner;
use App\Models\Evento;
use App\Models\Publicidad;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    //
    public function index()
    {
        $banner=Banner::all();
        return $this->sendResponse(message: 'Banner list generated successfully', result: [
            'banners' => BannerResource::collection($banner),
        ]);
    }

    public function indexnuevo()
    {
        $publicidad=Publicidad::all();
        $evento=Evento::all();
        $bannertotal=$publicidad->concat($evento);

        return $this->sendResponse(message: 'Banner list generated successfully', result: [
            'banners' => ReferenciaBannerResource::collection($bannertotal),
        ]);
    }


    public function store(Request $request)
    {
         // Validación de los datos de entrada
         // Crear un array asociativo de clave y valor
        $img=$request -> validate([
            'fotografias' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
            // https://laravel.com/docs/9.x/validation#rule-alpha-dash
        
           
        ]);
        $file = $img['fotografias'];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),['folder'=>'fotografias']);
        $url = $uploadedFileUrl->getSecurePath();
        Banner::create(["fotografias"=>$url]);
        // https://laravel.com/docs/9.x/eloquent#inserts
       
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Banner stored successfully');
    }

    public function destroy(Banner $banner){
        $banner->delete();

        return response(null, 204);
    }
}
