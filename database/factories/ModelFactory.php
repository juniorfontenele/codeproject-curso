<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(CodeProject\Entities\User::class, function (Faker\Generator $faker) {
    $faker->addProvider(new \CodeProject\Faker\Pessoa($faker));

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('123456'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(CodeProject\Entities\Client::class, function (Faker\Generator $faker) {
	$faker->addProvider(new CodeProject\Faker\Pessoa($faker));
	$faker->addProvider(new CodeProject\Faker\Endereco($faker));
	$faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker));
	$faker->addProvider(new \CodeProject\Faker\Texto($faker));

	return [
		'name' => $faker->name,
		'responsible' => $faker->name,
		'email' => $faker->email,
		'phone' => $faker->phoneNumber,
		'address' => $faker->address,
		'obs' => $faker->text
	];
});

$factory->define(CodeProject\Entities\Project::class, function (Faker\Generator $faker) {
    $faker->addProvider(new \CodeProject\Faker\Pessoa($faker));

    return [
        'owner_id' => 1,
        'client_id' => 1,
        'name' => $faker->text(50),
        'description' => $faker->text(200),
        'progress' => rand(0,100),
        'status' => $faker->text(50),
        'due_date' => $faker->dateTimeBetween('now','+3 months')->format('Y-m-d')
    ];
});

$factory->define(CodeProject\Entities\ProjectTask::class, function (Faker\Generator $faker) {
	$faker->addProvider(new \CodeProject\Faker\Pessoa($faker));

	return [
		'name' => $faker->words(3,true),
		'project_id' => rand(1,3),
		'start_date' => $faker->dateTimeBetween('now','+3 months')->format('Y-m-d'),
		'due_date' => $faker->dateTimeBetween('now','+3 months')->format('Y-m-d'),
		'status' => rand(1,3)
	];
});

$factory->define(CodeProject\Entities\ProjectNote::class, function (Faker\Generator $faker) {
	return [
		'text' => $faker->sentence,
		'project_id' => rand(1,3),
	];
});
