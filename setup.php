<?php
require_once 'config/database.php';

$sql = file_get_contents(__DIR__ . '/database.sql');

try {
    // Split SQL by semicolon to execute statements one by one if needed, 
    // but PDO might handle it if emulation is on. 
    // Safest is to just run it.
    $pdo->exec($sql);
    echo "Database setup successfully!";
} catch (PDOException $e) {
    echo "Setup failed: " . $e->getMessage();
}
?>