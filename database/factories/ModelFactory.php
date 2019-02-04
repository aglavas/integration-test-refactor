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

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->defineAs(\App\User::class,'courses_case_empty', function ($faker){
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->defineAs(\App\User::class,'courses_case_ipa_started', function ($faker){
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->afterCreating(App\User::class, function (\App\User $user, $faker) {
    $modules = \App\Module::where('course_key', 'ipa')->limit(3)->get();
    $user->completed_modules()->attach($modules);

    $modules = \App\Module::where('course_key', 'ipa')->where('module_number', 5)->get();
    $user->completed_modules()->attach($modules);
}, 'courses_case_ipa_started');


$factory->defineAs(\App\User::class,'courses_case_ipa_last_module_finished', function ($faker){
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->afterCreating(App\User::class, function (\App\User $user, $faker) {
    $modules = \App\Module::where('course_key', 'ipa')->where('module_number', 7)->get();

    $user->completed_modules()->attach($modules);
}, 'courses_case_ipa_last_module_finished');


$factory->defineAs(\App\User::class,'courses_case_ipa_finished', function ($faker){
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->afterCreating(App\User::class, function (\App\User $user, $faker) {
    $modules = \App\Module::where('course_key', 'ipa')->limit(7)->get();

    $user->completed_modules()->attach($modules);
}, 'courses_case_ipa_finished');



$factory->defineAs(\App\User::class,'courses_case_iea_started', function ($faker){
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->afterCreating(App\User::class, function (\App\User $user, $faker) {
    $modules = \App\Module::where('course_key', 'ipa')->limit(7)->get();
    $user->completed_modules()->attach($modules);
    $modules = \App\Module::where('course_key', 'iea')->limit(2)->get();
    $user->completed_modules()->attach($modules);

}, 'courses_case_iea_started');




$factory->defineAs(\App\User::class,'courses_case_iea_ipa_finished', function ($faker){
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->afterCreating(App\User::class, function (\App\User $user, $faker) {
    $modules = \App\Module::where('course_key', 'ipa')->limit(7)->get();
    $user->completed_modules()->attach($modules);
    $modules = \App\Module::where('course_key', 'iea')->limit(7)->get();
    $user->completed_modules()->attach($modules);

}, 'courses_case_iea_ipa_finished');


$factory->defineAs(\App\User::class,'all_courses_finished', function ($faker){
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->afterCreating(App\User::class, function (\App\User $user, $faker) {
    $modules = \App\Module::where('course_key', 'ipa')->limit(7)->get();
    $user->completed_modules()->attach($modules);
    $modules = \App\Module::where('course_key', 'iea')->limit(7)->get();
    $user->completed_modules()->attach($modules);

    $modules = \App\Module::where('course_key', 'iaa')->where('module_number', 7)->get();

    $user->completed_modules()->attach($modules);

}, 'all_courses_finished');

