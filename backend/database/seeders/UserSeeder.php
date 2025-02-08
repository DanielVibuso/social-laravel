<?php

namespace Database\Seeders;

use App\Enums\ProfileEnum;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
            'email' => 'admin@curotec.com.br',
            'password' => 'qwe123',
        ]);
    }
}
