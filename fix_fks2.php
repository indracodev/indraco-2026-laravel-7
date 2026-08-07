<?php

$migrationsDir = __DIR__ . '/database/migrations';
$files = glob($migrationsDir . '/*.php');

$foreignKeyReplacements = [
    "constrained('brands')" => "constrained('master_merek')",
    "constrained('categories')" => "constrained('master_kategori')",
    "constrained('collections')" => "constrained('master_collection')",
    "constrained('types')" => "constrained('master_type')",
    "constrained('variants')" => "constrained('master_variant')",
    "constrained('news')" => "constrained('master_news')",
    "constrained('products')" => "constrained('master_produk')",
    "constrained('banners')" => "constrained('master_banners')",
    "constrained('contacts')" => "constrained('master_kontak')",
    "constrained('settings')" => "constrained('master_settings')",
    "constrained('users')" => "constrained('master_admin')",
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
        echo "Fixed constrained foreign keys in " . basename($file) . "\n";
    }
}
echo "Done.\n";
