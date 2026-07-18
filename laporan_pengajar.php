<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if (!in_array($_SESSION['role'], ['admin', 'kepala_sekolah'])) {
    header("Location: index.php");
    exit;
}

// Filter
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($db, $_GET['status']) : '';
$search = isset($_GET['q']) ? mysqli_real_escape_string($db, trim($_GET['q'])) : '';

$where = [];
if ($status_filter) $where[] = "g.status_tugas = '$status_filter'";
if ($search) $where[] = "(g.nama_guru LIKE '%$search%' OR g.nip LIKE '%$search%' OR g.kode_guru LIKE '%$search%')";
$whereSQL = count($where) ? "WHERE " . implode(" AND ", $where) : "";

// Query Laporan Pengajar (Beban Mengajar)
$query = mysqli_query($db, "
    SELECT g.id, g.kode_guru, g.nip, g.nama_guru, g.kontak, g.status_tugas, 
           COUNT(j.id) as total_jam,
           GROUP_CONCAT(DISTINCT m.nama_mapel SEPARATOR ', ') as daftar_mapel
    FROM guru g
    LEFT JOIN jadwal j ON g.id = j.id_guru
    LEFT JOIN mapel m ON j.id_mapel = m.id
    $whereSQL
    GROUP BY g.id
    ORDER BY CAST(g.kode_guru AS UNSIGNED) ASC
");

// Stats ringkas
$total_guru = mysqli_num_rows($query);
$stat_aktif = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as c FROM guru WHERE status_tugas = 'Aktif Mengajar'"))['c'];
$stat_cuti = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as c FROM guru WHERE status_tugas != 'Aktif Mengajar'"))['c'];
$stat_jadwal = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as c FROM jadwal WHERE id_guru IS NOT NULL"))['c'];
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <link rel="icon" type="image/png" href="images/logosekolah.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengajar - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media screen {
            .print-only { display: none !important; }
        }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .print-full { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .border-collapse { width: 100% !important; }
            @page { margin: 1cm; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>

            <div class="p-6 md:p-10 space-y-6 max-w-[1600px] mx-auto print-full">

                <!-- Kop Surat (Print Only) -->
                <div class="print-only text-center border-b-[3px] border-black pb-4 mb-6">
                    <h1 class="text-xl font-bold uppercase tracking-wider text-black">YAYASAN PENDIDIKAN MKGR</h1>
                    <h2 class="text-2xl font-extrabold uppercase tracking-widest text-black mt-1">SMAS MKGR SEPATAN</h2>
                    <p class="text-sm text-black mt-2 font-medium">Jl. Raya Pakuhaji No.1, Sarakan, Kec. Sepatan, Kabupaten Tangerang, Banten 15520</p>
                    <p class="text-sm text-black mt-0.5">Telp: 081211161139 | Website: https://smasmkgrsepatan.sch.id/</p>
                    <p class="text-xs text-black mt-0.5">VHVP+H7 Sarakan, Kabupaten Tangerang, Banten</p>
                </div>
                
                <!-- Judul Laporan (Print Only) -->
                <div class="print-only text-center mb-6">
                    <h3 class="text-lg font-bold text-black uppercase underline underline-offset-4">Laporan Beban Mengajar Guru</h3>
                    <p class="text-sm mt-1">Tahun Pelajaran 2025 / 2026</p>
                </div>

                <!-- Header (Screen Only) -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm no-print">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Laporan Pengajar</h2>
                        <p class="text-xs text-slate-400 mt-1">Rekap beban mengajar dan daftar guru di SMAS MKGR Sepatan.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Laporan
                        </button>
                    </div>
                </div>

                <!-- Kartu Statistik (Screen Only) -->
                <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;" class="no-print">
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Guru</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $total_guru ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">di data laporan</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aktif Mengajar</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $stat_aktif ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">tenaga pengajar</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Cuti / Non-Aktif</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $stat_cuti ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">guru tidak aktif</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Jam</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $stat_jadwal ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">sesi terisi guru</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 print-full">
                    
                    <!-- Search & Filter (Screen Only) -->
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl no-print">
                        <form method="GET" action="" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
                            <div style="flex:1; min-width:250px;">
                                <div style="position:relative;">
                                    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama guru, NIP, atau kode..." style="width:100%; padding:0 16px 0 38px; height:38px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; font-size:12px; outline:none; font-family:inherit; color:#334155;" onfocus="this.style.borderColor='#94a3b8'" onblur="this.style.borderColor='#e2e8f0'">
                                    <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
                                </div>
                            </div>
                            <div>
                                <select name="status" style="padding:0 10px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; font-size:12px; outline:none; font-family:inherit; color:#334155; font-weight:500; height:38px; box-sizing:border-box; cursor:pointer;" onfocus="this.style.borderColor='#1e293b'" onblur="this.style.borderColor='#e2e8f0'">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif Mengajar" <?= $status_filter == 'Aktif Mengajar' ? 'selected' : '' ?>>Aktif Mengajar</option>
                                    <option value="Cuti" <?= $status_filter == 'Cuti' ? 'selected' : '' ?>>Cuti</option>
                                </select>
                            </div>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <button type="submit" style="padding:0 18px; height:38px; background:#0f172a; color:#fff; font-size:12px; font-weight:600; border-radius:10px; border:none; cursor:pointer; font-family:inherit;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">Cari</button>
                                <?php if($status_filter || $search): ?>
                                    <a href="laporan_pengajar.php" style="padding:0 16px; height:38px; display:inline-flex; align-items:center; background:#f1f5f9; color:#475569; font-size:12px; font-weight:600; border-radius:10px; text-decoration:none; font-family:inherit;">Reset</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto print-full">
                        <table class="w-full text-left border-collapse print:text-black">
                            <thead>
                                <tr class="bg-slate-50 print:bg-transparent border-b border-slate-100 print:border-black">
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-16 text-center print:border print:border-black print:text-black print:text-[10px]">No</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider print:border print:border-black print:text-black print:text-[10px]">Guru</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider print:border print:border-black print:text-black print:text-[10px]">NIP</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider print:border print:border-black print:text-black print:text-[10px]">Kontak</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider print:border print:border-black print:text-black print:text-[10px]">Mengajar Mapel</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center print:border print:border-black print:text-black print:text-[10px]">Beban Jam</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center print:border print:border-black print:text-black print:text-[10px]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query) > 0):
                                    while ($row = mysqli_fetch_assoc($query)):
                                ?>
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="p-4 text-center text-slate-400 print:border print:border-black print:text-black print:py-2"><?= $no++ ?></td>
                                    <td class="p-4 print:border print:border-black print:py-2">
                                        <div class="font-bold text-slate-900 print:text-black"><?= htmlspecialchars($row['nama_guru'] ?? '-') ?></div>
                                    </td>
                                    <td class="p-4 text-slate-600 print:border print:border-black print:text-black print:py-2">
                                        <?= htmlspecialchars($row['nip'] && $row['nip'] !== '' ? $row['nip'] : '-') ?>
                                    </td>
                                    <td class="p-4 text-slate-500 print:border print:border-black print:text-black print:py-2"><?= htmlspecialchars($row['kontak'] ?? '-') ?></td>
                                    <td class="p-4 print:border print:border-black print:text-black print:py-2">
                                        <div class="font-semibold text-slate-800 print:text-black" style="max-width: 200px; white-space: normal;">
                                            <?= htmlspecialchars($row['daftar_mapel'] ?? '-') ?>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center print:border print:border-black print:text-black print:py-2">
                                        <span class="font-extrabold text-slate-900 print:text-black"><?= $row['total_jam'] ?></span> <span class="text-slate-400 print:text-black">Jam</span>
                                    </td>
                                    <td class="p-4 text-center print:border print:border-black print:text-black print:py-2">
                                        <?php if ($row['status_tugas'] == 'Aktif Mengajar'): ?>
                                            <span class="font-bold text-slate-700 print:text-black">Aktif</span>
                                        <?php else: ?>
                                            <span class="font-bold text-slate-700 print:text-black">Cuti</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-slate-400 print:border print:border-black print:text-black">Tidak ada data guru yang sesuai filter.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Tanda Tangan (Print Only) -->
                <div class="print-only mt-12 text-black" style="page-break-inside: avoid;">
                    <div style="float:right; width:300px; text-align:center;">
                        <?php
                        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                        $tgl_sekarang = date('d') . ' ' . $bulan[(int)date('m')] . ' ' . date('Y');
                        ?>
                        <p class="mb-1 text-sm font-medium">Tangerang, <?= $tgl_sekarang ?></p>
                        <p class="mb-20 text-sm font-bold">Kepala Sekolah,</p>
                        <p class="font-bold border-b border-black inline-block pb-0.5 mb-1 text-sm">(........................................................)</p>
                        <p class="text-xs">NIP. ....................................</p>
                    </div>
                    <div style="clear:both;"></div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
