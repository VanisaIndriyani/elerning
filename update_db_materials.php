<?php
require_once 'config/database.php';

try {
    // Cek apakah kolom file_path sudah ada
    $check = $pdo->query("SHOW COLUMNS FROM materials LIKE 'file_path'");
    if ($check->rowCount() == 0) {
        $pdo->exec("ALTER TABLE materials ADD COLUMN file_path VARCHAR(255) NULL AFTER content");
        echo "Kolom file_path berhasil ditambahkan.";
    } else {
        echo "Kolom file_path sudah ada.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>