<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Silakan isi semua kolom.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Javiardi Dima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-50 to-slate-100"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-200/30 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-200/30 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="glass p-10 rounded-[2.5rem] shadow-2xl w-full max-w-sm text-center relative z-10">
        <!-- Logo / Avatar -->
        <div class="w-24 h-24 bg-gradient-to-tr from-blue-600 to-emerald-400 rounded-3xl rotate-3 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-8 shadow-xl shadow-blue-200 transform hover:rotate-6 transition-transform duration-300">
            JD
        </div>
        
        <h2 class="text-2xl font-bold mb-2 text-slate-800 tracking-tight">Selamat Datang!</h2>
        <p class="text-slate-400 text-sm mb-8 font-medium">Silakan masuk ke akun administrator Anda</p>
        
        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl relative mb-6 text-sm flex items-center gap-2 animate-pulse">
                <i class="fas fa-exclamation-circle"></i>
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div class="group relative">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center transition-colors group-focus-within:bg-blue-600 group-focus-within:text-white">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <input type="text" name="username" class="w-full pl-16 pr-4 py-4 bg-white border-2 border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium placeholder-slate-300 text-slate-700" placeholder="Username" required>
            </div>
            
            <div class="group relative">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center transition-colors group-focus-within:bg-blue-600 group-focus-within:text-white">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <input type="password" name="password" id="password" class="w-full pl-16 pr-12 py-4 bg-white border-2 border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium placeholder-slate-300 text-slate-700" placeholder="Password" required>
                <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-emerald-500 text-white py-4 rounded-2xl font-bold hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group">
                <span>Masuk Sekarang</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <p class="mt-8 text-xs text-slate-400">
            &copy; <?= date('Y') ?> Javiardi Dima. All rights reserved.
        </p>
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
</body>
</html>
