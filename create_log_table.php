<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('master_log_aktivitas')) {
    Schema::create('master_log_aktivitas', function (Blueprint $table) {
        $table->id();
        $table->integer('user_id')->nullable();
        $table->string('aktivitas'); // created, updated, deleted, login, logout
        $table->string('model')->nullable();
        $table->unsignedBigInteger('model_id')->nullable();
        $table->text('data_lama')->nullable();
        $table->text('data_baru')->nullable();
        $table->string('ip_address')->nullable();
        $table->string('user_agent')->nullable();
        $table->string('url')->nullable();
        $table->timestamps();
    });
    echo "Table master_log_aktivitas created successfully.\n";
} else {
    echo "Table already exists.\n";
}
