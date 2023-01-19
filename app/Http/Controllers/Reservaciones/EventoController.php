<?php

namespace App\Http\Controllers\Reservaciones;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventoResource;
use App\Http\Resources\ReservaResource;
use App\Http\Resources\VercomentarioResource;
use App\Models\Evento;
use App\Models\Reserva;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $evento=Evento::all();
        return $this->sendResponse(message: 'Event list generated successfully', result: [
            'eventos' => EventoResource::collection($evento),
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
        $mytime=Carbon::now();
        
        $request->validate([
            'titulo' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            'evento' => ['required', 'date'],
            'contacto' => ['required', 'numeric', 'digits:10'],
            'cupos' => ['required', 'numeric'],
        ]);
        $evento= $request -> validate([
            'imagen' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
              
        ]);
        $time=$request->evento;
        $file = $evento['imagen'];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),['folder'=>'evento']);
        $url = $uploadedFileUrl->getSecurePath(); 

        if($time<$mytime){
            return $this->sendResponse(message: 'You event must is in present'); 
        }

         Evento::create([
            "titulo"=>$request->titulo,
            "imagen"=>$url,
            "descripcion"=>$request->descripcion,
            "evento"=>$request->evento,
            "contacto"=>$request->contacto,
            "cupos"=>$request->cupos

         ]);
         return $this->sendResponse('Event created succesfully',204);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $evento)
    {
        //
        $reserva=Reserva::where('eventos_id',$evento->id)->get();
        return $this->sendResponse(message: 'Reserva-Event details', result: [
            'eventos' => new EventoResource($evento),
            'reservaciones'=> VercomentarioResource::collection($reserva)
        ]);
    }

    /**
     * Show the form for editing the specified resource.qqqqq
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
    public function update(Request $request, Evento $eventoup)
    {
        //

        $request->validate([
            'titulo' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            'evento' => ['required', 'date'],
            'contacto' => ['required', 'numeric', 'digits:10'],
            'cupos' => ['required', 'numeric','digits:10'],
        ]);
        $evento= $request -> validate([
            'imagen' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
              
        ]);
        $file = $evento['imagen'];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),['folder'=>'evento']);
        $url = $uploadedFileUrl->getSecurePath(); 

         $eventoup->update([
            "titulo"=>$request->titulo,
            "imagen"=>$url,
            "descripcion"=>$request->descripcion,
            "evento"=>$request->evento,
            "contacto"=>$request->contacto,
            "cupos"=>$request->cupos

         ]);
         return $this->sendResponse('Event update succesfully',204);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evento $evento)
    {
        //
        $evento->delete();
        return $this->sendResponse("Event delete succesfully", 200);
    }
}
