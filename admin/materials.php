<?php
require_once 'includes/auth_check.php';
require_once '../config/database.php';

$message = '';
$error = '';

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = $_POST['title'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $description = $_POST['description'] ?? '';
            $content = $_POST['content'] ?? '';
            
            if ($title && $subject) {
                $stmt = $pdo->prepare("INSERT INTO materials (title, subject, description, content) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $subject, $description, $content]);
                $message = "Materi berhasil ditambahkan!";
            } else {
                $error = "Judul dan Mata Pelajaran wajib diisi.";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $description = $_POST['description'] ?? '';
            $content = $_POST['content'] ?? '';
            
            if ($id && $title && $subject) {
                $stmt = $pdo->prepare("UPDATE materials SET title = ?, subject = ?, description = ?, content = ? WHERE id = ?");
                $stmt->execute([$title, $subject, $description, $content, $id]);
                $message = "Materi berhasil diperbarui!";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? '';
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM materials WHERE id = ?");
                $stmt->execute([$id]);
                $message = "Materi berhasil dihapus!";
            }
        }
    }
}

// Fetch Materials with Filter
$filter_subject = $_GET['subject'] ?? '';
$sql = "SELECT * FROM materials";
$params = [];

if ($filter_subject) {
    $sql .= " WHERE subject = ?";
    $params[] = $filter_subject;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$materials = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Materi Ajar</h1>
            <p class="text-slate-500 text-sm">Kelola bahan ajar untuk siswa.</p>
        </div>
        <button onclick="openModal('add')" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Materi
        </button>
    </div>

    <!-- Subject Navigation Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="materials.php" class="bg-white p-4 rounded-2xl shadow-sm border <?= !$filter_subject ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= !$filter_subject ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-layer-group text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Semua</h3>
                <p class="text-xs text-slate-500">Tampilkan semua</p>
            </div>
        </a>
        
        <a href="materials.php?subject=Informatika" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_subject == 'Informatika' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= $filter_subject == 'Informatika' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-laptop-code text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Informatika</h3>
                <p class="text-xs text-slate-500">Komputer & Jaringan</p>
            </div>
        </a>

        <a href="materials.php?subject=KKA" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_subject == 'KKA' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= $filter_subject == 'KKA' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-tools text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">KKA</h3>
                <p class="text-xs text-slate-500">Keterampilan Kejuruan</p>
            </div>
        </a>

        <a href="materials.php?subject=Dasar Kejuruan 2" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_subject == 'Dasar Kejuruan 2' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= $filter_subject == 'Dasar Kejuruan 2' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-globe text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Dasar Kejuruan 2</h3>
                <p class="text-xs text-slate-500">Materi Dasar</p>
            </div>
        </a>
    </div>

    <?php if ($message): ?>
        <script>Swal.fire('Sukses', '<?= $message ?>', 'success');</script>
    <?php endif; ?>
    <?php if ($error): ?>
        <script>Swal.fire('Error', '<?= $error ?>', 'error');</script>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($materials as $m): ?>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="flex justify-between items-start mb-4">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wider"><?= htmlspecialchars($m['subject']) ?></span>
                <div class="flex gap-2">
                    <button onclick='openModal("edit", <?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)' class="text-slate-400 hover:text-blue-500"><i class="fas fa-edit"></i></button>
                    <button onclick="confirmDelete(<?= $m['id'] ?>)" class="text-slate-400 hover:text-red-500"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2"><?= htmlspecialchars($m['title']) ?></h3>
            <p class="text-slate-500 text-sm mb-4 line-clamp-3"><?= htmlspecialchars($m['description']) ?></p>
            <div class="mt-auto pt-4 border-t border-slate-50">
                <a href="#" onclick="alert('Preview content: <?= htmlspecialchars($m['content']) ?>')" class="text-blue-600 font-semibold text-sm hover:underline">Lihat Konten <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($materials)): ?>
        <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-100 border-dashed">
            <i class="fas fa-folder-open text-4xl text-slate-300 mb-4"></i>
            <p class="text-slate-500">Belum ada materi ajar.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="materialModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-slate-800">Tambah Materi</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="materialId">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                <select name="subject" id="materialSubject" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="Informatika">Informatika</option>
                    <option value="KKA">KKA</option>
                    <option value="Dasar Kejuruan 2">Dasar Kejuruan 2</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Judul Materi</label>
                <input type="text" name="title" id="materialTitle" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" id="materialDescription" rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Konten / Link</label>
                <textarea name="content" id="materialContent" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan teks materi atau link..."></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 rounded-xl font-semibold hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">Simpan</button>
            </div>
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" class="hidden">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    function openModal(mode, data = null) {
        document.getElementById('materialModal').classList.remove('hidden');
        document.getElementById('materialModal').classList.add('flex');
        
        if (mode === 'edit' && data) {
            document.getElementById('modalTitle').textContent = 'Edit Materi';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('materialId').value = data.id;
            document.getElementById('materialSubject').value = data.subject;
            document.getElementById('materialTitle').value = data.title;
            document.getElementById('materialDescription').value = data.description;
            document.getElementById('materialContent').value = data.content;
        } else {
            document.getElementById('modalTitle').textContent = 'Tambah Materi';
            document.getElementById('formAction').value = 'add';
            document.getElementById('materialId').value = '';
            document.getElementById('materialSubject').value = 'Informatika';
            document.getElementById('materialTitle').value = '';
            document.getElementById('materialDescription').value = '';
            document.getElementById('materialContent').value = '';
        }
    }

    function closeModal() {
        document.getElementById('materialModal').classList.add('hidden');
        document.getElementById('materialModal').classList.remove('flex');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Materi?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>
