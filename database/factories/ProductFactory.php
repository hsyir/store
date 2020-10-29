<?php

use Carbon\Carbon;
use Hsy\Store\Models\Product;

$factory->define(Product::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => \Illuminate\Support\Str::slug($faker->sentence),
        'body' => $faker->sentence,
        'category_id' => 1,
    ];
});