<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Articulo;
use App\Proveedor;
use App\Persona;
use App\Categoria;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Categoria::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'descripcion' => $faker->text,
        'condicion' => $faker->boolean
    ];
});
$factory->define(Articulo::class, function (Faker $faker) {
    $cats_ids = DB::table('categorias')->select('id')->get();
    return [
        'codigo' => Str::random(10),
        'nombre' => $faker->name,
        'precio_venta' => $faker->numberBetween(1,50),
        'stock' => 10,
        'categoria_id' =>$faker->randomElement($cats_ids)->id
    ];
});

$factory->define(Persona::class, function (Faker $faker) {
    $tipos_dos = ['DNI','RUC','PASS'];
    return [
        'nombre' => $faker->name,
        'tipo_documento' => Str::random(10),
        'num_documento' => $faker->randomElement($tipos_dos),
        'direccion' => $faker->address,
        'telefono' => $faker->phoneNumber,
        'email' => $faker->email
    ];
});
$factory->define(User::class, function (Faker $faker) {
    
    $roles = [1,2,3];
    return [
        'usuario' => $faker->userName,
        'password' => bcrypt('123456'),
        'condicion' => $faker->boolean,
        'idrol' => $faker->randomElement($roles)
    ];
});

$factory->define(Proveedor::class, function (Faker $faker) {

    return [
        'contacto' => $faker->name,
        'telefono_contacto' => $faker->phoneNumber
    ];
});
