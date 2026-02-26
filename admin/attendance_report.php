<?php
require_once 'includes/auth_check.php';
require_once '../config/database.php';

// Default Filter
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');
$class = $_GET['class'] ?? '';

// Get unique classes
$stmt = $pdo->query("SELECT DISTINCT class FROM students ORDER BY class");
$classes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Build Query
$students = [];
if ($class) {
    $sql = "SELECT s.name, s.class,
            COUNT(CASE WHEN a.status = 'hadir' THEN 1 END) as hadir,
            COUNT(CASE WHEN a.status = 'sakit' THEN 1 END) as sakit,
            COUNT(CASE WHEN a.status = 'izin' THEN 1 END) as izin,
            COUNT(CASE WHEN a.status = 'alpa' THEN 1 END) as alpa
            FROM students s
            LEFT JOIN attendance a ON s.id = a.student_id 
                AND MONTH(a.date) = ? AND YEAR(a.date) = ?
            WHERE s.class = ?
            GROUP BY s.id
            ORDER BY s.name";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$month, $year, $class]);
    $students = $stmt->fetchAll();
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="space-y-6">
    <h1 class="text-2xl font-bold text-slate-800">Laporan Rekap Absensi</h1>

    <!-- Filter Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                <select name="class" class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 w-40" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>" <?= $class === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                <select name="month" class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 w-40">
                    <?php
                    $months = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    foreach ($months as $k => $v) {
                        echo "<option value='$k' " . ($month == $k ? 'selected' : '') . ">$v</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                <select name="year" class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 w-32">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 2; $y--) {
                        echo "<option value='$y' " . ($year == $y ? 'selected' : '') . ">$y</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-filter mr-2"></i> Tampilkan
            </button>
        </form>
    </div>

    <?php if ($class): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-800">Kelas: <?= htmlspecialchars($class) ?></h3>
                    <p class="text-sm text-slate-500">Periode: <?= $months[$month] ?> <?= $year ?></p>
                </div>
                <button onclick="exportPDF()" class="px-4 py-2 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-all shadow-lg shadow-slate-200">
                    <i class="fas fa-file-pdf mr-2"></i> Download PDF
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table id="report-table" class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-bold text-slate-700 w-16">No.</th>
                            <th class="px-6 py-4 font-bold text-slate-700">Nama Siswa</th>
                            <th class="px-6 py-4 font-bold text-center text-emerald-600">Hadir</th>
                            <th class="px-6 py-4 font-bold text-center text-amber-600">Sakit</th>
                            <th class="px-6 py-4 font-bold text-center text-purple-600">Izin</th>
                            <th class="px-6 py-4 font-bold text-center text-red-600">Alpa</th>
                            <th class="px-6 py-4 font-bold text-center text-slate-700">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($students)): ?>
                            <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Tidak ada data siswa.</td></tr>
                        <?php else: ?>
                            <?php foreach ($students as $i => $s): 
                                $total_days = $s['hadir'] + $s['sakit'] + $s['izin'] + $s['alpa'];
                                $percentage = $total_days > 0 ? round(($s['hadir'] / $total_days) * 100) : 0;
                            ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-500"><?= $i + 1 ?></td>
                                <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($s['name']) ?></td>
                                <td class="px-6 py-4 text-center font-bold text-emerald-600 bg-emerald-50/50"><?= $s['hadir'] ?></td>
                                <td class="px-6 py-4 text-center font-bold text-amber-600 bg-amber-50/50"><?= $s['sakit'] ?></td>
                                <td class="px-6 py-4 text-center font-bold text-purple-600 bg-purple-50/50"><?= $s['izin'] ?></td>
                                <td class="px-6 py-4 text-center font-bold text-red-600 bg-red-50/50"><?= $s['alpa'] ?></td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">
                                    <span class="px-2 py-1 rounded <?= $percentage >= 80 ? 'bg-emerald-100 text-emerald-700' : ($percentage >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') ?>">
                                        <?= $percentage ?>%
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif (isset($_GET['class'])): ?>
        <div class="bg-white p-12 rounded-2xl shadow-sm border border-slate-100 text-center space-y-4">
            <i class="fas fa-search text-6xl text-slate-200"></i>
            <h3 class="text-xl font-bold text-slate-700">Data Tidak Ditemukan</h3>
            <p class="text-slate-500">Silakan pilih filter kelas dengan benar.</p>
        </div>
    <?php else: ?>
        <div class="bg-white p-12 rounded-2xl shadow-sm border border-slate-100 text-center space-y-4">
            <i class="fas fa-filter text-6xl text-slate-200"></i>
            <h3 class="text-xl font-bold text-slate-700">Silakan Pilih Filter</h3>
            <p class="text-slate-500">Pilih kelas, bulan, dan tahun untuk melihat laporan.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    function exportPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        doc.setFontSize(16);
        doc.text("Laporan Absensi Kelas <?= htmlspecialchars($class) ?>", 14, 20);
        doc.setFontSize(11);
        doc.text("Periode: <?= $months[$month] ?? '' ?> <?= $year ?>", 14, 30);
        
        doc.autoTable({ 
            html: '#report-table',
            startY: 40,
            theme: 'striped',
            headStyles: { 
                fillColor: [37, 99, 235], // Blue 600
                textColor: [255, 255, 255],
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [248, 250, 252] // Slate 50
            },
            styles: { 
                fontSize: 10, 
                cellPadding: 6,
                valign: 'middle'
            },
            columnStyles: {
                0: { halign: 'center' }, // No
                2: { halign: 'center', textColor: [5, 150, 105], fontStyle: 'bold' }, // Hadir (Emerald)
                3: { halign: 'center', textColor: [217, 119, 6], fontStyle: 'bold' }, // Sakit (Amber)
                4: { halign: 'center', textColor: [147, 51, 234], fontStyle: 'bold' }, // Izin (Purple)
                5: { halign: 'center', textColor: [220, 38, 38], fontStyle: 'bold' }, // Alpa (Red)
                6: { halign: 'center', fontStyle: 'bold' } // %
            }
        });
        
        doc.save('Laporan_Absensi_<?= htmlspecialchars($class) ?>_<?= $month ?>-<?= $year ?>.pdf');
    }
</script>

<?php include 'includes/footer.php'; ?>
