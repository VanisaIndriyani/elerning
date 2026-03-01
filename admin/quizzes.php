<?php
require_once 'includes/auth_check.php';
require_once '../config/database.php';

$message = '';
$error = '';

function uploadQuizFile($file) {
    $target_dir = "../uploads/quizzes/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Allow certain file formats
    $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'zip', 'rar', 'txt'];
    if (!in_array($file_extension, $allowed)) {
        return ['error' => 'Format file tidak diizinkan.'];
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['path' => $new_filename];
    } else {
        return ['error' => 'Gagal mengupload file.'];
    }
}

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = $_POST['title'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $description = $_POST['description'] ?? '';
            $link = $_POST['link'] ?? '';
            $file_path = null;
            
            if ($title && $subject) {
                if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                    $upload = uploadQuizFile($_FILES['file']);
                    if (isset($upload['error'])) {
                        $error = $upload['error'];
                    } else {
                        $file_path = $upload['path'];
                    }
                }

                if (!$error) {
                    $stmt = $pdo->prepare("INSERT INTO quizzes (title, subject, description, link, file_path) VALUES (?, ?, ?, ?, ?)");
                    if ($stmt->execute([$title, $subject, $description, $link, $file_path])) {
                        $message = "Kuis berhasil ditambahkan!";
                    } else {
                        $error = "Gagal menambahkan kuis.";
                    }
                }
            } else {
                $error = "Judul dan Mata Pelajaran wajib diisi.";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $description = $_POST['description'] ?? '';
            $link = $_POST['link'] ?? '';
            
            if ($id && $title && $subject) {
                $file_path = null;
                // Cek apakah ada file baru
                if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                    $upload = uploadQuizFile($_FILES['file']);
                    if (isset($upload['error'])) {
                        $error = $upload['error'];
                    } else {
                        $file_path = $upload['path'];
                        
                        // Hapus file lama
                        $stmt = $pdo->prepare("SELECT file_path FROM quizzes WHERE id = ?");
                        $stmt->execute([$id]);
                        $old_file = $stmt->fetchColumn();
                        if ($old_file && file_exists("../uploads/quizzes/" . $old_file)) {
                            unlink("../uploads/quizzes/" . $old_file);
                        }
                    }
                }

                if (!$error) {
                    if ($file_path) {
                        $stmt = $pdo->prepare("UPDATE quizzes SET title = ?, subject = ?, description = ?, link = ?, file_path = ? WHERE id = ?");
                        $stmt->execute([$title, $subject, $description, $link, $file_path, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE quizzes SET title = ?, subject = ?, description = ?, link = ? WHERE id = ?");
                        $stmt->execute([$title, $subject, $description, $link, $id]);
                    }
                    $message = "Kuis berhasil diperbarui!";
                }
            } else {
                $error = "Data tidak lengkap.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? '';
            if ($id) {
                // Hapus file fisik
                $stmt = $pdo->prepare("SELECT file_path FROM quizzes WHERE id = ?");
                $stmt->execute([$id]);
                $file = $stmt->fetchColumn();
                if ($file && file_exists("../uploads/quizzes/" . $file)) {
                    unlink("../uploads/quizzes/" . $file);
                }

                $stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $message = "Kuis berhasil dihapus!";
                } else {
                    $error = "Gagal menghapus kuis.";
                }
            }
        }
    }
}

// Fetch Quizzes with Filter
$filter_subject = $_GET['subject'] ?? '';
$sql = "SELECT * FROM quizzes";
$params = [];

