<!-- Navigation Header (Floating Glass Nav) -->
<nav class="navbar floating-nav sticky top-4 z-40 flex items-center justify-between">
    <!-- Logo & Brand -->
    <div class="flex-1 lg:flex-none">
        <a href="dashboard.php" class="btn btn-ghost text-lg font-bold tracking-tight gap-2 hover:bg-[rgba(177,116,87,0.08)] rounded-xl transition-all">
            <i data-lucide="sparkles" class="icon-brand filter drop-shadow-[0_0_8px_var(--accent-primary)] w-5 h-5"></i>
            <span class="text-nav-title text-primary-theme">Perpustakaan <span class="text-gold-gradient">Bintang</span></span>
        </a>
    </div>

    <!-- Desktop Menu Links (Center) -->
    <div class="hidden lg:flex flex-1 justify-center gap-1.5">
        <?php if($role == 'admin'): ?>
            <a href="dashboard.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Dashboard</a>
            <a href="kelola_buku.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= in_array(basename($_SERVER['PHP_SELF']), ['kelola_buku.php', 'tambah_buku.php', 'edit_buku.php']) ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Kelola Buku</a>
            <a href="admin_pinjam.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= basename($_SERVER['PHP_SELF']) == 'admin_pinjam.php' ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Peminjaman</a>
            <a href="buku.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= basename($_SERVER['PHP_SELF']) == 'buku.php' ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Katalog Master</a>
        <?php else: ?>
            <a href="dashboard.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Dashboard</a>
            <a href="pinjam.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= basename($_SERVER['PHP_SELF']) == 'pinjam.php' ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Pinjam Buku</a>
            <a href="riwayat.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Riwayat</a>
            <a href="daftar_buku.php" class="btn btn-ghost btn-sm rounded-xl px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] <?= basename($_SERVER['PHP_SELF']) == 'daftar_buku.php' ? 'nav-active' : 'text-secondary-theme font-medium' ?>">Katalog</a>
        <?php endif; ?>
    </div>

    <!-- Right Section Actions -->
    <div class="flex items-center gap-2">
        <!-- Theme Toggle Switcher -->
        <button id="themeToggleBtn" onclick="toggleTheme()" class="btn btn-ghost btn-circle btn-sm hover:bg-[rgba(177,116,87,0.08)] text-nav-icon" title="Ubah Tema">
            <i data-lucide="moon" class="w-4 h-4 text-secondary-theme" id="themeBtnIcon"></i>
        </button>

        <!-- Role Badge (Desktop) -->
        <div class="hidden md:flex items-center gap-2 mr-1">
            <span class="badge <?= $role === 'admin' ? 'badge-glass-admin' : 'badge-glass-user' ?> uppercase px-2.5 py-2"><?= strtoupper($role) ?></span>
        </div>

        <!-- Sleek Profile & Mobile Dropdown -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle border border-[var(--glass-border)] hover:bg-[var(--glass-bg)] bg-[var(--glass-bg)] shadow-sm">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-primary-theme">
                    <i data-lucide="user" class="w-4 h-4 mx-auto"></i>
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[100] p-3 shadow-2xl glass-dropdown-card rounded-2xl w-56 border border-[var(--glass-border)] space-y-1.5">
                <li class="menu-title px-2.5 py-1.5">
                    <p class="text-xs font-bold text-primary-theme">Halo, <?= htmlspecialchars(ucfirst($nama)) ?></p>
                    <p class="text-[9px] text-secondary-theme opacity-80 uppercase font-semibold tracking-wider opacity-85"><?= strtoupper($role) ?></p>
                </li>
                
                <div class="border-t border-[var(--glass-border)] my-1 lg:hidden"></div>
                
                <!-- Mobile Navigation Quicklinks (lg:hidden) -->
                <?php if($role == 'admin'): ?>
                    <li class="lg:hidden"><a href="dashboard.php" class="rounded-xl py-2 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="layout-dashboard" class="w-3.5 h-3.5 mr-1.5"></i> Dashboard</a></li>
                    <li class="lg:hidden"><a href="kelola_buku.php" class="rounded-xl py-2 <?= in_array(basename($_SERVER['PHP_SELF']), ['kelola_buku.php', 'tambah_buku.php', 'edit_buku.php']) ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="settings" class="w-3.5 h-3.5 mr-1.5"></i> Kelola Buku</a></li>
                    <li class="lg:hidden"><a href="admin_pinjam.php" class="rounded-xl py-2 <?= basename($_SERVER['PHP_SELF']) == 'admin_pinjam.php' ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="clipboard-list" class="w-3.5 h-3.5 mr-1.5"></i> Peminjaman</a></li>
                    <li class="lg:hidden"><a href="buku.php" class="rounded-xl py-2 <?= basename($_SERVER['PHP_SELF']) == 'buku.php' ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="database" class="w-3.5 h-3.5 mr-1.5"></i> Katalog Master</a></li>
                <?php else: ?>
                    <li class="lg:hidden"><a href="dashboard.php" class="rounded-xl py-2 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="layout-dashboard" class="w-3.5 h-3.5 mr-1.5"></i> Dashboard</a></li>
                    <li class="lg:hidden"><a href="pinjam.php" class="rounded-xl py-2 <?= basename($_SERVER['PHP_SELF']) == 'pinjam.php' ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="book-open" class="w-3.5 h-3.5 mr-1.5"></i> Pinjam Buku</a></li>
                    <li class="lg:hidden"><a href="riwayat.php" class="rounded-xl py-2 <?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="history" class="w-3.5 h-3.5 mr-1.5"></i> Riwayat</a></li>
                    <li class="lg:hidden"><a href="daftar_buku.php" class="rounded-xl py-2 <?= basename($_SERVER['PHP_SELF']) == 'daftar_buku.php' ? 'bg-[rgba(177,116,87,0.1)] text-[var(--accent-primary)] font-bold border border-[rgba(177,116,87,0.15)]' : 'text-secondary-theme' ?>"><i data-lucide="library" class="w-3.5 h-3.5 mr-1.5"></i> Katalog</a></li>
                <?php endif; ?>

                <div class="border-t border-[var(--glass-border)] my-1"></div>

                <!-- Keluar Action -->
                <li>
                    <button onclick="bukaModalLogout()" class="rounded-xl text-[var(--accent-danger)] hover:bg-[var(--accent-danger-bg)] gap-1.5 py-2 font-semibold">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        Keluar
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Inline script for dynamic Sun/Moon toggle and state updates -->
<script>
    function updateThemeToggleUI(theme) {
        const iconEl = document.getElementById('themeBtnIcon');
        if (!iconEl) return;
        if (theme === 'light') {
            iconEl.setAttribute('data-lucide', 'sun');
            iconEl.style.color = '#B17457'; // Desert Clay Accent
        } else {
            iconEl.setAttribute('data-lucide', 'moon');
            iconEl.style.color = '';
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeToggleUI(newTheme);
    }

    window.addEventListener('DOMContentLoaded', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        updateThemeToggleUI(currentTheme);
    });
</script>
