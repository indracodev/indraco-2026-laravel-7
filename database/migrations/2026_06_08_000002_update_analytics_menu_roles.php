<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateAnalyticsMenuRoles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = json_encode(['superadmin', 'admin', 'markom']);

        // Update roles_allowed for Analytics header and its submenus
        DB::table('admin_menus')
            ->where('title', 'Analytics')
            ->orWhereIn('url', [
                'admin/traffic',
                'admin/traffic/audience',
                'admin/traffic/geo',
                'admin/traffic/behavior'
            ])
            ->update(['roles_allowed' => $roles]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original roles_allowed
        DB::table('admin_menus')
            ->where('title', 'Analytics')
            ->update(['roles_allowed' => json_encode(['superadmin', 'admin'])]);

        DB::table('admin_menus')
            ->whereIn('url', [
                'admin/traffic',
                'admin/traffic/audience',
                'admin/traffic/geo',
                'admin/traffic/behavior'
            ])
            ->update(['roles_allowed' => json_encode(['superadmin'])]);
    }
}
