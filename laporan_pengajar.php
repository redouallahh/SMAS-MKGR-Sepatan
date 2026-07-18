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
$filter_hari = isset($_GET['hari']) ? mysqli_real_escape_string($db, $_GET['hari']) : '';
$filter_kelas = isset($_GET['kelas']) ? intval($_GET['kelas']) : 0;
$filter_guru = isset($_GET['guru']) ? intval($_GET['guru']) : 0;
$search = isset($_GET['q']) ? mysqli_real_escape_string($db, trim($_GET['q'])) : '';

$where = [];
if ($filter_hari) $where[] = "j.hari = '$filter_hari'";
if ($filter_kelas) $where[] = "j.id_kelas = $filter_kelas";
if ($filter_guru) $where[] = "j.id_guru = $filter_guru";
if ($search) $where[] = "(g.nama_guru LIKE '%$search%' OR m.nama_mapel LIKE '%$search%' OR k.nama_kelas LIKE '%$search%')";
$whereSQL = count($where) ? "WHERE " . implode(" AND ", $where) : "";

$query = mysqli_query($db, "
    SELECT j.id, j.hari, j.jam_ke, g.kode_guru, g.nama_guru, g.status_tugas,
           k.nama_kelas, m.kode_mapel, m.nama_mapel
    FROM jadwal j
    LEFT JOIN guru g ON j.id_guru = g.id
    LEFT JOIN kelas k ON j.id_kelas = k.id
    LEFT JOIN mapel m ON j.id_mapel = m.id
    $whereSQL
    ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat'), j.jam_ke ASC, k.nama_kelas ASC
");

// Data untuk dropdown filter
$all_kelas = mysqli_query($db, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$all_guru = mysqli_query($db, "SELECT id, kode_guru, nama_guru FROM guru ORDER BY kode_guru ASC");

// Stats ringkas
$total_jadwal = mysqli_num_rows($query);
$stat_guru = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as c FROM guru WHERE status_tugas = 'Aktif Mengajar'"))['c'];
$stat_kelas = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as c FROM kelas"))['c'];
$stat_mapel = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as c FROM mapel"))['c'];
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
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

            <div class="p-6 md:p-10 space-y-6 max-w-[1600px] mx-auto">

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
                    <h3 class="text-lg font-bold text-black uppercase underline underline-offset-4">Laporan Jadwal Mengajar Guru</h3>
                </div>

                <!-- Header (Screen Only) -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm no-print">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Laporan Pengajar</h2>
                        <p class="text-xs text-slate-400 mt-1">Rekap lengkap jadwal mengajar seluruh guru aktif di SMAS MKGR Sepatan.</p>
                    </div>
                    <div class="flex items-center gap-3 no-print">
                        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Laporan
                        </button>
                    </div>
                </div>

                <!-- Kartu Statistik -->
                <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Jadwal</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $total_jadwal ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">sesi terdaftar</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru Aktif</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $stat_guru ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">tenaga pengajar</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jumlah Kelas</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $stat_kelas ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">rombongan belajar</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-1"><?= $stat_mapel ?></p>
                        <p class="text-[11px] text-slate-400 mt-1">kurikulum aktif</p>
                    </div>
                </div>

                <!-- Filter -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm no-print">
                    <form method="GET" action="" style="display:flex; align-items:flex-end; gap:12px; flex-wrap:wrap;">
                        <div>
                            <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px;">Cari</div>
                            <div style="position:relative;">
                                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Guru / Mapel / Kelas..." style="padding:9px 12px 9px 34px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; font-size:12px; outline:none; width:200px; font-family:inherit; color:#1e293b; height:38px; box-sizing:border-box;" onfocus="this.style.borderColor='#1e293b';this.style.background='#fff'" onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
                                <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);pointer-events:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                        <div>
                            <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px;">Hari</div>
                            <select name="hari" style="padding:0 10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; font-size:12px; outline:none; font-family:inherit; color:#334155; font-weight:500; height:38px; box-sizing:border-box; cursor:pointer;" onfocus="this.style.borderColor='#1e293b'" onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">Semua Hari</option>
                                <?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $h): ?>
                                    <option value="<?= $h ?>" <?= $filter_hari == $h ? 'selected' : '' ?>><?= $h ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px;">Kelas</div>
                            <select name="kelas" style="padding:0 10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; font-size:12px; outline:none; font-family:inherit; color:#334155; font-weight:500; height:38px; box-sizing:border-box; cursor:pointer;" onfocus="this.style.borderColor='#1e293b'" onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">Semua Kelas</option>
                                <?php 
                                mysqli_data_seek($all_kelas, 0);
                                while($k = mysqli_fetch_assoc($all_kelas)): ?>
                                    <option value="<?= $k['id'] ?>" <?= $filter_kelas == $k['id'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px;">Guru</div>
                            <select name="guru" style="padding:0 10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; font-size:12px; outline:none; font-family:inherit; color:#334155; font-weight:500; height:38px; box-sizing:border-box; cursor:pointer;" onfocus="this.style.borderColor='#1e293b'" onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">Semua Guru</option>
                                <?php while($g = mysqli_fetch_assoc($all_guru)): ?>
                                    <option value="<?= $g['id'] ?>" <?= $filter_guru == $g['id'] ? 'selected' : '' ?>>[<?= $g['kode_guru'] ?>] <?= $g['nama_guru'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <button type="submit" style="padding:0 18px; height:38px; background:#0f172a; color:#fff; font-size:12px; font-weight:600; border-radius:10px; border:none; cursor:pointer; font-family:inherit;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">Terapkan</button>
                            <?php if($filter_hari || $filter_kelas || $filter_guru || $search): ?>
                                <a href="laporan_pengajar.php" style="padding:0 16px; height:38px; display:inline-flex; align-items:center; background:#f1f5f9; color:#475569; font-size:12px; font-weight:600; border-radius:10px; text-decoration:none; font-family:inherit;">Reset</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Tabel Laporan -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100">
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-12 text-center">No</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Hari</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Jam Ke</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Guru Pengampu</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Kelas</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query) > 0):
                                    while ($row = mysqli_fetch_assoc($query)):
                                ?>
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="p-4 text-center text-slate-400"><?= $no++ ?></td>
                                    <td class="p-4">
                                        <span class="font-bold text-slate-700"><?= $row['hari'] ?></span>
                                    </td>
                                    <td class="p-4 text-center font-bold text-slate-800">Jam <?= $row['jam_ke'] ?></td>
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-900"><?= htmlspecialchars($row['nama_guru'] ?? '-') ?></div>
                                    </td>
                                    <td class="p-4 text-center font-bold text-slate-800"><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-900"><?= htmlspecialchars($row['nama_mapel'] ?? '-') ?></div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if ($row['status_tugas'] == 'Aktif Mengajar'): ?>
                                            <span class="font-bold text-slate-700">Aktif</span>
                                        <?php else: ?>
                                            <span class="font-bold text-slate-700">Cuti</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="7" class="p-10 text-center text-slate-400">Tidak ada data jadwal yang sesuai filter.</td>
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
