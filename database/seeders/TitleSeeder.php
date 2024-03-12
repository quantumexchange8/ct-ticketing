<?php

namespace Database\Seeders;

use App\Models\Title;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Title::create([
            'title_name' => 'Introduction',
            't_sequence' => '1',
            'project_id' => '1'
        ]);

        Title::create([
            'title_name' => 'Methodology',
            't_sequence' => '2',
            'project_id' => '1'
        ]);

        Title::create([
            'title_name' => 'Conclusion',
            't_sequence' => '1',
            'project_id' => '2'
        ]);
    }
}
