<?php

namespace App\Providers;

use App\Models\Task;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    protected $policies = [
        Workspace::class => WorkspacePolicy::class,
        Board::class => BoardPolicy::class,
        Task::class => TaskPolicy::class
    ];
}
