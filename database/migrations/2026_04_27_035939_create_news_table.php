<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_news', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('judul', 255);
            $table->string('tanggal_teks', 100)->nullable();
            $table->text('konten')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_news');
    }
}
