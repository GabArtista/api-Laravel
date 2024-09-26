<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'gabrielw.dev@gmail.com')->first()) {
            User::create([
                'name' => 'Gabriel',
                'email' => 'gabrielw.dev@gmail.com',
                'password' => Hash::make('123456a', ['rounds' => 12]),
            ]);
        }
    }
}
