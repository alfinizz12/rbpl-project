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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('taskId');
            $table->string('taskName');
            $table->date('post_date');
            $table->date('due_date')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('boardId');
            $table->timestamps();

            $table->foreign('boardId')->references('boardId')->on('boards')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
