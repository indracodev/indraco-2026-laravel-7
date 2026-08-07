<?php

$migrationsDir = __DIR__ . '/database/migrations';
$files = glob($migrationsDir . '/*.php');

$foreignKeyReplacements = [
    "on('brands')" => "on('master_merek')",
    "on('categories')" => "on('master_kategori')",
    "on('collections')" => "on('master_collection')",
    "on('types')" => "on('master_type')",
    "on('variants')" => "on('master_variant')",
    "on('news')" => "on('master_news')",
    "on('products')" => "on('master_produk')",
    "on('banners')" => "on('master_banners')",
    "on('contacts')" => "on('master_kontak')",
    "on('settings')" => "on('master_settings')",
    "on('users')" => "on('master_admin')",
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $modified = false;
    foreach ($foreignKeyReplacements as $old => $new) {
        if (strpos($content, $old) !== false) {
            $content = str_replace($old, $new, $content);
            $modified = true;
        }
    }
    if ($modified) {
        file_put_contents($file, $content);
        echo "Fixed foreign keys in " . basename($file) . "\n";
    }
}
echo "Done.\n";
