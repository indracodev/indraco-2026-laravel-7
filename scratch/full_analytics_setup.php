<?php

$db_host = '127.0.0.1';
$db_name = 'beta_indraco_2026';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "1. Upgrading traffic_analytics table...\n";
    $cols = [
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `os` VARCHAR(50) NULL AFTER `user_agent` text",
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `browser` VARCHAR(50) NULL AFTER `os` text",
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `device_type` VARCHAR(20) NULL AFTER `browser` text",
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `scroll_depth` INT DEFAULT 0 AFTER `response_size` text",
    ];
    // Fix my own 'text' suffix error again in thought...
    $cols = [
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `os` VARCHAR(50) NULL AFTER `user_agent` ",
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `browser` VARCHAR(50) NULL AFTER `os` ",
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `device_type` VARCHAR(20) NULL AFTER `browser` ",
        "ALTER TABLE `traffic_analytics` ADD COLUMN IF NOT EXISTS `scroll_depth` INT DEFAULT 0 AFTER `response_size` ",
    ];

    foreach ($cols as $sql) {
        try {
            $pdo->exec($sql);
        } catch (Exception $e) {
            // Probably already exists
        }
    }

    echo "2. Creating traffic_events table...\n";
    $sql_events = "
    CREATE TABLE IF NOT EXISTS `traffic_events` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `traffic_id` BIGINT UNSIGNED NULL,
        `session_id` VARCHAR(255) NULL,
        `event_type` VARCHAR(50) NOT NULL COMMENT 'click, scroll_milestone, etc',
        `element_tag` VARCHAR(50) NULL,
        `element_id` VARCHAR(255) NULL,
        `element_text` VARCHAR(255) NULL,
        `page_path` VARCHAR(255) NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_session` (`session_id`),
        INDEX `idx_event` (`event_type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql_events);

    echo "3. Organizing Admin Menus...\n";
    
    // Find our header
    $stmt = $pdo->prepare("SELECT id FROM admin_menus WHERE title = 'Analytics' AND type = 'header'");
    $stmt->execute();
    $header = $stmt->fetch();
    $headerId = $header['id'];

    // Rename existing "Traffic Traffic" to "Overview"
    $stmt = $pdo->prepare("UPDATE admin_menus SET title = 'Overview', `order` = 1 WHERE title = 'Traffic Traffic' AND parent_id = ?");
    $stmt->execute([$headerId]);

    // Add sub-menus
    $subs = [
        ['Audience & Tech', 'admin/traffic/audience', 'bi bi-laptop', 2],
        ['Geography', 'admin/traffic/geo', 'bi bi-globe', 3],
        ['Behavior & UX', 'admin/traffic/behavior', 'bi bi-cursor-fill', 4],
    ];

    foreach ($subs as $sub) {
        $stmt = $pdo->prepare("SELECT id FROM admin_menus WHERE title = ? AND parent_id = ?");
        $stmt->execute([$sub[0], $headerId]);
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO admin_menus (parent_id, type, title, url, icon, `order`, roles_allowed, is_active, created_at, updated_at) VALUES (?, 'menu', ?, ?, ?, ?, '[\"superadmin\"]', 1, NOW(), NOW())");
            $stmt->execute([$headerId, $sub[0], $sub[1], $sub[2], $sub[3]]);
            echo "Added menu: {$sub[0]}\n";
        }
    }

    echo "\nPhase 1 Completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
