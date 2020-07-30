<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Articulo;
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

$factory->define(Articulo::class, function (Faker $faker) {
    return [
        'codigo' => Str::random(10),
        'nombre' => $faker->name,
        'precio_venta' => $faker->name,
        'stock' => 10,
        'categoria_id' =>1
    ];
});
