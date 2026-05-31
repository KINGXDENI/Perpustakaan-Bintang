# Perpustakaan Bintang

Aplikasi manajemen perpustakaan berbasis web bergaya SaaS modern yang dibangun dengan **PHP**, **MySQL**, dan UI premium glass‑morphism (DaisyUI + Tailwind CDN). Aplikasi ini mendukung tema terang dan gelap, tata letak responsif mobile, notifikasi toast, serta dashboard katalog buku interaktif.

---

## 📦 Prasyarat

- **PHP 8.0+** (tersedia pada Laragon atau XAMPP)
- **MySQL 5.7+** (atau MariaDB) – sudah termasuk dalam Laragon/XAMPP
- **Composer** (opsional, bila ingin memasang paket PHP tambahan)
- **Web server** – Apache/Nginx (Laragon sudah menyertakan Apache yang terkonfigurasi otomatis)

---

## 🛠️ Setup

1. **Clone / download repository**
   ```bash
   # Jika Anda memiliki Git
   git clone https://github.com/KINGXDENI/perpustakaan-bintang.git
   ```
   Atau cukup salin folder `perpustakaan-bintang` ke dalam direktori `www` milik Laragon.

2. **Buat basis data**
   - Buka **phpMyAdmin** (http://localhost/phpmyadmin) atau gunakan MySQL CLI.
   - Buat basis data baru bernama `perpustakaan`:
     ```sql
     CREATE DATABASE perpustakaan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   - Impor skema serta data contoh dari file `perpustakaan.sql` yang berada di root proyek.

3. **Konfigurasi koneksi DB**
   - Buka `koneksi.php` (atau file yang menyimpan detail koneksi MySQL).
   - Sesuaikan parameter pada fungsi `mysqli_connect` bila kredensial MySQL Anda berbeda dari default:
     ```php
     $koneksi = mysqli_connect('localhost', 'root', '', 'perpustakaan');
     // atau sesuaikan dengan nama database, user, password Anda
     ```
   - Simpan perubahan.

4. **Jalankan server pengembangan**
   - Pada Laragon, klik **Start → Apache & MySQL**.
   - Buka browser dan arahkan ke `http://localhost/perpustakaan-bintang/`.

---

## 🚀 Cara Pakai

1. **Daftar akun** – pengunjung pertama harus klik **Register** dan mengisi formulir.
2. **Login** – masuk menggunakan kredensial yang telah didaftarkan untuk mengakses dashboard admin.
3. **Dashboard** – kelola buku, lihat katalog interaktif, dan proses permintaan peminjaman.
4. **Tambah / Edit buku** – form tambah/ubah kini menampilkan tata letak dua kolom dengan kartu preview secara real‑time.
5. **Pinjam buku** – pilih durasi melalui selector pill; notifikasi toast menampilkan konfirmasi.
6. **Toggle tema** – klik ikon bulan/matahari di navbar untuk beralih antara Light dan Dark mode (UI menyesuaikan otomatis).
7. **Desain responsif** – aplikasi berfungsi baik pada desktop, tablet, maupun perangkat mobile.

---

## 🎨 Tema & Desain

- **Palet warna**: Earthy‑Premium (hijau lembut, cokelat netral, aksen teal).
- **Glass‑morphism**: diterapkan pada kartu, dropdown, dan toast.
- **Ikon**: Feather icons dimuat lewat CDN; tidak ada emoji berlebih.
- **DaisyUI**: komponen UI menggunakan CDN DaisyUI – tidak memerlukan instalasi NPM lokal.

---

## 🐞 Masalah Umum & Troubleshooting

- **Error koneksi database** – pastikan `koneksi.php` sudah di‑set dengan benar dan MySQL sedang berjalan.
- **CSS / ikon tidak muncul** – pastikan koneksi internet aktif karena Tailwind & DaisyUI dimuat dari CDN.
- **Toast tidak muncul** – pastikan JavaScript diaktifkan dan tidak ada error di console browser.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License**. Anda dapat mem‑fork, memodifikasi, dan menggunakannya untuk keperluan pribadi maupun komersial.

---

*Selamat coding!*
