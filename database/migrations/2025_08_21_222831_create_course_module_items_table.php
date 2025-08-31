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
        Schema::create('course_module_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->references('id')->on('course_modules')->constrained()->cascadeOnDelete();
            $table->json('content');
            $table->time('duration')->nullable();
            $table->string('video_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_module_items');
    }
};
