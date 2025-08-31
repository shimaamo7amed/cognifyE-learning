<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coursevideos', function (Blueprint $table) {
            $table->id();
            $table->string('video_path');
            $table->json('title')->nullable();
            $table->json('desc')->nullable();
            $table->foreignId('module_item_id')->references('id')->on('course_module_items')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->references('id')->on('courses')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coursevideos');
    }
};
