<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'username' => 'Admin123',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Test1234.'),
            'role_id' => 1,
            'category_id' => 0,
            'manage_documentation' => 1,
            'manage_support_tool' => 1,
            'manage_all_ticket' => 1,
            'manage_ticket_in_category' => 1,
            'manage_own_ticket' => 1,
        ]);

        User::create([
            'name' => 'Guin',
            'username' => 'Member123',
            'email' => 'member@gmail.com',
            'password' => Hash::make('Test1234.'),
            'role_id' => 2,
            'category_id' => 1,
            'manage_documentation' => 0,
            'manage_support_tool' => 0,
            'manage_all_ticket' => 0,
            'manage_ticket_in_category' => 1,
            'manage_own_ticket' => 1,
        ]);
    }
}
