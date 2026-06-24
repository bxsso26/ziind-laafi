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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('property_usage');
            $table->string('contract_option');
            $table->string('zone', 100);
            $table->decimal('size', 10, 2);
            $table->decimal('price', 15, 2);
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status')->default('Publiée');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
