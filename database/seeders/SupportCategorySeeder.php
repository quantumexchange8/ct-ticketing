<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupportCategory;

class SupportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupportCategory::create([
            'category_name' => 'Account Management',
        ]);

        SupportCategory::create([
            'category_name' => 'Billing Management',
        ]);

        SupportCategory::create([
            'category_name' => 'Customer Service',
        ]);

    }
}
