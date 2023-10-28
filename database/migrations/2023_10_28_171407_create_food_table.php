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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('price');
            $table->string('calories');
            $table->string('fat');
            $table->string('carbs');
            $table->string('protein');
            $table->string('label');
            $table->string('restaurantId');
            $table->string('image');
            $table->double('rate');
            $table->integer('favorate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
