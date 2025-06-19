<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Workspace::all()->each(function ($workspace) {
            Board::factory()->count(2)->create(['workspaceId' => $workspace->workspaceId]);
        });
    }
}
