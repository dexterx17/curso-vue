<?php

use Illuminate\Database\Seeder;

use App\Articulo;

class ArticuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articulos = factory(Articulo::class,100)->create([]);
    }
}
