<?php
require_once 'includes/auth_check.php';
require_once '../config/database.php';

// Stats Query
$stmt = $pdo->query("SELECT COUNT(*) FROM students");
$total_students = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM materials");
$total_materials = $stmt->fetchColumn();

// Today's attendance
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM attendance WHERE date = ? GROUP BY status");
$stmt->execute([$today]);
$attendance_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$hadir = $attendance_stats['hadir'] ?? 0;
$alpa = $attendance_stats['alpa'] ?? 0;
$sakit = $attendance_stats['sakit'] ?? 0;
$izin = $attendance_stats['izin'] ?? 0;
$total_absensi = $hadir + $alpa + $sakit + $izin;

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="space-y-8 max-w-7xl mx-auto">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-emerald-400 rounded-3xl p-10 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <h1 class="text-3xl font-bold mb-3">Selamat Datang, <?= htmlspecialchars($_SESSION['name']) ?>! 👋</h1>
            <p class="text-blue-50 text-lg opacity-90">Siap untuk mengajar hari ini? Berikut ringkasan aktivitas terkini.</p>
        </div>
        <!-- Decorative Shapes -->
        <div class="absolute right-0 top-0 h-full w-1/3 bg-white/10 skew-x-12 translate-x-10"></div>
        <div class="absolute right-10 bottom-10 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Siswa -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wide">Total Siswa</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $total_students ?></h3>
            </div>
        </div>

        <!-- Materi Ajar -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
            <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wide">Materi Ajar</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $total_materials ?></h3>
            </div>
        </div>

        <!-- Hadir Hari Ini -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wide">Hadir Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $hadir ?></h3>
            </div>
        </div>

        <!-- Alpa Hari Ini -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-all">
            <div class="w-14 h-14 bg-red-50 text-red-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wide">Alpa Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $alpa ?></h3>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Statistics Detail -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 h-full">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-pie text-sm"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Statistik Kehadiran Hari Ini</h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-emerald-50 p-6 rounded-2xl text-center border border-emerald-100">
                        <p class="text-emerald-600 font-bold mb-2">Hadir</p>
                        <h4 class="text-3xl font-bold text-emerald-700"><?= $hadir ?></h4>
                    </div>
                    <div class="bg-red-50 p-6 rounded-2xl text-center border border-red-100">
                        <p class="text-red-600 font-bold mb-2">Alpa</p>
                        <h4 class="text-3xl font-bold text-red-700"><?= $alpa ?></h4>
                    </div>
                    <div class="bg-amber-50 p-6 rounded-2xl text-center border border-amber-100">
                        <p class="text-amber-600 font-bold mb-2">Sakit</p>
                        <h4 class="text-3xl font-bold text-amber-700"><?= $sakit ?></h4>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-2xl text-center border border-purple-100">
                        <p class="text-purple-600 font-bold mb-2">Izin</p>
                        <h4 class="text-3xl font-bold text-purple-700"><?= $izin ?></h4>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-2 text-slate-400 text-sm bg-slate-50 p-3 rounded-xl">
                    <i class="fas fa-info-circle"></i>
                    <p>Data di atas diperbarui secara real-time berdasarkan input absensi harian.</p>
                </div>
            </div>
        </div>

        <!-- Right: Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 h-full">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-bolt text-amber-500 text-xl"></i>
                    <h3 class="text-lg font-bold text-slate-800">Aksi Cepat</h3>
                </div>

                <div class="space-y-4">
                    <a href="attendance.php" class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors group border border-transparent hover:border-slate-100">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Isi Absensi</h4>
                            <p class="text-sm text-slate-500">Catat kehadiran siswa hari ini</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-300 mt-2"></i>
                    </a>

                    <a href="materials.php" class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors group border border-transparent hover:border-slate-100">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-purple-600 transition-colors">Upload Materi</h4>
                            <p class="text-sm text-slate-500">Bagikan bahan ajar baru</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-300 mt-2"></i>
                    </a>

                    <a href="attendance_report.php" class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors group border border-transparent hover:border-slate-100">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-print"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Cetak Laporan</h4>
                            <p class="text-sm text-slate-500">Rekapan bulanan absensi</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-300 mt-2"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
