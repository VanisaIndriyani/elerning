<?php
require_once 'includes/auth_check.php';
require_once '../config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Verify current password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (password_verify($current_password, $user['password'])) {
        try {
            if (!empty($new_password)) {
                if ($new_password === $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, password = ? WHERE id = ?");
                    $stmt->execute([$name, $username, $hashed_password, $_SESSION['user_id']]);
                    $message = "Profil dan password berhasil diperbarui!";
                } else {
                    $error = "Konfirmasi password baru tidak cocok.";
                }
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ? WHERE id = ?");
                $stmt->execute([$name, $username, $_SESSION['user_id']]);
                $message = "Profil berhasil diperbarui!";
            }
            
            // Update session name
            $_SESSION['name'] = $name;
            
        } catch (PDOException $e) {
            $error = "Gagal memperbarui profil: " . $e->getMessage();
        }
    } else {
        $error = "Password saat ini salah.";
    }
}

// Get User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="space-y-8 max-w-4xl mx-auto">
    <!-- Header Card -->
    <div class="relative bg-gradient-to-r from-blue-600 to-emerald-400 rounded-3xl p-8 text-white shadow-xl overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-4xl font-bold shadow-lg border-2 border-white/30">
                <?= strtoupper(substr($user['name'], 0, 2)) ?>
            </div>
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-bold mb-1"><?= htmlspecialchars($user['name']) ?></h1>
                <p class="text-blue-100 opacity-90">Administrator • <?= htmlspecialchars($user['username']) ?></p>
            </div>
        </div>
        <!-- Decorative Shapes -->
        <div class="absolute -right-10 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute top-10 right-20 w-20 h-20 bg-blue-400/20 rounded-full blur-xl"></div>
    </div>

    <?php if ($message): ?>
        <script>Swal.fire({ title: 'Berhasil!', text: '<?= $message ?>', icon: 'success', confirmButtonColor: '#2563eb', customClass: { popup: 'rounded-3xl' } });</script>
    <?php endif; ?>
    <?php if ($error): ?>
        <script>Swal.fire({ title: 'Gagal!', text: '<?= $error ?>', icon: 'error', confirmButtonColor: '#ef4444', customClass: { popup: 'rounded-3xl' } });</script>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4">Informasi Akun</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-2xl">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase">Role</p>
                            <p class="font-medium text-slate-800">Administrator</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-2xl">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase">Terakhir Login</p>
                            <p class="font-medium text-slate-800"><?= date('d M Y H:i') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-edit text-blue-600"></i> Edit Profil
                </h3>
                
                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                            <div class="relative">
                                <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-4">Keamanan</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Password Saat Ini <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="password" name="current_password" id="current_password" class="w-full pl-10 pr-12 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-slate-50 focus:bg-white" required placeholder="Verifikasi password lama">
                                    <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                                    <div class="relative">
                                        <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="new_password" id="new_password" class="w-full pl-10 pr-12 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Kosongkan jika tetap">
                                        <button type="button" onclick="togglePassword('new_password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                                    <div class="relative">
                                        <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="confirm_password" id="confirm_password" class="w-full pl-10 pr-12 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Ulangi password baru">
                                        <button type="button" onclick="togglePassword('confirm_password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div> md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                                    <div class="relative">
                                        <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="new_password" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Kosongkan jika tetap">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                                    <div class="relative">
                                        <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="confirm_password" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2 transform active:scale-95">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

<?php include 'includes/footer.php'; ?>
