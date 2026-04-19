# 🚀 Portal Lowongan Kerja (Pencari Kerja)

![Banner](job_portal_banner_1776572183544.png)

Sebuah platform manajemen lowongan kerja berbasis web yang dirancang untuk memudahkan proses rekrutmen. Dibangun dengan PHP Native yang terstruktur dan menggunakan Bootstrap untuk antarmuka yang responsif dan modern.

---

## ✨ Fitur Utama

- **Dashboard Admin**: Ringkasan data rekrutmen yang informatif.
- **Manajemen Lowongan**: Tambah, edit, dan hapus lowongan kerja dengan status (Buka/Tutup).
- **Manajemen Pelamar**: Pantau pelamar yang masuk untuk setiap lowongan.
- **Status Aplikasi**: Ubah status pelamar (Pending, Review, Diterima, Ditolak).
- **Profil Admin**: Pengaturan profil dengan fitur unggah foto.
- **Desain Modern**: Antarmuka bersih menggunakan Bootstrap 5 dan FontAwesome.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP 8.x (Native dengan Struktur Modular)
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5, Vanilla CSS, JavaScript
- **Icons**: FontAwesome 6

---

## 🚀 Cara Penggunaan & Instalasi

### 1. Persiapan Lingkungan
Pastikan Anda sudah menginstal aplikasi server lokal seperti **XAMPP**, **Laragon**, atau **WAMP**.

### 2. Klon atau Unduh Project
Salin folder project ini ke dalam direktori server lokal Anda:
- XAMPP: `C:\xampp\htdocs\pencari_kerja`
- Laragon: `C:\laragon\www\pencari_kerja`

### 3. Konfigurasi Database
1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Buat database baru dengan nama `pencari_kerja`.
3. Pilih database tersebut, lalu pilih menu **Import**.
4. Pilih file SQL yang berada di: `sql/database.sql`.
5. Klik **Go** atau **Import**.

### 4. Konfigurasi Aplikasi
Buka file `config/db.php` dan sesuaikan kredensial database Anda jika berbeda:
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pencari_kerja';
```

### 5. Jalankan Aplikasi
Buka browser Anda dan akses:
`http://localhost/pencari_kerja`

---

## 🔐 Informasi Login Admin

Gunakan akun default berikut untuk masuk ke panel administrasi:

| Username | Password |
| :--- | :--- |
| **admin** | **admin123** |

---

## 📁 Struktur Folder

```text
pencari_kerja/
├── assets/          # File CSS, JS, dan Gambar
├── config/          # Konfigurasi Database
├── includes/        # Komponen Reusable (Header, Footer, Sidebar)
├── modules/         # Logika Fitur (Jobs, Profile, Applicants)
├── sql/             # File Skema Database
├── uploads/         # Folder Penyimpanan File (Foto Profil, Resume)
├── index.php        # Halaman Utama Dashboard
├── login.php        # Halaman Login
└── logout.php       # Proses Logout
```

---

## 🤝 Kontribusi

Kontribusi selalu diterima! Jika Anda ingin meningkatkan fitur atau memperbaiki bug, silakan buat *Pull Request*.

---

## 📄 Lisensi

Project ini dibuat untuk tujuan pembelajaran dan pengembangan. Bebas digunakan dan dimodifikasi.

---

Developed with ❤️ by **Antigravity AI**
