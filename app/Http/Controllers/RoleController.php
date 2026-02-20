<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;//Añadir modelos 

class RoleController extends Controller
{
    //
    public function index()
    {
        $roles = Role::all();
    }

    public function store()
    {
        $role = new Role;
        $role -> name = "Admin";
        $role -> save();
    }

    public function update()
    {
        //$role = Role:: where("name","Admin")->first();
        $role = Role::find(1); //Funciona solamente con ID
        $role -> name = "Administrador";
        $role -> save();
    }

    public function delete()
    {
        Role::find(1)->delete(); //borrar por ID
    }
}
