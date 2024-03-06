<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'project_name' => 'MetaFinx',
            'description' => 'Trading',
            'show' => 1
        ]);

        Project::create([
            'project_name' => 'KinderTown',
            'description' => 'Education',
            'show' => 1
        ]);
    }
}
