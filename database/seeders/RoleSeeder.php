<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['role_type' => 'admin']);
        Role::create(['role_type' => 'ceo']);
        Role::create(['role_type' => 'manager']);
        Role::create(['role_type' => 'member']);
    }
}
