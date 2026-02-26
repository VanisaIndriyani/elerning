<?php
require_once 'includes/auth_check.php';
require_once '../config/database.php';

$message = '';
$error = '';

$selected_class = $_GET['class'] ?? '';
$selected_date = $_GET['date'] ?? date('Y-m-d');

// Get unique classes
$stmt = $pdo->query("SELECT DISTINCT class FROM students ORDER BY class");
$classes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance_data = $_POST['attendance'] ?? [];
    $date = $_POST['date'] ?? date('Y-m-d');
    
    if (!empty($attendance_data)) {
        try {
            $pdo->beginTransaction();
            
            // Prepare statement for upsert (Insert or Update)
            // MySQL specific: ON DUPLICATE KEY UPDATE
            $stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE status = VALUES(status)");
            
            foreach ($attendance_data as $student_id => $status) {
                $stmt->execute([$student_id, $date, $status]);
            }
            
            $pdo->commit();
            $message = "Absensi berhasil disimpan!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Gagal menyimpan absensi: " . $e->getMessage();
        }
    }
}

// Fetch Students and Attendance if class is selected
$students = [];
if ($selected_class) {
    // Left join to get attendance status if exists
    $sql = "SELECT s.id, s.name, a.status 
            FROM students s 
            LEFT JOIN attendance a ON s.id = a.student_id AND a.date = ? 
            WHERE s.class = ? 
            ORDER BY s.name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$selected_date, $selected_class]);
    $students = $stmt->fetchAll();
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="space-y-6">
    <h1 class="text-2xl font-bold text-slate-800">Absensi Siswa</h1>

    <?php if ($message): ?>
        <script>Swal.fire('Sukses', '<?= $message ?>', 'success');</script>
    <?php endif; ?>
    <?php if ($error): ?>
        <script>Swal.fire('Error', '<?= $error ?>', 'error');</script>
    <?php endif; ?>

    <form method="GET" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-wrap gap-4 items-center">
        <select name="class" onchange="this.form.submit()" class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Kelas --</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?= htmlspecialchars($c) ?>" <?= $selected_class === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="date" value="<?= $selected_date ?>" onchange="this.form.submit()" class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
    </form>
    
    <?php if (!$selected_class): ?>
        <div class="bg-white p-12 rounded-2xl shadow-sm border border-slate-100 text-center space-y-4">
            <i class="fas fa-chair text-6xl text-slate-200"></i>
            <h3 class="text-xl font-bold text-slate-700">Silakan Pilih Kelas</h3>
            <p class="text-slate-500">Daftar nama siswa akan muncul di sini setelah Anda memilih kelas.</p>
        </div>
    <?php else: ?>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="date" value="<?= $selected_date ?>">
            <div class="flex gap-2">
                <button type="button" onclick="bulkCheck('hadir')" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition-colors">Semua Hadir</button>
                <button type="button" onclick="bulkCheck('alpa')" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-100 transition-colors">Semua Alpa</button>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 font-bold text-slate-700">No.</th>
                                <th class="px-6 py-4 font-bold text-slate-700">Nama Siswa</th>
                                <th class="px-6 py-4 font-bold text-center text-emerald-600">Hadir</th>
                                <th class="px-6 py-4 font-bold text-center text-red-600">Alpa</th>
                                <th class="px-6 py-4 font-bold text-center text-amber-600">Sakit</th>
                                <th class="px-6 py-4 font-bold text-center text-purple-600">Izin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php if (empty($students)): ?>
                                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada siswa di kelas ini.</td></tr>
                            <?php else: ?>
                                <?php foreach ($students as $i => $s): ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-500"><?= $i+1 ?></td>
                                    <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($s['name']) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendance[<?= $s['id'] ?>]" value="hadir" class="w-5 h-5 accent-blue-600" <?= $s['status'] === 'hadir' ? 'checked' : '' ?>>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendance[<?= $s['id'] ?>]" value="alpa" class="w-5 h-5 accent-blue-600" <?= $s['status'] === 'alpa' ? 'checked' : '' ?>>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendance[<?= $s['id'] ?>]" value="sakit" class="w-5 h-5 accent-blue-600" <?= $s['status'] === 'sakit' ? 'checked' : '' ?>>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendance[<?= $s['id'] ?>]" value="izin" class="w-5 h-5 accent-blue-600" <?= $s['status'] === 'izin' ? 'checked' : '' ?>>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    function bulkCheck(status) {
        document.querySelectorAll(`input[value="${status}"]`).forEach(r => r.checked = true);
    }
</script>

<?php include 'includes/footer.php'; ?>
