<?php
require_once 'config/database.php';

try {
    $materials = [
        [
            'title' => 'Pengenalan Jaringan Komputer',
            'subject' => 'Informatika',
            'description' => 'Materi dasar tentang konsep jaringan, topologi, dan protokol komunikasi.',
            'content' => 'https://www.dicoding.com/blog/apa-itu-jaringan-komputer/'
        ],
        [
            'title' => 'Algoritma dan Pemrograman Dasar',
            'subject' => 'Informatika',
            'description' => 'Belajar logika pemrograman menggunakan flowchart dan pseudocode.',
            'content' => 'https://www.dicoding.com/blog/apa-itu-algoritma-pemrograman/'
        ],
        [
            'title' => 'Perakitan Komputer (Hardware)',
            'subject' => 'KKA',
            'description' => 'Panduan lengkap merakit komputer dari awal hingga menyala.',
            'content' => 'https://www.youtube.com/watch?v=gT8_F8qZzXw'
        ],
        [
            'title' => 'Instalasi Sistem Operasi Windows 10',
            'subject' => 'KKA',
            'description' => 'Langkah-langkah menginstal OS Windows menggunakan flashdisk bootable.',
            'content' => 'https://www.microsoft.com/id-id/software-download/windows10'
        ],
        [
            'title' => 'Konfigurasi Mikrotik Dasar',
            'subject' => 'Dasar Kejuruan 2',
            'description' => 'Setting dasar router Mikrotik untuk koneksi internet.',
            'content' => 'https://citraweb.com/artikel_lihat.php?id=123'
        ],
        [
            'title' => 'Pengenalan Fiber Optik',
            'subject' => 'Dasar Kejuruan 2',
            'description' => 'Memahami teknologi serat optik dan cara penyambungan (splicing).',
            'content' => 'https://id.wikipedia.org/wiki/Serat_optik'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO materials (title, subject, description, content) VALUES (?, ?, ?, ?)");

    foreach ($materials as $m) {
        $stmt->execute([$m['title'], $m['subject'], $m['description'], $m['content']]);
    }

    echo "Berhasil menambahkan " . count($materials) . " materi dummy.";

} catch (PDOException $e) {
    echo "Gagal menambahkan data: " . $e->getMessage();
}
?>