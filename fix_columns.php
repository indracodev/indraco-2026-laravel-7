<?php

$migrationsDir = __DIR__ . '/database/migrations';

function replaceInFile($file, $replacements) {
    global $migrationsDir;
    $path = $migrationsDir . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $modified = false;
        foreach ($replacements as $old => $new) {
            if (strpos($content, $old) !== false) {
                $content = str_replace($old, $new, $content);
                $modified = true;
            }
        }
        if ($modified) {
            file_put_contents($path, $content);
            echo "Fixed columns in $file\n";
        }
    }
}

// 1. Categories
replaceInFile('2026_04_27_035937_create_categories_table.php', [
    "'name'" => "'nama_kategori'",
    "'order'" => "'urutan'",
    "'icon_path'" => "'ikon_path'"
]);

// 2. Collections
replaceInFile('2026_04_27_035938_create_collections_table.php', [
    "'name'" => "'nama_collection'",
    "'brand_id'" => "'merek_id'",
    "'icon_path'" => "'ikon_path'"
]);

// 3. Types
replaceInFile('2026_04_27_035938_create_types_table.php', [
    "'name'" => "'nama_type'",
    "'icon_path'" => "'ikon_path'"
]);

// 4. Brands
replaceInFile('2026_04_27_035937_create_brands_table.php', [
    "'name'" => "'nama_merek'",
    "'description'" => "'deskripsi'"
]);

// 5. Products
replaceInFile('2026_04_27_035939_create_products_table.php', [
    "'name'" => "'nama_produk'",
    "'packing'" => "'kemasan'",
    "'brand_id'" => "'merek_id'",
    "'category_id'" => "'kategori_id'",
    "'collection_id'" => "'collection_id'", // Keep if same, but let's check
    "'type_id'" => "'type_id'",
    "'variant_id'" => "'variant_id'"
]);

// 6. News
replaceInFile('2026_04_27_035939_create_news_table.php', [
    "'title'" => "'judul'",
    "'date_text'" => "'tanggal_teks'",
    "'category_id'" => "'kategori_id'" // if any
]);
replaceInFile('2026_04_28_091628_add_en_fields_to_news_table.php', [
    "'title_en'" => "'judul_en'",
    "'date_text_en'" => "'tanggal_teks_en'",
    "'content_en'" => "'konten_en'"
]);

// 7. Variants
replaceInFile('2026_04_27_035938_create_variants_table.php', [
    "'name'" => "'variant_name'",
    "->string('slug'" => "->string('variant_name_eng')->nullable();\n            \$table->string('slug'",
    "'description'" => "'description'",
    "->text('description')->nullable();" => "->text('description')->nullable();\n            \$table->text('description_eng')->nullable();",
    "->string('taste', 255)->nullable();" => "->string('taste', 255)->nullable();\n            \$table->string('taste_eng', 255)->nullable();",
    "->string('roast', 255)->nullable();" => "->string('roast', 255)->nullable();\n            \$table->string('roast_eng', 255)->nullable();",
    "->string('ingredient', 255)->nullable();" => "->string('ingredient', 255)->nullable();\n            \$table->string('ingredient_eng', 255)->nullable();",
    "\$table->timestamps();" => "\$table->integer('sort_order')->default(0);\n            // \$table->timestamps();" // removed timestamps
]);

// 8. Users
replaceInFile('2014_10_12_000000_create_users_table.php', [
    "'name'" => "'nama'"
]);

echo "Column fix script completed.\n";

