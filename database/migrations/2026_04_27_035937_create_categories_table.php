<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('master_kategori')->nullOnDelete();
            $table->string('nama_kategori', 100);
            $table->string('slug', 100)->unique();
            $table->string('ikon_path', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kategori');
    }
}
