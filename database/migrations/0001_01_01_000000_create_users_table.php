<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->nullable();
            $table->string('userName')->unique()->nullable();
            $table->string('email')->unique();
            $table->enum('user_type', ['user', 'instructor', 'admin'])->default('user');
            $table->boolean('is_active')->default(true);
            $table->string('phone')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('gender')->nullable();
            $table->string('country')->nullable();
            $table->text('bio')->nullable();
            $table->string('otp')->nullable();
            $table->string('government')->nullable();
            $table->string('image')->nullable();
            $table->string('social_id')->nullable();
            $table->string('social_type')->nullable();
            $table->timestamp('otp_expires_at')->nullable();

            // instructor-specific fields
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->nullable()->default('pending');
            $table->text('experience')->nullable(); 
            $table->string('linkedIn')->nullable();
            $table->string('cv')->nullable();
            $table->json('desc')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
