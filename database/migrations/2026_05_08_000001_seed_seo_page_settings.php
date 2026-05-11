<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedSeoPageSettings extends Migration
{
    /**
     * Seed default SEO page settings into master_settings
     * and add SEO menu item to admin sidebar.
     */
    public function up(): void
    {
        // ── 1. Seed default SEO settings for each page ──────────────────────
        $pages = [
            'home'        => ['Home (Beranda)',   '/'],
            'about'       => ['About Us',         '/about'],
            'products'    => ['Products',         '/products'],
            'news'        => ['News',             '/news'],
            'businesses'  => ['Businesses',       '/businesses'],
            'stores'      => ['Stores',           '/stores'],
            'career'      => ['Career',           '/career'],
            'contact'     => ['Contact',          '/contact'],
            'equipment'   => ['Equipment',        '/equipment'],
            'foodservice' => ['Foodservice',      '/foodservice'],
            'download'    => ['Download',         '/download'],
        ];

        $siteUrl = config('app.url', 'https://indraco.com');

        foreach ($pages as $pageKey => [$pageLabel, $pagePath]) {
            $fullUrl = rtrim($siteUrl, '/') . $pagePath;

            $defaults = [
                "seo_page_{$pageKey}_title"          => "INDRACO – {$pageLabel} | Perusahaan FMCG Indonesia Sejak 1971",
                "seo_page_{$pageKey}_description"    => '',
                "seo_page_{$pageKey}_keywords"       => 'indraco, fmcg indonesia, kopi indonesia, teh indonesia',
                "seo_page_{$pageKey}_og_title"       => '',
                "seo_page_{$pageKey}_og_description" => '',
                "seo_page_{$pageKey}_og_image"       => '',
                "seo_page_{$pageKey}_canonical"      => $fullUrl,
            ];

            foreach ($defaults as $key => $value) {
                // Only insert if not already exists
                DB::table('master_settings')->insertOrIgnore([
                    'setting_key'   => $key,
                    'setting_value' => $value,
                ]);
            }
        }

        // ── 2. Add SEO Management menu to admin sidebar ──────────────────────
        // Find the "Tools" or "Pengaturan" group to attach to, or create standalone
        $parentMenu = DB::table('admin_menus')
            ->where('title', 'LIKE', '%Pengaturan%')
            ->orWhere('title', 'LIKE', '%Settings%')
            ->orWhere('title', 'LIKE', '%Tools%')
            ->first();

        // Check if SEO menu already exists
        $exists = DB::table('admin_menus')
            ->where('url', 'admin/seo')
            ->exists();

        if (!$exists) {
            // Get max order
            $maxOrder = DB::table('admin_menus')->max('order') ?? 0;

            DB::table('admin_menus')->insert([
                'parent_id'    => $parentMenu ? $parentMenu->id : null,
                'type'         => 'menu',
                'title'        => 'SEO Management',
                'url'          => 'admin/seo',
                'icon'         => 'bi bi-search',
                'order'        => $maxOrder + 1,
                'roles_allowed' => json_encode(['superadmin', 'admin']),
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Remove SEO settings
        DB::table('master_settings')
            ->where('setting_key', 'LIKE', 'seo_page_%')
            ->delete();

        // Remove SEO menu
        DB::table('admin_menus')
            ->where('url', 'admin/seo')
            ->delete();
    }
};
