<?php

namespace App\Services;

//use Illuminate\Support\Facades\Http;
use Faker\Factory as Faker;

class PostService
{
    /**
     * This method would get the third part post
     */
    public static function getPostFrom($mockSocialNetwork = null)
    {
        //$response = Http::get(https://wwww.{$mockSocialNetwork}.com/posts);

        $faker = Faker::create();

        $response = [
            'author' => $faker->name,
            'title' => $faker->sentence,
            'content' => $faker->paragraph
        ];

        return $response;
    }
}
