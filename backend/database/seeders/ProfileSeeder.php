<?php

namespace Database\Seeders;

use App\Enums\ProfileEnum;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create(['name' => ProfileEnum::ADMIN->value, 'description' => 'Full permission in the system']);  
        Profile::create(['name' => ProfileEnum::USER->value, 'description' => 'Permission for everything except registering other companies or managing affiliates from other companies']);  
    }
}
