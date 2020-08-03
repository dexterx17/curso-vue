<?php

use Illuminate\Database\Seeder;

use App\Persona;
use App\User;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $persona = new Persona();
        $persona->nombre = "Jaime";
        $persona->tipo_documento = "DNI";
        $persona->num_documento = "1600392369";
        $persona->direccion = "home";
        $persona->telefono = "0983706086";
        $persona->email = "sistemas@santana.ec";
        $persona->save();

        $user = new User();
        $user->usuario = "dexter";
        $user->password = bcrypt("12345");
        $user->idrol = 1;
        $user->condicion='1';
        $user->id=$persona->id;
        $user->save();
    }
}
