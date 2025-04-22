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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->string('category');
            $table->string('name');
            $table->text('description');
            $table->decimal('weight', 8, 2);
            $table->integer('calories');
            $table->integer('caloriesPercentage');
            $table->decimal('proteins', 5, 2);
            $table->integer('proteinsPercentage');
            $table->decimal('carbohydrates', 5, 2);
            $table->integer('carbohydratesPercentage');
            $table->decimal('lipids', 5, 2);
            $table->integer('lipidsPercentage');
            $table->decimal('sodium', 8, 2);
            $table->integer('sodiumPercentage');
            $table->string('image');
            $table->string('country', 2);
            $table->boolean('hideExtraInfo')->default(false);
            $table->string('urlPdf')->nullable();
            $table->boolean('active')->default(true);
            $table->decimal('fiber', 5, 2);
            $table->integer('fiberPercentage');
            $table->decimal('saturatedFats', 5, 2);
            $table->integer('saturatedFatsPercentage');
            $table->decimal('transFats', 5, 2);
            $table->integer('transFatsPercentage');
            $table->json('allergens');
            $table->decimal('sugarTotals', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
