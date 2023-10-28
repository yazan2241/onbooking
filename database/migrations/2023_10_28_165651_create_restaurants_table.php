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
            $table->string('name');
            $table->string('contactName');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('tableNumbers');
            $table->string('category');
            $table->string('startWork');
            $table->string('endWork');
            $table->string('service');
            $table->string('image');
            $table->double('rate')->default(0.0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
