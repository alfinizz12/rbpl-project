<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owners = User::all();

        foreach ($owners as $owner) {
            Workspace::factory()->count(4)->create([
                'ownerId' => $owner->id,
            ]);
        }
    }
}
