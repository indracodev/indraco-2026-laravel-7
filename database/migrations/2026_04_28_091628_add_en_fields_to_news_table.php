<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnFieldsToNewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('master_news', function (Blueprint $table) {
            $table->string('judul_en', 255)->nullable()->after('judul');
            $table->string('tanggal_teks_en', 100)->nullable()->after('tanggal_teks');
            $table->text('konten_en')->nullable()->after('konten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_news', function (Blueprint $table) {
            $table->dropColumn(['judul_en', 'tanggal_teks_en', 'konten_en']);
        });
    }
}
