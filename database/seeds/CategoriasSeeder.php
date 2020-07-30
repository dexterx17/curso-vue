<?php

use Illuminate\Database\Seeder;

use App\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $c = new Categoria();
        $c->nombre = "Viveres";
        $c->descripcion = "fideos";
        $c->condicion = 1;
        $c->save();

        $c = new Categoria();
        $c->nombre = "Dulces";
        $c->descripcion = "cosas de tomar";
        $c->condicion = 0;
        $c->save();
    }
}
