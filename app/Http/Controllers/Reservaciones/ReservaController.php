<?php

namespace App\Http\Controllers\Reservaciones;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventoResource;
use App\Http\Resources\ReservaResource;
use App\Models\Evento;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
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


    public function indexuser()
    {
        //
        $user=Auth::user();
        $reservas=Reserva::where('user_id',$user->id)->get();
        if(!$reservas->first()){
            return $this->sendResponse(message: 'The client not have a reservation');
        }

        return $this->sendResponse(message: 'Reserv list generated successfully', result: [
            'reservas' => ReservaResource::collection($reservas),
            
            
        ]);
    }

    // public function misreservas(Reserva $reserva){
    //     return $this->sendResponse(message: 'Reserva-Event details'.$reserva->eventos, result: [

    //     ]);
    // }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request ,Evento $evento)
    {
        
        //
        if($evento->cupos==0){
            return $this->sendResponse(message: 'No exist reservs for this event'); 
        }
       
        $user=Auth::user();
       // $reservacion->date=$evento->evento;
        //$reservacruce=$user->reserva;
        //if($evento->evento==[$reservacruce->date]){
        //    return $this->sendResponse(message: 'No puedes hacer reservaciones con la misma fecha y hora'); 

        //}
        $reservacion=new Reserva();
        $num=$evento->cupos;
        $evento->cupos=$evento->cupos-1;
        $reservacion->numero=$num;
        $evento->save();
        $reservacion->eventos_id=$evento->id;
        $user->reserva()->save($reservacion);

        return $this->sendResponse(message: 'Event list generated successfully  '); 
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
        return $this->sendResponse(message: 'Reserva-Event details', result: [
            'eventos' => new EventoResource($evento),
        ]);
    }

   
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reserva $reserva)
    {
        
        $evento=Evento::where('id',$reserva->eventos_id)->first();
        if($evento->cupos==0){
            return $this->sendResponse(message: 'No exist reservs for this event'); 
        }
        
        $user=Auth::user();
        if(!$user->reserva->first()){
            return $this->sendResponse(message: 'This user not have reservs'); 
        }
        
        if($reserva->user_id!=$user->id){
            return $this->sendResponse(message: 'This reservs not have you'); 

        }
        $evento->cupos=$evento->cupos+1;
        $evento->save();
        $reserva->delete();
        return $this->sendResponse("Reserv delete succesfully", 200);

    }
}
