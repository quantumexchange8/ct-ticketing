<?php

namespace Database\Seeders;

use App\Models\Subtitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubtitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subtitle::create([
            'title_id' => '1',
            'subtitle_name' => 'What is introduction?',
            's_sequence' => '1',
        ]);

        Subtitle::create([
            'title_id' => '1',
            'subtitle_name' => 'Where is introduction?',
            's_sequence' => '2',
        ]);

        Subtitle::create([
            'title_id' => '2',
            'subtitle_name' => 'What is methodology?',
            's_sequence' => '1',
        ]);
    }
}
