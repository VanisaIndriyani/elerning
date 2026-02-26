<?php
require_once 'config/database.php';

try {
    $class = 'X TJKT 1';
    $students = [
        "Afemusanis Bin Oni's Tupa",
        "Albi Landjar",
        "ALLDY CRISTOVEL HERE",
        "Ariel Anselmus Therik",
        "Arina Yayu Manes",
        "Bryan Jession Alfiano Toma",
        "CHATERINE MANSUITA WIE LAWA",
        "COSTANDJI JOHANIS LATUMAHINA",
        "Cresentia Regina Petronela Fanggi",
        "DIDIMUS UNTUNG P. ROHI KANA",
        "DIJAN STAR KAPRICON ULY",
        "Emil Evalina Nara",
        "ERWIN TRENDY GABRIEL HANA",
        "FEBRYANO JACOB KOAMESAH",
        "GALANG ZLATAN MAHARDIKA",
        "Hesti Elsadai Malafu",
        "JANSUND LORDLY MIHA DIMU",
        "Jericho Anthonio Missa",
        "Jibrel Ronal Lopu",
        "Jilda Anastasia Ratu",
        "KAJOL CHARITAS CRISTI ANGELIKA TABUN",
        "KEYGEN ARYA DJAMI",
        "LIONEL GABRIEL ELIK",
        "Marco Alexandro Kofi",
        "Margareth Juliana Syalomi Polly",
        "Mariana Sagala",
        "Marvel De Aprilo Tael",
        "Novriyanti Sarlota Molum",
        "Qn Gratian Mauring",
        "REYNER FRANCIS EUGINIO LINGU",
        "RYCKO REAHAN MARKUS GA",
        "Sheren Donamarshinta Lay Doma",
        "Steven William Saduk",
        "Theresia R Wiku Epa",
        "VINZEN ROWLAND YEVERS TIMO",
        "YORLISBET MEDIAN PUTRI PULANGA"
    ];

    // Optional: Bersihkan data lama kelas ini jika perlu (uncomment jika diinginkan)
    // $pdo->prepare("DELETE FROM students WHERE class = ?")->execute([$class]);

    $stmt = $pdo->prepare("INSERT INTO students (name, class) VALUES (?, ?)");

    $count = 0;
    foreach ($students as $name) {
        $stmt->execute([$name, $class]);
        $count++;
    }

    echo "Berhasil menambahkan $count siswa ke kelas $class.";

} catch (PDOException $e) {
    echo "Gagal menambahkan data: " . $e->getMessage();
}
?>