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
        Schema::create('pantry_items', function (Blueprint $table): void {
            $table->id()->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('ingredient_id')->max(24);
            $table->integer('unit_index')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pantry_items');
    }
};
