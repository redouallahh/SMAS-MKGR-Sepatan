<?php
$role = $_SESSION['role'] ?? 'admin';
$page = basename($_SERVER['PHP_SELF']);

function isActive($page_name, $current_page) {
    if (is_array($page_name)) {
        return in_array($current_page, $page_name) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-900/50';
    }
    return ($current_page == $page_name) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-900/50';
}
?>
<div class="flex flex-col h-screen w-64 bg-slate-950 text-slate-300 border-r border-slate-900 shrink-0">
    
    <div class="px-6 py-6 border-b border-slate-900/80">
        <div class="flex items-center gap-3.5">
           
            <div>
                <h1 class="text-sm font-black text-white tracking-wider leading-none uppercase">SMAS MKGR</h1>
                <p class="text-[11px] text-slate-500 font-medium mt-1.5">Sepatan, Kab. Tangerang</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
        
        <!-- DASHBOARD -->
        <div class="space-y-1">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all border border-slate-800/40 <?= isActive('index.php', $page) ?>">
                <svg class="w-4 h-4 <?= $page == 'index.php' ? 'text-white' : 'text-indigo-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                </svg>
                Dashboard
            </a>
        </div>

        <?php if ($role == 'admin'): ?>
        <!-- DATA MASTER (ADMIN) -->
        <div class="space-y-1">
            <span class="px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest block mb-2">Data Master</span>
            
            <a href="guru.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all group <?= isActive(['guru.php', 'tambah_guru.php', 'edit_guru.php'], $page) ?>">
                <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Data Guru
            </a>

            <a href="kepsek.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all group <?= isActive(['kepsek.php', 'tambah_kepsek.php', 'edit_kepsek.php'], $page) ?>">
                <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Data Kepala Sekolah
            </a>

            <a href="kelas.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all group <?= isActive(['kelas.php', 'tambah_kelas.php', 'edit_kelas.php'], $page) ?>">
                <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Data Kelas
            </a>

            <a href="mapel.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all group <?= isActive(['mapel.php', 'tambah_mapel.php', 'edit_mapel.php'], $page) ?>">
                <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Mata Pelajaran
            </a>
        </div>
        <?php endif; ?>

        <!-- AKADEMIK / JADWAL -->
        <div class="space-y-1">
            <span class="px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest block mb-2">Akademik</span>
            
            <?php if ($role == 'admin'): ?>
            <a href="jadwal.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all group <?= isActive(['jadwal.php', 'tambah_jadwal.php', 'edit_jadwal.php'], $page) ?>">
                <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Atur Jadwal
            </a>
            <?php endif; ?>

            <?php if (in_array($role, ['guru', 'kepala_sekolah'])): ?>
            <a href="jadwal.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all group <?= isActive(['jadwal.php'], $page) ?>">
                <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Melihat Jadwal
            </a>
            <?php endif; ?>

            <?php if ($role == 'kepala_sekolah'): ?>
            <a href="laporan_pengajar.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all group <?= isActive(['laporan_pengajar.php'], $page) ?>">
                <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Laporan Pengajar
            </a>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- SweetAlert2 (via NPM) -->
<script src="js/sweetalert2.all.min.js"></script>