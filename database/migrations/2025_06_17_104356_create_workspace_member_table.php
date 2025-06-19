<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workspace_member', function (Blueprint $table) {
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('workspaceId');

            $table->primary(['userId', 'workspaceId']);

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('workspaceId')->references('workspaceId')->on('workspaces')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_member');
    }
};
