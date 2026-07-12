<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$role = $_SESSION['role'] ?? 'admin';

// Query untuk menampilkan daftar jadwal
$query = mysqli_query($db, "
    SELECT j.*, g.nama_guru, k.nama_kelas, m.nama_mapel 
    FROM jadwal j
    LEFT JOIN guru g ON j.id_guru = g.id 
    LEFT JOIN kelas k ON j.id_kelas = k.id 
    LEFT JOIN mapel m ON j.id_mapel = m.id 
    ORDER BY j.hari ASC, j.jam_ke ASC
");
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <link rel="icon" type="image/png" href="images/logosekolah.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            
            <div class="p-6 md:p-10 space-y-6 max-w-[1600px] mx-auto">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Daftar Jadwal Pengajaran Aktif</h2>
                        <p class="text-xs text-slate-400 mt-1">Sistem Jadwal Terintegrasi SMAS MKGR Sepatan.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="cetak_jadwal.php" target="_blank" class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-xl transition-all shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak / Unduh
                        </a>
                        <?php if ($role == 'admin'): ?>
                        <a href="tambah_jadwal.php" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Jadwal
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/30 border-b border-slate-100">
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-12">No</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hari</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jam Ke</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ruang Kelas</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru Pengampu</th>
                                    <?php if ($role == 'admin'): ?>
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-24">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query) > 0) {
                                    while ($row = mysqli_fetch_assoc($query)): ?>
                                        <tr class="hover:bg-slate-50/40 transition-colors">
                                            <td class="p-4 text-center text-slate-400"><?= $no++; ?></td>
                                            <td class="p-4 font-bold text-slate-800"><?= $row['hari']; ?></td>
                                            <td class="p-4 font-semibold text-indigo-600">Jam Ke-<?= $row['jam_ke']; ?></td>
                                            <td class="p-4 font-bold text-slate-900"><?= $row['nama_mapel'] ?? '<span class="text-rose-500">Terhapus</span>'; ?></td>
                                            <td class="p-4 text-slate-600"><?= $row['nama_kelas'] ?? '<span class="text-rose-500">Terhapus</span>'; ?></td>
                                            <td class="p-4 text-slate-500"><?= $row['nama_guru'] ?? '<span class="text-rose-500">Terhapus</span>'; ?></td>
                                            
                                            <?php if ($role == 'admin'): ?>
                                            <td class="p-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="edit_jadwal.php?id=<?= $row['id']; ?>" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg inline-block transition-all" title="Edit Sesi">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <a href="hapus_jadwal.php?id=<?= $row['id']; ?>" onclick="return confirm('Batalkan sesi ini?')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition-all" title="Hapus Sesi">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </a>
                                                </div>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endwhile; 
                                } else { ?>
                                    <tr>
                                        <td colspan="<?= $role == 'admin' ? '7' : '6' ?>" class="p-8 text-center text-slate-400">Belum ada sesi jadwal yang terdaftar.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>