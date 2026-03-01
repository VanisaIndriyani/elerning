<?php
require_once 'config/database.php';

// Aktifkan error reporting untuk melihat masalah
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Perbaikan Database Hosting Lengkap</h1>";

try {
    // 1. Tabel Users
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p style='color:green'>✅ Tabel 'users' berhasil dicek/dibuat.</p>";

    // Cek apakah user admin ada
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $pass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, name) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $pass, 'Administrator']);
        echo "<p style='color:green'>✅ User default 'admin' (password: admin123) berhasil dibuat.</p>";
    } else {
        echo "<p style='color:blue'>ℹ️ User admin sudah ada.</p>";
    }

    // 2. Tabel Materials
    $sql = "CREATE TABLE IF NOT EXISTS materials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        subject VARCHAR(100) NOT NULL,
        description TEXT,
        content TEXT,
        file_path VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p style='color:green'>✅ Tabel 'materials' berhasil dicek/dibuat.</p>";

    // Cek kolom file_path di materials
    $stmt = $pdo->query("SHOW COLUMNS FROM materials LIKE 'file_path'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE materials ADD COLUMN file_path VARCHAR(255) NULL AFTER content");
        echo "<p style='color:green'>✅ Kolom 'file_path' ditambahkan ke 'materials'.</p>";
    }

    // 3. Tabel Quizzes
    $sql = "CREATE TABLE IF NOT EXISTS quizzes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        subject VARCHAR(100) NOT NULL,
        link TEXT NULL,
        description TEXT NULL,
        file_path VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p style='color:green'>✅ Tabel 'quizzes' berhasil dicek/dibuat.</p>";

    // Cek kolom file_path di quizzes
    $stmt = $pdo->query("SHOW COLUMNS FROM quizzes LIKE 'file_path'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE quizzes ADD COLUMN file_path VARCHAR(255) NULL AFTER link");
        echo "<p style='color:green'>✅ Kolom 'file_path' ditambahkan ke 'quizzes'.</p>";
    }

    // 4. Tabel Students
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nis VARCHAR(20) NOT NULL UNIQUE,
        name VARCHAR(100) NOT NULL,
        class VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p style='color:green'>✅ Tabel 'students' berhasil dicek/dibuat.</p>";

    // 5. Tabel Attendance
    $sql = "CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        status ENUM('Hadir', 'Izin', 'Sakit', 'Alpha') NOT NULL,
        date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "<p style='color:green'>✅ Tabel 'attendance' berhasil dicek/dibuat.</p>";

    echo "<h3>Selesai! Database Anda sudah diperbarui sepenuhnya.</h3>";
    echo "<p>Silakan coba login kembali di: <a href='admin/login.php'>Halaman Login</a></p>";

} catch (PDOException $e) {
    echo "<h3 style='color:red'>Error Database: " . $e->getMessage() . "</h3>";
} catch (Exception $e) {
    echo "<h3 style='color:red'>Error Umum: " . $e->getMessage() . "</h3>";
}
?>