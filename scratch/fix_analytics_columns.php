<?php

$db_host = '127.0.0.1';
$db_name = 'beta_indraco_2026';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Fixing traffic_analytics table structure...\n";
    
    $queries = [
        "ALTER TABLE `traffic_analytics` ADD `os` VARCHAR(50) NULL AFTER `user_agent`",
        "ALTER TABLE `traffic_analytics` ADD `browser` VARCHAR(50) NULL AFTER `os`",
        "ALTER TABLE `traffic_analytics` ADD `device_type` VARCHAR(20) NULL AFTER `browser`",
        "ALTER TABLE `traffic_analytics` ADD `country_code` VARCHAR(5) NULL AFTER `ip_address`",
        "ALTER TABLE `traffic_analytics` ADD `status_code` INT NULL AFTER `method`",
        "ALTER TABLE `traffic_analytics` ADD `response_time` INT NULL AFTER `status_code`",
        "ALTER TABLE `traffic_analytics` ADD `response_size` INT NULL AFTER `response_time`",
        "ALTER TABLE `traffic_analytics` ADD `scroll_depth` INT DEFAULT 0 AFTER `response_size`"
    ];

    foreach ($queries as $sql) {
        try {
            $pdo->exec($sql);
            echo "Success: $sql\n";
        } catch (Exception $e) {
            echo "Skipped (or Error): " . $e->getMessage() . "\n";
        }
    }

    echo "Verification complete.\n";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
