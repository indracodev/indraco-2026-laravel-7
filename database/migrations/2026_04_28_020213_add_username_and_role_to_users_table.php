<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsernameAndRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('master_admin', function (Blueprint $table) {
            $table->string('username')->unique()->after('nama');
            $table->string('role')->default('admin')->after('username'); // superadmin, admin, markom
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_admin', function (Blueprint $table) {
            $table->dropColumn(['username', 'role']);
            $table->string('email')->nullable(false)->change();
        });
    }
}
