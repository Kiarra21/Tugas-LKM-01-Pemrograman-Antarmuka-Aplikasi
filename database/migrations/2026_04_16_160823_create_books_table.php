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
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('author_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('title', 150)->index();
            $table->string('slug', 170)->unique();
            $table->string('isbn', 20)->unique();
            $table->unsignedSmallInteger('published_year')->index();
            $table->unsignedInteger('stock')->default(0);
            $table->text('synopsis')->nullable();
            $table->timestamps();

            $table->index(['category_id', 'published_year']);
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
