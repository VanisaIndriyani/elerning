<?php
require_once 'config/database.php';

try {
    $class = 'X TJKT 2';
    $students = [
        "ADYTIA MARVELINO RENDY LETMAI",
        "AIRYN VENI SOI",
        "ALBINUS GREGORIUS FORLAN PORWATA",
        "ALVIANO GIOVANNI FRANS",
        "ARTHA MYLANDRY P'TROWL SUPENO",
        "AUREL JEWELIS SINLAELOE",
        "CHAIRIL ARIFIN MAY LOIS",
        "CHERYL CASYAFANY ERY KOBIS",
        "DANIEL TULU GA",
        "DAWYA GRACE RIANTI RAME HUKI",
        "DON RAFA AYDIN YUSUF KHALFANI DVG",
        "ENJELLIA FERONIKA PUTRI BANGNGU",
        "EVAN LIONEL MARCO",
        "FERNANDO RAFFAEL CHRISTIAN AMABI",
        "GEORGE MARCHELO FALLO",
        "HIERONIMUS KEVIN SNEIJDER BAUN",
        "JANUAR IMANUEL KADJA",
        "JERLEN SAMUEL ROMEN NALLE",
        "JESIKA APRILIANI BRIGITA MONE",
        "JORDY HERMAN SINE",
        "KATARINA TALAN",
        "KOKO THIO MARULI",
        "LORENSIUS MARVELL NARA WATU",
        "MARDAN KI'IK SAFRIN",
        "MARIA AGUSTINA LIDIAWATI AMBROS",
        "MARVELLA NDUN",
        "MATHEOS SOLEMAN MOTONG OPENG",
        "MUHAMMAD ALAMSYAH AMIN",
        "NURALDA RIHLA RAHIM",
        "RADIN MARTEN WEO",
        "RISKY PRAWIRYOKUSWANTO MALLE",
        "SALFREDO BUNGSU WADU NELI",
        "SHERLITA MARGARET DETHAN",
        "THESALONIKA YESRIYANTI PUTRI LESIANGI",
        "TRISTAN MILANO LADO",
        "WANDRI YACOB ROHI"
    ];

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