<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupportSubCategory;

class SupportSubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupportSubCategory::create([
            'category_id' => '1',
            'sub_name' => 'Recover Your Account',
            'sub_description' => 'Restore control over a lost or compromised account.',
            'content_id' => 1,
        ]);

        SupportSubCategory::create([
            'category_id' => '1',
            'sub_name' => 'Forgot Username',
            'sub_description' => 'Use your email and other info to recover your username.',
            'content_id' => 3,
        ]);

        SupportSubCategory::create([
            'category_id' => '1',
            'sub_name' => 'Forgot Password',
            'sub_description' => 'Use your email and other info to recover your username.',
            'content_id' => 2,
        ]);

        SupportSubcategory::create([
            'category_id' => '2',
            'sub_name' => 'Refund an In-Game Purchase',
            'sub_description' => 'See if any of your recent game purchases qualify for an in-game refund.',
            'content_id' => 2,
        ]);
    }
}
