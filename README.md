# Sistem Pembelajaran & Absensi - Javiardi Dima

Aplikasi web untuk manajemen materi, absensi siswa, dan asisten AI.

## Fitur
- **Public (Siswa):**
  - Melihat materi pelajaran per mata pelajaran.
  - Chat dengan AI Assistant (Gemini) untuk bertanya tentang materi.
- **Admin (Guru):**
  - Dashboard statistik.
  - Manajemen Siswa (Tambah, Edit, Hapus).
  - Manajemen Materi (Tambah, Edit, Hapus).
  - Absensi Siswa (Hadir, Alpa, Sakit, Izin).
  - Chat dengan AI Assistant.

## Cara Instalasi
1. Pastikan Laragon/XAMPP sudah berjalan.
2. Buka folder project ini di browser: `http://localhost/aii` (sesuaikan dengan nama folder).
3. Database akan otomatis dibuat saat pertama kali akses, atau Anda bisa import file `database.sql` ke database `school_db`.

## Login Admin
- **URL:** `/admin/login.php`
- **Username:** `javierdima`
- **Password:** `jdkeh260602`

## Konfigurasi AI (Gemini)
Agar fitur chat berfungsi, Anda perlu memasukkan API Key Gemini (Gratis).
1. Buka file `config/ai_config.php`.
2. Isi `GEMINI_API_KEY` dengan API Key Anda.
3. Dapatkan API Key di: [Google AI Studio](https://aistudio.google.com/app/apikey).

## Struktur Folder
- `admin/` - Halaman dashboard guru.
- `api/` - Backend API untuk chat.
- `config/` - Konfigurasi database dan AI.
- `index.php` - Halaman depan untuk siswa.
