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
        Schema::create('responsibility', function (Blueprint $table) {
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('taskId');

            $table->primary(['userId', 'taskId']);

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('taskId')->references('taskId')->on('tasks')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsibility');
    }
};
