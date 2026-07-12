<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

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
<html lang="id">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏫</text></svg>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jadwal - SMAS MKGR Sepatan</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 20px; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h1>Jadwal Mata Pelajaran</h1>
        <p>SMAS MKGR Sepatan, Kab. Tangerang</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Hari</th>
                <th>Jam Ke</th>
                <th>Mata Pelajaran</th>
                <th>Ruang Kelas</th>
                <th>Guru Pengampu</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['hari']; ?></td>
                        <td>Jam Ke-<?= $row['jam_ke']; ?></td>
                        <td><?= $row['nama_mapel'] ?? '-'; ?></td>
                        <td><?= $row['nama_kelas'] ?? '-'; ?></td>
                        <td><?= $row['nama_guru'] ?? '-'; ?></td>
                    </tr>
                <?php endwhile; 
            } else { ?>
                <tr>
                    <td colspan="6">Belum ada sesi jadwal yang terdaftar.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
