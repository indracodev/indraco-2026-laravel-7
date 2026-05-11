<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMapPositionToMasterVariantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_variant', function (Blueprint $table) {
            $table->integer('map_size')->nullable()->default(100);
            $table->integer('map_top')->nullable()->default(0);
            $table->integer('map_right')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_variant', function (Blueprint $table) {
            $table->dropColumn(['map_size', 'map_top', 'map_right']);
        });
    }
}
