<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($db, "
    SELECT j.*, g.nama_guru, k.nama_kelas, m.nama_mapel
    FROM jadwal j
    LEFT JOIN guru g ON j.id_guru = g.id
    LEFT JOIN kelas k ON j.id_kelas = k.id
    LEFT JOIN mapel m ON j.id_mapel = m.id
    ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_ke ASC
");

$bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$tgl = date('j') . ' ' . $bulan[(int)date('m')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Jadwal - SMAS MKGR Sepatan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; margin: 0; background: #fff; color: #000; }

        .no-print { padding: 10px; text-align: center; background: #eee; border-bottom: 1px solid #ccc; }
        .no-print button, .no-print a { padding: 8px 20px; margin: 0 4px; font-size: 11pt; cursor: pointer; text-decoration: none; }
        .no-print button { background: #1a3a5c; color: #fff; border: none; }
        .no-print a { background: #888; color: #fff; }

        .halaman { max-width: 760px; margin: 20px auto; padding: 28px 36px; background: #fff; }

        /* Kop Surat */
        .kop { display: flex; align-items: center; gap: 14px; padding-bottom: 10px; border-bottom: 3px solid #000; margin-bottom: 16px; }
        .kop img { width: 64px; height: 64px; object-fit: contain; }
        .kop-tengah { text-align: center; flex: 1; }
        .kop-tengah .yayasan { font-size: 10pt; }
        .kop-tengah .sekolah { font-size: 15pt; font-weight: bold; text-transform: uppercase; }
        .kop-tengah .info { font-size: 8.5pt; margin-top: 3px; line-height: 1.5; }

        /* Judul */
        .judul { text-align: center; margin: 0 0 14px; }
        .judul h2 { font-size: 12pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin: 0 0 2px; }
        .judul p { font-size: 9pt; margin: 0; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        th { background: #ddd; font-weight: bold; text-align: center; border: 1px solid #000; padding: 4px 6px; }
        td { border: 1px solid #000; padding: 3px 6px; text-align: center; vertical-align: middle; }
        td.kiri { text-align: left; }
        tr:nth-child(even) td { background: #f9f9f9; }

        /* Tanda Tangan */
        .ttd { margin-top: 24px; text-align: right; }
        .ttd-inner { display: inline-block; text-align: center; width: 200px; font-size: 10pt; }
        .ttd-inner p { margin: 0 0 4px; }
        .ttd-inner .jabatan { margin-bottom: 56px; }
        .ttd-inner .garis { border-top: 1px solid #000; padding-top: 2px; font-weight: bold; }
        .ttd-inner .nip { font-size: 8.5pt; margin-top: 2px; }

        @media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
            .no-print { display: none !important; }
            .halaman { margin: 0; padding: 15px 20px; max-width: 100%; }
            @page { size: A4 portrait; margin: 1.2cm 1.5cm; }
            table { page-break-inside: auto; border-collapse: collapse !important; width: 100% !important; }
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
            th { background-color: #ddd !important; border: 1px solid #000 !important; outline: 1px solid #000; }
            td { border: 1px solid #000 !important; outline: 1px solid #000; }
            tr:nth-child(even) td { background-color: #f9f9f9 !important; }
            .kop { border-bottom: 3px solid #000 !important; }
            .ttd-inner .garis { border-top: 1px solid #000 !important; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">🖨 Cetak</button>
    <a href="jadwal.php">← Kembali</a>
</div>

<div class="halaman">

    <!-- Kop Surat -->
    <div class="kop">
        <img src="images/logosekolah.png" alt="Logo">
        <div class="kop-tengah">
            <div class="yayasan">YAYASAN PENDIDIKAN MKGR</div>
            <div class="sekolah">SMAS MKGR Sepatan</div>
            <div class="info">
                Jl. Raya Pakuhaji No.1, Sarakan, Kec. Sepatan, Kab. Tangerang, Banten 15520<br>
                Telp: 081211161139 &nbsp;|&nbsp; smasmkgrsepatan.sch.id
            </div>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="judul">
        <h2>Jadwal Mata Pelajaran</h2>
        <p>Tahun Pelajaran 2025 / 2026</p>
    </div>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th style="width:32px">No</th>
                <th style="width:58px">Hari</th>
                <th style="width:52px">Jam Ke</th>
                <th>Mata Pelajaran</th>
                <th style="width:58px">Kelas</th>
                <th style="width:160px">Guru Pengampu</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($query) > 0):
                while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['hari']) ?></td>
                <td>Ke-<?= $row['jam_ke'] ?></td>
                <td class="kiri"><?= htmlspecialchars($row['nama_mapel'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                <td class="kiri"><?= htmlspecialchars($row['nama_guru'] ?? '-') ?></td>
            </tr>
            <?php endwhile;
            else: ?>
            <tr><td colspan="6" style="text-align:center;padding:20px">Belum ada data jadwal.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="ttd">
        <div class="ttd-inner">
            <p>Tangerang, <?= $tgl ?></p>
            <p class="jabatan">Kepala Sekolah,</p>
            <p class="garis">( _______________________ )</p>
            <p class="nip">NIP. _____________________</p>
        </div>
    </div>

</div>
</body>
</html>
