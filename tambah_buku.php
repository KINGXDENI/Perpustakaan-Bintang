<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}
if(($_SESSION['role'] ?? '') != 'admin'){
    setNotif('Akses ditolak! Khusus administrator.', 'error');
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}

if(isset($_POST['simpan'])){
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $tahun = (int)$_POST['tahun'];
    $stok = (int)$_POST['stok'];
    
    mysqli_query($koneksi, "INSERT INTO buku(judul,penulis,tahun,stok) VALUES('$judul','$penulis','$tahun','$stok')");
    setNotif('Sukses! Buku "' . $judul . '" berhasil ditambahkan.', 'success');
    echo "<script>window.location='kelola_buku.php';</script>";
    exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="id" data-theme="night">
<head>
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - Perpustakaan Bintang</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS & DaisyUI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="style.css">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen pb-12">

    <!-- Modal Logout (DaisyUI Native Dialog with Premium Design) -->
    <dialog id="modalLogout" class="modal z-50">
        <div class="modal-box p-6 border border-[var(--glass-border)] shadow-2xl relative overflow-hidden">
            <!-- Glow background decor -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-[var(--accent-primary)]/10 blur-xl"></div>
            
            <h3 class="text-lg font-bold text-[var(--accent-danger)] flex items-center gap-2">
                <div class="p-2 rounded-xl bg-[var(--accent-danger)]/10 flex items-center justify-center">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </div>
                Konfirmasi Keluar
            </h3>
            <p class="py-4 text-secondary-theme text-sm">Apakah Anda yakin ingin keluar dari Perpustakaan Bintang, <span class="font-bold text-primary-theme"><?= htmlspecialchars($nama) ?></span>?</p>
            <div class="modal-action flex gap-2 pt-2">
                <button class="btn btn-premium-ghost rounded-xl px-5" onclick="tutupModalLogout()">Batal</button>
                <a href="logout.php" class="btn btn-premium-danger px-6 rounded-xl flex items-center justify-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                </a>
            </div>
        </div>
    </dialog>

    <!-- Navigation Header (Floating Glass Nav) -->
    <?php include 'navbar.php'; ?>

    <!-- Main Container -->
    <main class="container mx-auto px-4 mt-8 max-w-4xl">
        
        <!-- Back Navigation link -->
        <div class="mb-6 flex justify-start">
            <a href="kelola_buku.php" class="btn btn-premium-ghost btn-sm rounded-xl text-secondary-theme text-xs px-4 py-2 gap-1.5 flex items-center justify-center">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Side: Overhauled Form Card (col-span-7) -->
            <div class="card lg:col-span-7 w-full glass-card overflow-hidden shadow-2xl transition-all duration-300 hover:shadow-[rgba(177,116,87,0.15)]">
                <!-- Glow top line -->
                <div class="h-1 w-full bg-gradient-to-r from-[var(--accent-primary)] via-[var(--accent-secondary)] to-[var(--accent-primary)] opacity-60"></div>
                
                <div class="card-body p-6 sm:p-8 text-secondary-theme">
                    <!-- Header/Logo -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--accent-primary)]/10 border border-[var(--accent-primary)]/20 flex items-center justify-center shadow-lg shadow-[var(--accent-primary)]/10 mb-3 transition-transform hover:scale-110 duration-300">
                            <i data-lucide="plus-circle" class="icon-indigo w-6 h-6 filter drop-shadow-[0_0_8px_rgba(177,116,87,0.5)]"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-center tracking-tight text-primary-theme">
                            Tambah Buku Baru
                        </h2>
                        <p class="text-[10px] text-secondary-theme opacity-70 mt-0.5 tracking-wider uppercase font-semibold">Input Informasi Katalog Baru</p>
                    </div>

                    <!-- Addition Form -->
                    <form method="POST" class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-secondary-theme text-xs">Judul Buku</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <input type="text" name="judul" id="inputJudul" placeholder="Masukkan judul buku" class="input input-glass w-full text-primary-theme" required autocomplete="off">
                                <i data-lucide="book" class="input-icon-left w-4 h-4"></i>
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-secondary-theme text-xs">Nama Penulis</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <input type="text" name="penulis" id="inputPenulis" placeholder="Nama penulis buku" class="input input-glass w-full text-primary-theme" required autocomplete="off">
                                <i data-lucide="user" class="input-icon-left w-4 h-4"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control w-full">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-secondary-theme text-xs">Tahun Terbit</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="number" name="tahun" id="inputTahun" placeholder="Contoh: 2024" min="1900" max="2026" class="input input-glass w-full text-primary-theme" required>
                                    <i data-lucide="calendar" class="input-icon-left w-4 h-4"></i>
                                </div>
                            </div>

                            <div class="form-control w-full">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-secondary-theme text-xs">Jumlah Stok</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="number" name="stok" id="inputStok" placeholder="Contoh: 5" min="0" class="input input-glass w-full text-primary-theme" required>
                                    <i data-lucide="layers" class="input-icon-left w-4 h-4"></i>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" name="simpan" class="btn btn-premium w-full text-white font-bold tracking-wide transition-all duration-300 gap-1.5 flex items-center justify-center">
                                <i data-lucide="save" class="w-4 h-4"></i> Simpan Buku Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side: Live Pratinjau Card (col-span-5) -->
            <div class="lg:col-span-5 w-full flex flex-col gap-4">
                <h3 class="text-xs uppercase font-extrabold tracking-widest text-secondary-theme opacity-80 flex items-center gap-1.5 ml-1">
                    <span class="glow-dot"></span> Pratinjau Real-Time
                </h3>
                
                <div class="glass-card p-6 flex flex-col justify-between h-[360px] relative overflow-hidden transition-all duration-300 border border-[var(--accent-primary)]/20 shadow-xl" style="background: var(--bg-dark-glow);">
                    <!-- Card top glow decoration -->
                    <div class="absolute -top-12 -right-12 w-28 h-28 rounded-full bg-[var(--accent-primary)]/10 blur-2xl"></div>
                    
                    <div>
                        <!-- Book Cover Dummy -->
                        <div class="w-full h-36 rounded-xl bg-gradient-to-br from-[var(--accent-primary)]/15 via-[var(--accent-secondary)]/5 to-[var(--accent-primary)]/5 border border-[var(--glass-border)] flex items-center justify-center mb-5 relative overflow-hidden">
                            <i data-lucide="book-open" class="w-12 h-12 icon-brand animate-pulse"></i>
                            <div class="absolute bottom-3 right-3">
                                <span class="badge badge-premium-ghost py-1.5 px-3 text-[9px] uppercase tracking-wider shadow-sm" id="previewTahun">2026</span>
                            </div>
                        </div>
                        
                        <!-- Book Title & Author -->
                        <h4 class="font-extrabold text-xl text-primary-theme line-clamp-1" id="previewJudul">Judul Buku Baru</h4>
                        <div class="flex items-center gap-1.5 mt-2.5 text-sm text-secondary-theme opacity-90">
                            <i data-lucide="user" class="w-4 h-4 icon-indigo"></i>
                            <span class="font-medium line-clamp-1" id="previewPenulis">Nama Penulis</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[var(--glass-border)] flex items-center justify-between">
                        <div>
                            <p class="text-[9px] uppercase tracking-widest text-secondary-theme opacity-60">Stok Tersedia</p>
                            <p class="text-base font-black text-primary-theme mt-0.5" id="previewStok">0 Buku</p>
                        </div>
                        <div>
                            <span class="badge badge-glass-success py-2.5 px-3.5 text-[10px]" id="previewStatus">Tersedia</span>
                        </div>
                    </div>
                </div>
                
                <div class="alert toast-glass shadow-md py-3 px-4 flex items-start gap-3 border border-[var(--glass-border)]">
                    <i data-lucide="info" class="w-4 h-4 icon-indigo mt-0.5"></i>
                    <p class="text-[11px] text-secondary-theme leading-relaxed">
                        Kartu di atas adalah simulasi tampilan buku ketika terbit di katalog umum anggota. Periksa kembali informasi sebelum menyimpan.
                    </p>
                </div>
            </div>
            
        </div>

    </main>

    <!-- JS Scripts -->
    <script>
        function bukaModalLogout(){
            document.getElementById('modalLogout').showModal();
        }
        function tutupModalLogout(){
            document.getElementById('modalLogout').close();
        }

        // Live Real-time script
        const inputJudul = document.getElementById('inputJudul');
        const inputPenulis = document.getElementById('inputPenulis');
        const inputTahun = document.getElementById('inputTahun');
        const inputStok = document.getElementById('inputStok');

        const previewJudul = document.getElementById('previewJudul');
        const previewPenulis = document.getElementById('previewPenulis');
        const previewTahun = document.getElementById('previewTahun');
        const previewStok = document.getElementById('previewStok');
        const previewStatus = document.getElementById('previewStatus');

        function updatePreview() {
            previewJudul.innerText = inputJudul.value.trim() !== '' ? inputJudul.value : 'Judul Buku Baru';
            previewPenulis.innerText = inputPenulis.value.trim() !== '' ? inputPenulis.value : 'Nama Penulis';
            previewTahun.innerText = inputTahun.value.trim() !== '' ? inputTahun.value : '2026';
            
            const stokVal = parseInt(inputStok.value) || 0;
            previewStok.innerText = stokVal + ' Buku';
            
            if (stokVal > 0) {
                previewStatus.innerText = 'Tersedia';
                previewStatus.className = 'badge badge-glass-success py-2.5 px-3.5 text-[10px]';
            } else {
                previewStatus.innerText = 'Habis';
                previewStatus.className = 'badge badge-glass-error py-2.5 px-3.5 text-[10px]';
            }
        }

        inputJudul.addEventListener('input', updatePreview);
        inputPenulis.addEventListener('input', updatePreview);
        inputTahun.addEventListener('input', updatePreview);
        inputStok.addEventListener('input', updatePreview);
        
        window.addEventListener('DOMContentLoaded', updatePreview);
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>