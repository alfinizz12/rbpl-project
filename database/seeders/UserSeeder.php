<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $adminRole = Role::where('role_type', 'admin')->first();
        // $memberRole = Role::where('role_type', 'member')->first();

        // User::factory()->count(2)->create(['roleId' => $adminRole->roleId]);
        // User::factory()->count(5)->create(['roleId' => $memberRole->roleId]);
    }
}
