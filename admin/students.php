<?php
require_once 'includes/auth_check.php';
require_once '../config/database.php';

$message = '';
$error = '';

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = $_POST['name'] ?? '';
            $class = $_POST['class'] ?? '';
            if ($name && $class) {
                $stmt = $pdo->prepare("INSERT INTO students (name, class) VALUES (?, ?)");
                $stmt->execute([$name, $class]);
                $message = "Siswa berhasil ditambahkan!";
            } else {
                $error = "Nama dan Kelas wajib diisi.";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $class = $_POST['class'] ?? '';
            if ($id && $name && $class) {
                $stmt = $pdo->prepare("UPDATE students SET name = ?, class = ? WHERE id = ?");
                $stmt->execute([$name, $class, $id]);
                $message = "Data siswa berhasil diperbarui!";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? '';
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
                $stmt->execute([$id]);
                $message = "Siswa berhasil dihapus!";
            }
        }
    }
}

// Search Logic
$search = $_GET['search'] ?? '';
$filter_class = $_GET['filter_class'] ?? '';

$sql = "SELECT * FROM students WHERE 1=1";
$params = [];

if ($filter_class) {
    $sql .= " AND class = ?";
    $params[] = $filter_class;
}

if ($search) {
    $sql .= " AND (name LIKE ? OR class LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY class, name";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Siswa</h1>
            <p class="text-slate-500 text-sm">Kelola data siswa dengan mudah.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search Form -->
            <form method="GET" class="relative">
                <?php if($filter_class): ?><input type="hidden" name="filter_class" value="<?= htmlspecialchars($filter_class) ?>"><?php endif; ?>
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari siswa..." class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64 transition-all">
            </form>
            <button onclick="openModal('add')" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> <span>Tambah Siswa</span>
            </button>
        </div>
    </div>

    <!-- Class Cards Navigation -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="students.php" class="bg-white p-4 rounded-2xl shadow-sm border <?= !$filter_class ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= !$filter_class ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Semua Kelas</h3>
                <p class="text-xs text-slate-500">Tampilkan semua</p>
            </div>
        </a>
        
        <a href="students.php?filter_class=X TJKT 1" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_class == 'X TJKT 1' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= $filter_class == 'X TJKT 1' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-laptop-code text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">X TJKT 1</h3>
                <p class="text-xs text-slate-500">Teknik Jaringan</p>
            </div>
        </a>

        <a href="students.php?filter_class=X TJKT 2" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_class == 'X TJKT 2' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= $filter_class == 'X TJKT 2' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-server text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">X TJKT 2</h3>
                <p class="text-xs text-slate-500">Teknik Jaringan</p>
            </div>
        </a>
    </div>

    <?php if ($message): ?>
        <script>Swal.fire('Sukses', '<?= $message ?>', 'success');</script>
    <?php endif; ?>
    <?php if ($error): ?>
        <script>Swal.fire('Error', '<?= $error ?>', 'error');</script>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-700 w-16">No.</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Nama Siswa</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Kelas</th>
                        <th class="px-6 py-4 font-bold text-center text-slate-700 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-search text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium">Tidak ada data siswa ditemukan.</p>
                                    <?php if($search): ?>
                                        <p class="text-sm mt-1">Coba kata kunci lain.</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $i => $s): ?>
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="px-6 py-4 text-slate-500"><?= $i + 1 ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                        <?= strtoupper(substr($s['name'], 0, 1)) ?>
                                    </div>
                                    <span class="font-medium text-slate-800"><?= htmlspecialchars($s['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wide border border-slate-200">
                                    <?= htmlspecialchars($s['class']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($s), ENT_QUOTES) ?>)" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmDelete(<?= $s['id'] ?>)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination / Footer Table (Optional, for now just showing count) -->
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50 text-sm text-slate-500 flex justify-between items-center">
            <span>Menampilkan <strong><?= count($students) ?></strong> siswa</span>
            <?php if($search): ?>
                <a href="students.php" class="text-blue-600 hover:underline">Reset Pencarian</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="studentModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-all opacity-0 scale-95" style="display: none;">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md relative transform transition-all scale-100">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-slate-800">Tambah Siswa</h2>
        
        <form method="POST" class="space-y-5">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="studentId">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="name" id="studentName" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Masukkan nama siswa" required>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kelas</label>
                <div class="relative">
                    <i class="fas fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select name="class" id="studentClass" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none bg-white" required>
                        <option value="X TJKT 1">X TJKT 1</option>
                        <option value="X TJKT 2">X TJKT 2</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" class="hidden">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    const modal = document.getElementById('studentModal');

    function openModal(mode, data = null) {
        modal.style.display = 'flex';
        // Small delay to allow display:flex to apply before adding opacity class
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'scale-95');
            modal.classList.add('opacity-100', 'scale-100');
        }, 10);
        
        if (mode === 'edit' && data) {
            document.getElementById('modalTitle').textContent = 'Edit Siswa';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('studentId').value = data.id;
            document.getElementById('studentName').value = data.name;
            document.getElementById('studentClass').value = data.class;
        } else {
            document.getElementById('modalTitle').textContent = 'Tambah Siswa';
            document.getElementById('formAction').value = 'add';
            document.getElementById('studentId').value = '';
            document.getElementById('studentName').value = '';
            document.getElementById('studentClass').value = 'X TJKT 1'; // Default
        }
    }

    function closeModal() {
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Siswa?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    // Close modal on click outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
</script>

<?php include 'includes/footer.php'; ?>