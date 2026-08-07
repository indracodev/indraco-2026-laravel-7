<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merek_id')->nullable()->constrained('master_merek')->cascadeOnDelete();
            $table->string('nama_collection', 100);
            $table->string('slug', 100);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_collection');
    }
}
