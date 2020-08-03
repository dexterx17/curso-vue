<?php

use Illuminate\Database\Seeder;

use App\Persona;
use App\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $personas = factory(Persona::class,100)->create([]);

        foreach($personas as $p){
            $proveedor = factory(Proveedor::class)->create(['id'=>$p->id]);
        }
    }
}
