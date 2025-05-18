<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admir Adrovic',
            'email' => 'aa@bscg.me',
            'password' => 'adokica2009',
        ]);

        Category::create([
            'name' => 'Novosti',
            'slug' => 'novosti',
        ]);
    }
}
