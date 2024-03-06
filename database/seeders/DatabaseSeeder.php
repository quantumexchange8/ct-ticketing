<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
       $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            TitleSeeder::class,
            SubtitleSeeder::class,
            ContentSeeder::class,
            SupportCategorySeeder::class,
            SupportSubCategorySeeder::class,
            TicketStatusSeeder::class,
            EmailSignatureSeeder::class,
            ProjectSeeder::class,
       ]);
    }
}
