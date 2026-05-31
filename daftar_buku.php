<?php
include 'koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
$user_id = $_SESSION['user_id'];

// Ambil semua buku
$buku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
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
    <title>Daftar Buku - Perpustakaan Bintang</title>
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
    <main class="container mx-auto px-4 mt-8 max-w-6xl">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
            <div class="text-center sm:text-left">
                <h2 class="text-3xl font-black tracking-tight flex items-center justify-center sm:justify-start gap-3 text-primary-theme">
                    <i data-lucide="book-open" class="icon-indigo w-8 h-8"></i> 
                    <span>Katalog Buku Bintang</span>
                </h2>
                <p class="text-[10px] text-secondary-theme opacity-80 mt-1 uppercase tracking-widest font-semibold ml-11">Semua Koleksi Dalam Satu Genggaman</p>
            </div>
            <a href="dashboard.php" class="btn btn-premium-ghost rounded-2xl text-secondary-theme text-sm gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        <!-- Real-time Search Box -->
        <div class="glass-card p-4 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="relative w-full sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-secondary-theme opacity-60">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" id="pencarianBuku" onkeyup="cariBuku()" placeholder="Cari judul atau penulis..." class="input input-glass pl-9 w-full text-sm">
            </div>
            <div class="text-xs text-secondary-theme opacity-70">
                Menampilkan <span id="jumlahBuku" class="font-bold text-primary-theme"><?= mysqli_num_rows($buku) ?></span> buku
            </div>
        </div>

        <!-- Books Grid Container -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6" id="katalogGrid">
            <?php if(mysqli_num_rows($buku) > 0): ?>
                <?php while($b = mysqli_fetch_assoc($buku)):
                    $udah_pinjam = 0;
                    if($role == 'user'){
                        $udah_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE user_id=$user_id AND buku_id={$b['id']} AND status='dipinjam'"));
                    }
                ?>
                <div class="glass-card glass-card-hover p-6 flex flex-col justify-between h-full relative overflow-hidden book-card" data-judul="<?= strtolower(htmlspecialchars($b['judul'])) ?>" data-penulis="<?= strtolower(htmlspecialchars($b['penulis'])) ?>">
                    <!-- Card top glow decoration -->
                    <div class="absolute -top-12 -right-12 w-28 h-28 rounded-full bg-[var(--accent-primary)]/5 blur-xl"></div>
                    
                    <div>
                        <!-- Book Cover Dummy/Sleek design -->
                        <div class="w-full h-32 rounded-xl bg-gradient-to-br from-[var(--accent-primary)]/10 via-[var(--accent-secondary)]/5 to-[var(--accent-primary)]/5 border border-[var(--glass-border)] flex items-center justify-center mb-4 relative overflow-hidden group">
                            <i data-lucide="book" class="w-10 h-10 icon-brand transition-transform group-hover:scale-110 duration-300"></i>
                            <div class="absolute bottom-2.5 right-2.5">
                                <span class="badge badge-premium-ghost py-1 px-2.5 text-[9px] uppercase tracking-wider"><?= $b['tahun'] ?></span>
                            </div>
                        </div>
                        
                        <!-- Book Title & Author -->
                        <h4 class="font-extrabold text-lg text-primary-theme line-clamp-1" title="<?= htmlspecialchars($b['judul']) ?>"><?= htmlspecialchars($b['judul']) ?></h4>
                        <div class="flex items-center gap-1.5 mt-2 text-xs text-secondary-theme opacity-80">
                            <i data-lucide="user" class="w-3.5 h-3.5 opacity-60"></i>
                            <span class="font-medium line-clamp-1"><?= htmlspecialchars($b['penulis']) ?></span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[var(--glass-border)] flex items-center justify-between">
                        <div>
                            <p class="text-[9px] uppercase tracking-widest text-secondary-theme opacity-60">Stok Tersedia</p>
                            <p class="text-sm font-black text-primary-theme mt-0.5"><?= $b['stok'] ?> Buku</p>
                        </div>
                        <div>
                            <?php if($udah_pinjam > 0): ?>
                                <span class="badge badge-glass-info py-2 px-2.5 text-[10px]">Dipinjam</span>
                            <?php elseif($b['stok'] > 0): ?>
                                <span class="badge badge-glass-success py-2 px-2.5 text-[10px]">Tersedia</span>
                            <?php else: ?>
                                <span class="badge badge-glass-error py-2 px-2.5 text-[10px]">Habis</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Button Actions -->
                    <div class="mt-4">
                        <?php if($role == 'user'): ?>
                            <?php if($udah_pinjam > 0): ?>
                                <button class="btn btn-premium-ghost w-full rounded-xl py-2.5 text-xs gap-1.5 opacity-60 cursor-not-allowed" disabled>
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Sedang Dipinjam
                                </button>
                            <?php elseif($b['stok'] > 0): ?>
                                <a href="pinjam.php?id=<?= $b['id'] ?>" class="btn btn-premium w-full rounded-xl py-2.5 text-xs text-white gap-1.5">
                                    <i data-lucide="book-open" class="w-4 h-4"></i> Pinjam Buku
                                </a>
                            <?php else: ?>
                                <button class="btn btn-premium-ghost w-full rounded-xl py-2.5 text-xs gap-1.5 opacity-40 cursor-not-allowed" disabled>
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Stok Habis
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Admin shortcut -->
                            <a href="edit_buku.php?id=<?= $b['id'] ?>" class="btn btn-premium-ghost w-full rounded-xl py-2 text-xs gap-1.5 text-secondary-theme">
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit Detail Buku
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12 glass-card">
                    <p class="text-sm text-secondary-theme opacity-60">Belum ada buku terdaftar di katalog Perpustakaan Bintang.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <script>
        function bukaModalLogout(){
            document.getElementById('modalLogout').showModal();
        }
        function tutupModalLogout(){
            document.getElementById('modalLogout').close();
        }

        // Real-time grid filter search
        function cariBuku() {
            const query = document.getElementById('pencarianBuku').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.book-card');
            let count = 0;
            
            cards.forEach(card => {
                const judul = card.getAttribute('data-judul');
                const penulis = card.getAttribute('data-penulis');
                if (judul.includes(query) || penulis.includes(query)) {
                    card.style.display = 'flex';
                    count++;
                } else {
                    card.style.display = 'none';
                }
            });
            document.getElementById('jumlahBuku').innerText = count;
        }
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>