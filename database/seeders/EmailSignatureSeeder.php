<?php

namespace Database\Seeders;

use App\Models\EmailSignature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSignatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailSignature::create([
            'user_id' => '1',
            'sign_off' => 'Best regards,',
            'font_family' => 'Allura',
            'font_size' => '35',
            'font_color' => '#000'
        ]);
    }
}
