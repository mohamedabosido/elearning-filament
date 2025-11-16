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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('language_id')->constrained();
            $table->foreignId('instructor_id')->constrained();
            $table->decimal('price', 10, 4)->default(0);
            $table->decimal('discount_price', 10, 4)->nullable();
            $table->string('level')->default('beginner');
            $table->string('status')->default('waiting_for_approval');
            $table->integer('views')->default(0);
            $table->decimal('rate')->default(0);
            $table->json('files')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
