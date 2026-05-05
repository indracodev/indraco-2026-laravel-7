<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrafficAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traffic_analytics', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('url', 2048);
            $blueprint->string('path', 1024);
            $blueprint->string('method', 10);
            $blueprint->string('ip_address', 45);
            $blueprint->text('user_agent')->nullable();
            $blueprint->text('referer')->nullable();
            $blueprint->unsignedBigInteger('user_id')->nullable();
            $blueprint->string('session_id')->nullable();
            $blueprint->timestamps();

            $blueprint->index('path');
            $blueprint->index('created_at');
            $blueprint->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traffic_analytics');
    }
}
