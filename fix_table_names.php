<?php

$migrationsDir = __DIR__ . '/database/migrations';

$replacements = [
    '2026_04_27_035937_create_brands_table.php' => ['brands' => 'master_merek'],
    '2026_04_27_035937_create_categories_table.php' => ['categories' => 'master_kategori'],
    '2026_04_27_035938_create_collections_table.php' => ['collections' => 'master_collection'],
    '2026_04_27_035938_create_types_table.php' => ['types' => 'master_type'],
    '2026_04_27_035938_create_variants_table.php' => ['variants' => 'master_variant'],
    '2026_04_27_035939_create_news_table.php' => ['news' => 'master_news'],
    '2026_04_27_035939_create_products_table.php' => ['products' => 'master_produk'],
    '2026_04_27_035940_create_banners_table.php' => ['banners' => 'master_banners'],
    '2026_04_27_035940_create_contacts_table.php' => ['contacts' => 'master_kontak'],
    '2026_04_27_045944_create_settings_table.php' => ['settings' => 'master_settings'],
    // Also fix the seeder that I wrongly modified
    '2026_05_08_000001_seed_seo_page_settings.php' => ['settings' => 'master_settings', "'key'" => "'setting_key'", "'value'" => "'setting_value'"],
    // Also fix map position that I wrongly modified
    '2026_05_11_064026_add_map_position_to_master_variant_table.php' => ['variants' => 'master_variant']
];

foreach ($replacements as $file => $rules) {
    $path = $migrationsDir . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        foreach ($rules as $old => $new) {
            // Replace Schema::create('old'
            $content = str_replace("Schema::create('$old'", "Schema::create('$new'", $content);
            // Replace Schema::dropIfExists('old'
            $content = str_replace("Schema::dropIfExists('$old'", "Schema::dropIfExists('$new'", $content);
            // Replace Schema::table('old'
            $content = str_replace("Schema::table('$old'", "Schema::table('$new'", $content);
            
            // For the seeder file
            if ($file == '2026_05_08_000001_seed_seo_page_settings.php') {
                $content = str_replace("DB::table('settings')", "DB::table('master_settings')", $content);
                $content = str_replace("'key'", "'setting_key'", $content);
                $content = str_replace("'value'", "'setting_value'", $content);
            }
        }
        
        file_put_contents($path, $content);
        echo "Fixed $file\n";
    }
}

// Special case for products table, it might have foreign keys to brands, categories, collections, types
$productsMigration = $migrationsDir . '/2026_04_27_035939_create_products_table.php';
if (file_exists($productsMigration)) {
    $content = file_get_contents($productsMigration);
    $content = str_replace("on('brands')", "on('master_merek')", $content);
    $content = str_replace("on('categories')", "on('master_kategori')", $content);
    $content = str_replace("on('collections')", "on('master_collection')", $content);
    $content = str_replace("on('types')", "on('master_type')", $content);
    file_put_contents($productsMigration, $content);
}

// Check for news table foreign keys
$newsMigration = $migrationsDir . '/2026_04_27_035939_create_news_table.php';
if (file_exists($newsMigration)) {
    $content = file_get_contents($newsMigration);
    $content = str_replace("on('categories')", "on('master_kategori')", $content);
    file_put_contents($newsMigration, $content);
}

// Check for en_fields_to_news
$newsEnMigration = $migrationsDir . '/2026_04_28_091628_add_en_fields_to_news_table.php';
if (file_exists($newsEnMigration)) {
    $content = file_get_contents($newsEnMigration);
    $content = str_replace("Schema::table('news'", "Schema::table('master_news'", $content);
    file_put_contents($newsEnMigration, $content);
}

echo "Done fixing table names.\n";
