<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 bg-white shadow-xl z-50 w-64 transform -translate-x-full lg:translate-x-0 lg:static transition-all duration-300 flex flex-col border-r border-slate-100">
    <!-- Header Sidebar -->
    <div class="h-28 flex flex-col justify-center px-6 border-b border-slate-50 bg-white relative">
        <div class="flex items-center gap-3 mb-1 overflow-hidden">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-emerald-400 flex items-center justify-center text-white font-bold shadow-md shrink-0">
                JD
            </div>
            <div class="sidebar-text transition-opacity duration-300">
                <h3 class="text-lg font-bold text-slate-800 tracking-tight leading-tight whitespace-nowrap">JAVIARDI DIMA</h3>
                <p class="text-slate-400 text-xs font-medium whitespace-nowrap">Teacher Admin</p>
            </div>
        </div>
        <!-- Toggle Button for Desktop -->
        <button id="sidebar-collapse-btn" class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white border border-slate-200 rounded-full shadow-sm flex items-center justify-center text-slate-500 hover:text-blue-600 hover:border-blue-200 hidden lg:flex z-50">
            <i class="fas fa-chevron-left text-xs transition-transform duration-300"></i>
        </button>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-3 py-6 space-y-6 overflow-y-auto custom-scrollbar overflow-x-hidden">
        
        <!-- Group: Menu Utama -->
        <div>
            <p class="sidebar-text text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-4 opacity-70 whitespace-nowrap transition-opacity duration-300">Menu Utama</p>
            <div class="space-y-1">
                <a href="index.php" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= $current_page == 'index.php' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>" title="Dashboard">
                    <i class="<?= $current_page == 'index.php' ? 'fas' : 'fas' ?> fa-tachometer-alt w-5 text-center transition-transform group-hover:scale-110 shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Dashboard</span>
                    <?php if($current_page == 'index.php'): ?><span class="sidebar-text ml-auto w-1.5 h-1.5 bg-blue-600 rounded-full transition-opacity duration-300"></span><?php endif; ?>
                </a>
                <a href="students.php" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= $current_page == 'students.php' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>" title="Data Siswa">
                    <i class="<?= $current_page == 'students.php' ? 'fas' : 'fas' ?> fa-users w-5 text-center transition-transform group-hover:scale-110 shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Data Siswa</span>
                    <?php if($current_page == 'students.php'): ?><span class="sidebar-text ml-auto w-1.5 h-1.5 bg-blue-600 rounded-full transition-opacity duration-300"></span><?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Group: Akademik -->
        <div>
            <p class="sidebar-text text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-4 opacity-70 whitespace-nowrap transition-opacity duration-300">Akademik</p>
            <div class="space-y-1">
                <a href="attendance.php" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= $current_page == 'attendance.php' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>" title="Input Absensi">
                    <i class="<?= $current_page == 'attendance.php' ? 'fas' : 'fas' ?> fa-user-check w-5 text-center transition-transform group-hover:scale-110 shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Input Absensi</span>
                    <?php if($current_page == 'attendance.php'): ?><span class="sidebar-text ml-auto w-1.5 h-1.5 bg-blue-600 rounded-full transition-opacity duration-300"></span><?php endif; ?>
                </a>
                <a href="attendance_report.php" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= $current_page == 'attendance_report.php' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>" title="Rekapan Absensi">
                    <i class="<?= $current_page == 'attendance_report.php' ? 'fas' : 'fas' ?> fa-file-alt w-5 text-center transition-transform group-hover:scale-110 shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Rekapan Absensi</span>
                    <?php if($current_page == 'attendance_report.php'): ?><span class="sidebar-text ml-auto w-1.5 h-1.5 bg-blue-600 rounded-full transition-opacity duration-300"></span><?php endif; ?>
                </a>
                <a href="materials.php" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= $current_page == 'materials.php' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>" title="Materi Ajar">
                    <i class="<?= $current_page == 'materials.php' ? 'fas' : 'fas' ?> fa-book-open w-5 text-center transition-transform group-hover:scale-110 shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Materi Ajar</span>
                    <?php if($current_page == 'materials.php'): ?><span class="sidebar-text ml-auto w-1.5 h-1.5 bg-blue-600 rounded-full transition-opacity duration-300"></span><?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Group: Lainnya -->
        <div>
             <div class="space-y-1">
                <a href="profile.php" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= $current_page == 'profile.php' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>" title="Pengaturan Akun">
                    <i class="<?= $current_page == 'profile.php' ? 'fas' : 'fas' ?> fa-user-cog w-5 text-center transition-transform group-hover:scale-110 shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Pengaturan Akun</span>
                    <?php if($current_page == 'profile.php'): ?><span class="sidebar-text ml-auto w-1.5 h-1.5 bg-blue-600 rounded-full transition-opacity duration-300"></span><?php endif; ?>
                </a>
             </div>
        </div>

    </nav>

    <!-- Footer Sidebar -->
    <div class="p-4 border-t border-slate-50 bg-slate-50/50 space-y-1">
        <a href="../index.php" target="_blank" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-500 hover:text-blue-600 transition-all text-sm font-medium" title="Lihat Website">
            <i class="fas fa-external-link-alt w-5 text-center shrink-0"></i>
            <span class="sidebar-text whitespace-nowrap transition-opacity duration-300">Lihat Website</span>
        </a>
        <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-500 hover:text-red-600 transition-all text-sm font-medium" title="Logout">
            <i class="fas fa-sign-out-alt w-5 text-center shrink-0"></i>
            <span class="sidebar-text whitespace-nowrap transition-opacity duration-300">Logout</span>
        </a>
    </div>
</aside>

<div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden bg-slate-50">
    <header class="bg-white px-8 py-5 flex justify-between items-center border-b border-slate-100 sticky top-0 z-40">
        <button id="sidebar-toggle" class="p-2 hover:bg-slate-100 rounded-lg lg:hidden text-slate-600">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="flex items-center gap-4 ml-auto">
            <div class="text-right hidden sm:block">
                <p class="font-bold text-slate-800 text-sm leading-tight"><?= $_SESSION['name'] ?? 'Admin' ?></p>
                <p class="text-xs text-slate-500">Administrator</p>
            </div>
            <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 border border-slate-200">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </header>
    <main class="p-8 flex-1 overflow-y-auto">
