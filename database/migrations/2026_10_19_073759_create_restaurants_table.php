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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        Schema::create('city_restaurant', function (Blueprint $table) {
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('restaurant_id')->constrained('restaurants')->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['city_id', 'restaurant_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_restaurant');
        Schema::dropIfExists('restaurants');
    }
};
