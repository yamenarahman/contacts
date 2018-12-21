<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Contact::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'company' => $faker->company
    ];
});