if ($filter_subject) {
    $sql .= " WHERE subject = ?";
    $params[] = $filter_subject;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$quizzes = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Kuis</h1>
            <p class="text-slate-500 text-sm">Kelola kuis dan latihan soal untuk siswa.</p>
        </div>
        <button onclick="openModal('add')" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Kuis
        </button>
    </div>

    <!-- Subject Navigation Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="quizzes.php" class="bg-white p-4 rounded-2xl shadow-sm border <?= !$filter_subject ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= !$filter_subject ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-layer-group text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Semua</h3>
                <p class="text-xs text-slate-500">Tampilkan semua</p>
            </div>
        </a>
        
        <a href="quizzes.php?subject=Informatika" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_subject == 'Informatika' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= $filter_subject == 'Informatika' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-laptop-code text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Informatika</h3>
                <p class="text-xs text-slate-500">Komputer & Jaringan</p>
            </div>
        </a>

        <a href="quizzes.php?subject=KKA" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_subject == 'KKA' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 <?= $filter_subject == 'KKA' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-500' ?> rounded-xl flex items-center justify-center transition-colors">
                <i class="fas fa-tools text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">KKA</h3>
                <p class="text-xs text-slate-500">Keterampilan Kejuruan</p>
            </div>
        </a>

        <a href="quizzes.php?subject=Dasar Kejuruan 2" class="bg-white p-4 rounded-2xl shadow-sm border <?= $filter_subject == 'Dasar Kejuruan 2' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300' ?> transition-all flex items-center gap-4 group">
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
        <?php foreach ($quizzes as $q): ?>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="flex justify-between items-start mb-4">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wider"><?= htmlspecialchars($q['subject']) ?></span>
                <div class="flex gap-2">
                    <button onclick='openModal("edit", <?= htmlspecialchars(json_encode($q), ENT_QUOTES) ?>)' class="text-slate-400 hover:text-blue-500"><i class="fas fa-edit"></i></button>
                    <button onclick="confirmDelete(<?= $q['id'] ?>)" class="text-slate-400 hover:text-red-500"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2"><?= htmlspecialchars($q['title']) ?></h3>
            <p class="text-slate-500 text-sm mb-4 line-clamp-3"><?= htmlspecialchars($q['description']) ?></p>
            
            <div class="mt-auto pt-4 border-t border-slate-50 flex flex-col gap-2">
                <?php if (!empty($q['link'])): ?>
                <a href="<?= htmlspecialchars($q['link']) ?>" target="_blank" class="w-full text-blue-600 font-semibold text-sm hover:bg-blue-50 py-2 px-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-external-link-alt w-5 text-center"></i> Buka Link Kuis
                </a>
                <?php endif; ?>
                
                <?php if (!empty($q['file_path'])): ?>
                <a href="../uploads/quizzes/<?= htmlspecialchars($q['file_path']) ?>" target="_blank" class="w-full text-emerald-600 font-semibold text-sm hover:bg-emerald-50 py-2 px-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-download w-5 text-center"></i> Download Soal
                </a>
                <?php endif; ?>
                
                <span class="text-xs text-slate-400 mt-2 block text-right"><?= date('d M Y', strtotime($q['created_at'])) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($quizzes)): ?>
        <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-100 border-dashed">
            <i class="fas fa-clipboard-question text-4xl text-slate-300 mb-4"></i>
            <p class="text-slate-500">Belum ada kuis yang ditambahkan.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="quizModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-slate-800">Tambah Kuis</h2>
        <form method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="quizId">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                <select name="subject" id="quizSubject" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="Informatika">Informatika</option>
                    <option value="KKA">KKA</option>
                    <option value="Dasar Kejuruan 2">Dasar Kejuruan 2</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Judul Kuis</label>
                <input type="text" name="title" id="quizTitle" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Ulangan Harian 1">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Link Kuis (Opsional jika upload file)</label>
                <input type="url" name="link" id="quizLink" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://forms.google.com/...">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Upload File Soal (Opsional)</label>
                <div class="relative border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 hover:bg-white transition-colors">
                    <input type="file" name="file" id="quizFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="flex items-center gap-3 text-slate-500">
                        <i class="fas fa-cloud-upload-alt text-xl"></i>
                        <span id="fileName" class="text-sm truncate">Pilih file untuk diupload...</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-1" id="currentFileText"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Singkat (Opsional)</label>
                <textarea name="description" id="quizDescription" rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" placeholder="Instruksi pengerjaan..."></textarea>
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
    // File Input Name Display
    document.getElementById('quizFile').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            document.getElementById('fileName').textContent = e.target.files[0].name;
            document.getElementById('fileName').classList.add('text-slate-800');
        } else {
            document.getElementById('fileName').textContent = 'Pilih file untuk diupload...';
            document.getElementById('fileName').classList.remove('text-slate-800');
        }
    });

    function openModal(mode, data = null) {
        document.getElementById('quizModal').classList.remove('hidden');
        document.getElementById('quizModal').classList.add('flex');
        
        // Reset File Input
        document.getElementById('quizFile').value = '';
        document.getElementById('fileName').textContent = 'Pilih file untuk diupload...';
        document.getElementById('currentFileText').textContent = '';
        
        if (mode === 'edit' && data) {
            document.getElementById('modalTitle').textContent = 'Edit Kuis';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('quizId').value = data.id;
            document.getElementById('quizSubject').value = data.subject;
            document.getElementById('quizTitle').value = data.title;
            document.getElementById('quizLink').value = data.link;
            document.getElementById('quizDescription').value = data.description;
            
            if (data.file_path) {
                document.getElementById('currentFileText').innerHTML = 'File saat ini: <span class="font-bold text-slate-700">' + data.file_path + '</span> (Upload baru untuk mengganti)';
            }
        } else {
            document.getElementById('modalTitle').textContent = 'Tambah Kuis';
            document.getElementById('formAction').value = 'add';
            document.getElementById('quizId').value = '';
            document.getElementById('quizSubject').value = 'Informatika';
            document.getElementById('quizTitle').value = '';
            document.getElementById('quizLink').value = '';
            document.getElementById('quizDescription').value = '';
        }
    }

    function closeModal() {
        document.getElementById('quizModal').classList.add('hidden');
        document.getElementById('quizModal').classList.remove('flex');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Kuis?',
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