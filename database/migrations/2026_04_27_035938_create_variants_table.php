<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_variant', function (Blueprint $table) {
            $table->id();
            $table->string('variant_name', 100);
            $table->string('variant_name_eng')->nullable();
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->text('description_eng')->nullable();
            $table->string('taste', 255)->nullable();
            $table->string('taste_eng', 255)->nullable();
            $table->decimal('acidity', 2, 1)->default(0.0);
            $table->decimal('body', 2, 1)->default(0.0);
            $table->string('roast', 255)->nullable();
            $table->string('roast_eng', 255)->nullable();
            $table->string('ingredient', 255)->nullable();
            $table->string('ingredient_eng', 255)->nullable();
            $table->integer('sort_order')->default(0);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_variant');
    }
}
