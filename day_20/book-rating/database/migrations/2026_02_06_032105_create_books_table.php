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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->jsonb('genres')->default('[]');
            $table->string('cover_path')->nullable();
            $table->boolean('is_adult')->default(false);
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->integer('ratings_count')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('avg_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
