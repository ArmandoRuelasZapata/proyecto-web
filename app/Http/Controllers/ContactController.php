<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;


class ContactController extends Controller
{
    //
    public function index(){
        $mensajes = Contact::all();
        return view('indexcontact',compact('mensajes'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'=> 'required|min:10',
            'correo' => 'required|email|min:10',
            'prioridad'=> 'required|string',
            'asunto'=>'required|min:10|max:80',
            'mensaje'=> 'required|min:10|max:3000'
        ]);
        $contacto = new Contact;
        $contacto->nombre = $validated['nombre'];
        $contacto->correo = $validated['correo'];
        $contacto->prioridad = $validated['prioridad'];
        $contacto->asunto = $validated['asunto'];
        $contacto-> mensaje= $validated['mensaje'];
        $contacto->save();

        return redirect()->back();
    }
}
