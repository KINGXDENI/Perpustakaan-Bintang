<?php
include 'koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}

if($_SESSION['role'] != 'user'){
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

// PROSES PINJAM PAKAI POST
if(isset($_POST['konfirm_pinjam'])){
    $id_buku = (int)$_POST['id_buku'];
    $lama_hari = (int)$_POST['lama_hari'];
    
    if($lama_hari < 1 || $lama_hari > 7){
        setNotif('Maksimal durasi peminjaman adalah 7 hari!', 'error');
        echo "<script>window.location='pinjam.php';</script>";
        exit;
    }
    
    // 1. Cek stok buku
    $cek_buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id=$id_buku"));
    if($cek_buku['stok'] <= 0){
        setNotif('Stok buku tersebut sudah habis!', 'error');
        echo "<script>window.location='pinjam.php';</script>";
        exit;
    }

    // 2. Cek user udah pinjam buku ini apa belum
    $cek_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE user_id=$user_id AND buku_id=$id_buku AND status='dipinjam'"));
    if($cek_pinjam > 0){
        setNotif('Anda sedang meminjam buku ini. Harap kembalikan terlebih dahulu!', 'error');
        echo "<script>window.location='pinjam.php';</script>";
        exit;
    }

    // 3. Hitung tanggal kembali
    $tgl_pinjam = date('Y-m-d');
    $tgl_kembali = date('Y-m-d', strtotime("+$lama_hari days"));

    // 4. Insert peminjaman + kurangi stok
    mysqli_query($koneksi, "INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, tanggal_kembali, status) VALUES ($user_id, $id_buku, '$tgl_pinjam', '$tgl_kembali', 'dipinjam')");
    mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id=$id_buku");
    
    setNotif('Sukses! Buku "'.$cek_buku['judul'].'" berhasil dipinjam selama '.$lama_hari.' hari.', 'success');
    echo "<script>window.location='riwayat.php';</script>";
    exit;
}

// Deteksi Auto-Trigger Modal dari Redirect Catalog
$auto_open_id = 0;
$auto_open_judul = '';
if(isset($_GET['id'])){
    $get_id = (int)$_GET['id'];
    $get_buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id=$get_id"));
    if($get_buku){
        $auto_open_id = $get_buku['id'];
        $auto_open_judul = $get_buku['judul'];
    }
}

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
    <title>Pinjam Buku - Perpustakaan Bintang</title>
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

    <!-- Modal Pinjam Durasi (DaisyUI Native Dialog with Premium Design) -->
    <dialog id="modalPinjam" class="modal z-50">
        <div class="modal-box p-6 sm:p-8 border border-[var(--glass-border)] shadow-2xl relative overflow-hidden">
            <!-- Glow background decor -->
            <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full bg-[var(--accent-primary)]/10 blur-xl"></div>
            
            <h3 class="text-xl font-bold text-primary-theme flex items-center gap-2">
                <div class="p-2.5 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center">
                    <i data-lucide="clock" class="icon-brand w-5 h-5"></i>
                </div>
                <span>Durasi Peminjaman</span>
            </h3>
            <p class="py-1 text-[10px] text-secondary-theme opacity-70 uppercase tracking-wider font-semibold ml-12">Tentukan rentang waktu peminjaman Anda</p>
            
            <div class="mt-6 p-4 rounded-xl bg-[var(--glass-bg)] border border-[var(--glass-border)]">
                <p class="text-xs text-secondary-theme opacity-60">Buku yang dipilih:</p>
                <p class="text-sm font-bold text-primary-theme mt-1" id="judulBukuModal"></p>
            </div>
            
            <form method="POST" class="mt-6 space-y-5">
                <input type="hidden" name="id_buku" id="idBukuModal">
                <input type="hidden" name="lama_hari" id="lamaHariInput" value="3" required>
                
                <div class="form-control w-full">
                    <label class="label pb-2.5">
                        <span class="label-text font-bold text-secondary-theme text-xs">Pilih Durasi Peminjaman</span>
                    </label>
                    
                    <!-- Premium Horizontal Pill Selector -->
                    <div class="grid grid-cols-4 sm:grid-cols-7 gap-2" id="durasiSelector">
                        <?php for($i=1; $i<=7; $i++): ?>
                            <button type="button" onclick="setDurasi(<?= $i ?>)" id="btnDurasi<?= $i ?>" class="py-2 rounded-xl border border-[var(--glass-border)] text-center text-xs font-bold transition-all duration-200 <?= $i == 3 ? 'bg-[var(--accent-primary)] text-white border-[var(--accent-primary)] shadow-md shadow-[var(--accent-primary)]/20' : 'bg-[var(--glass-bg)] text-secondary-theme hover:bg-[rgba(177,116,87,0.08)]' ?>">
                                <?= $i ?>
                                <span class="block text-[8px] font-normal opacity-70 mt-0.5">Hari</span>
                            </button>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="modal-action pt-4 flex gap-2">
                    <button type="button" class="btn btn-premium-ghost rounded-xl px-5" onclick="tutupModalPinjam()">Batal</button>
                    <button type="submit" name="konfirm_pinjam" class="btn btn-premium rounded-xl font-bold px-6 text-white gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i> Konfirmasi Pinjam
                    </button>
                </div>
            </form>
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
                    <span>Peminjaman Katalog Buku</span>
                </h2>
                <p class="text-[10px] text-secondary-theme opacity-80 mt-1 uppercase tracking-widest font-semibold ml-11">Pilih Buku Terbaik Untuk Menemani Hari Anda</p>
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
            <?php mysqli_data_seek($buku, 0); // Reset pointer
            while($b = mysqli_fetch_assoc($buku)): 
                $udah_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE user_id=$user_id AND buku_id={$b['id']} AND status='dipinjam'"));
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
                    <?php if($udah_pinjam > 0): ?>
                        <button class="btn btn-premium-ghost w-full rounded-xl py-2.5 text-xs gap-1.5 opacity-60 cursor-not-allowed" disabled>
                            <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Sedang Dipinjam
                        </button>
                    <?php elseif($b['stok'] > 0): ?>
                        <button onclick="bukaModalPinjam(<?= $b['id'] ?>, '<?= htmlspecialchars($b['judul'], ENT_QUOTES) ?>')" class="btn btn-premium w-full rounded-xl py-2.5 text-xs text-white gap-1.5">
                            <i data-lucide="book-open" class="w-4 h-4"></i> Pinjam Buku
                        </button>
                    <?php else: ?>
                        <button class="btn btn-premium-ghost w-full rounded-xl py-2.5 text-xs gap-1.5 opacity-40 cursor-not-allowed" disabled>
                            <i data-lucide="x-circle" class="w-4 h-4"></i> Stok Habis
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
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
        
        function bukaModalPinjam(id, judul){
            document.getElementById('idBukuModal').value = id;
            document.getElementById('judulBukuModal').innerText = judul;
            setDurasi(3); // Set default to 3 days
            document.getElementById('modalPinjam').showModal();
        }
        function tutupModalPinjam(){
            document.getElementById('modalPinjam').close();
        }

        function setDurasi(hari) {
            document.getElementById('lamaHariInput').value = hari;
            for (let i = 1; i <= 7; i++) {
                const btn = document.getElementById('btnDurasi' + i);
                if (btn) {
                    btn.className = 'py-2 rounded-xl border border-[var(--glass-border)] text-center text-xs font-bold transition-all duration-200 bg-[var(--glass-bg)] text-secondary-theme hover:bg-[rgba(177,116,87,0.08)]';
                }
            }
            const activeBtn = document.getElementById('btnDurasi' + hari);
            if (activeBtn) {
                activeBtn.className = 'py-2 rounded-xl border border-[var(--accent-primary)] text-center text-xs font-bold transition-all duration-200 bg-[var(--accent-primary)] text-white border-[var(--accent-primary)] shadow-md shadow-[var(--accent-primary)]/20';
            }
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
        
        // Auto-Trigger modal jika diarahkan dari Katalog
        <?php if($auto_open_id > 0): ?>
        window.addEventListener('DOMContentLoaded', () => {
            bukaModalPinjam(<?= $auto_open_id ?>, '<?= htmlspecialchars($auto_open_judul, ENT_QUOTES) ?>');
        });
        <?php endif; ?>
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>