<?php

namespace App\Http\Controllers\Contactanos;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactanosResource;
use App\Models\Contactanos;
use Illuminate\Http\Request;

class ContactanosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $contacto=Contactanos::all();
        return $this->sendResponse(message: 'Contactos list generated successfully', result: [
            'contactanos' => ContactanosResource::collection($contacto),
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
       $contactos= $request -> validate([
            'nombre' => ['required', 'string', 'min:3', 'max:45'],
            'apellido' => ['required', 'string', 'min:3', 'max:45'],
            'correo' => ['required', 'string', 'min:5', 'max:30', 'unique:contactanos'],
            'puesto' => ['required', 'string', 'min:5', 'max:100', 'unique:contactanos'],
            'contactanos' => ['required','regex:/(01)[0-9]{9}/', 'numeric', 'digits:10'],
            
        ]);
         
         Contactanos::create($contactos);
         return $this->sendResponse('Contact created succesfully',204);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contactanos $contactanos)
    {
        //
        return $this->sendResponse(message: 'Contactanos details', result: [
            'contactanos' => new ContactanosResource($contactanos)
        ]);
    }

   
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contactanos $contactanos)
    {
        //
        $data=$request -> validate([
            'nombre' => ['required', 'string', 'min:3', 'max:45'],
            'apellido' => ['required', 'string', 'min:3', 'max:45'],
            'correo' => ['required', 'string', 'min:5', 'max:30' ],
            'puesto' => ['required', 'string', 'min:5', 'max:100'],
            'contactanos' => ['required','regex:/(01)[0-9]{9}/', 'numeric', 'digits:10'],
        ]);
        $contactanos->fill($data);
        $contactanos->save();
        return $this->sendResponse('Contact update succesfully',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contactanos $contactanos)
    {
        //
        $contactanos->delete();
        return $this->sendResponse("Contact delete succesfully", 200);
    }
}
