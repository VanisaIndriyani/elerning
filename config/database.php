<?php
// config/database.php

// Deteksi Otomatis Lingkungan (Local vs Hosting)
$is_cli = php_sapi_name() === 'cli';
$remote_addr = $_SERVER['REMOTE_ADDR'] ?? '';
$server_name = $_SERVER['SERVER_NAME'] ?? '';

$is_local = $is_cli || in_array($remote_addr, ['127.0.0.1', '::1']) || $server_name == 'localhost';

if ($is_local) {
    // Pengaturan Local (Laragon/XAMPP)
    $host = 'localhost';
    $db   = 'school_db';
    $user = 'root';
    $pass = '';
    $port = '3306';
} else {
    // Pengaturan Hosting
    $host = 'localhost';
    $db   = 'bitubimy_ai';
    $user = 'bitubimy_izsaa';
    $pass = 'jokiizsaa200504';
    $port = '3306';
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika di local dan db belum ada, coba buat otomatis
    if ($is_local) {
        try {
            $dsn_no_db = "mysql:host=$host;port=$port;charset=$charset";
            $pdo_init = new PDO($dsn_no_db, $user, $pass, $options);
            $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$db`");
            $pdo_init->exec("USE `$db`");
            $pdo = $pdo_init;
        } catch (\PDOException $e2) {
            die("Database connection failed: " . $e2->getMessage());
        }
    } else {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>