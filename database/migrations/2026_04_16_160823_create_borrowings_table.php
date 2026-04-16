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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('borrower_name', 120)->index();
            $table->string('borrower_email')->index();
            $table->date('borrowed_at')->index();
            $table->date('due_date')->index();
            $table->date('returned_at')->nullable()->index();
            $table->enum('status', ['borrowed', 'returned', 'late'])->default('borrowed')->index();
            $table->timestamps();

            $table->index(['book_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
