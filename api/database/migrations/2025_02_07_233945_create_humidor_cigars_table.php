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
        Schema::create('humidor_cigars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('humidor_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('ring', total: 8, places: 4)->nullable();
            $table->decimal('length', total: 8, places: 4)->nullable();
            $table->decimal('price_per_cigar', total: 12, places: 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('humidor_cigars');
    }
};
