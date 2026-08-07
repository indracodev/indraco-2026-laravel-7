<?php

$dir = __DIR__ . '/database/migrations';
$files = glob($dir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'return new class extends Migration') !== false) {
        $basename = basename($file, '.php');
        // Remove the date prefix, e.g., 2014_10_12_000000_
        $parts = explode('_', $basename);
        $nameParts = array_slice($parts, 4);
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', implode('_', $nameParts))));
        
        $content = str_replace('return new class extends Migration', 'class ' . $className . ' extends Migration', $content);
        
        // Also need to remove the trailing semicolon at the end of the file
        // find `};` at the end of the file and replace with `}`
        $content = preg_replace('/};\s*$/', "}\n", $content);
        
        file_put_contents($file, $content);
        echo "Fixed: $basename -> $className\n";
    }
}
echo "Done.\n";
