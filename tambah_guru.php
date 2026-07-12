<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $nip = mysqli_real_escape_string($db, $_POST['nip']);
    $nama = mysqli_real_escape_string($db, $_POST['nama_guru']);
    $kontak = mysqli_real_escape_string($db, $_POST['kontak']);
    $status_tugas = mysqli_real_escape_string($db, $_POST['status_tugas']);
    $notes = mysqli_real_escape_string($db, $_POST['notes']);
    
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    
    // Cek username
    $cek = mysqli_query($db, "SELECT id FROM user WHERE username='$username'");
    if(mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username sudah terpakai!']);
        exit;
    }
    
    // Insert User
    $pass_md5 = md5($password);
    $insert_user = mysqli_query($db, "INSERT INTO user (username, password, role) VALUES ('$username', '$pass_md5', 'guru')");
    $id_user = mysqli_insert_id($db);
    
    // Insert Guru
    $insert = mysqli_query($db, "INSERT INTO guru (id_user, nip, nama_guru, kontak, status_tugas, notes) 
                            VALUES ('$id_user', '$nip', '$nama', '$kontak', '$status_tugas', '$notes')");
                            
    if ($insert) {
        echo json_encode(['status' => 'success', 'message' => 'Data guru berhasil ditambahkan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <link rel="icon" type="image/png" href="images/logosekolah.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            <div class="p-6 md:p-10 max-w-[800px] mx-auto space-y-6">

                <div class="flex items-center gap-3">
                    <a href="guru.php" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Registrasi Tenaga Pengajar</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Tambahkan biodata guru  ke dalam database master.</p>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="nip" pattern="\d{18}" maxlength="18" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="NIP harus 18 digit angka" placeholder="Masukkan 18 digit NIP..." required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama_guru" pattern="[a-zA-Z\s\.,\-']+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.,\-']/g, '')" title="Nama hanya boleh berisi huruf, spasi, titik, koma, tanda hubung, dan petik" placeholder="Contoh: Drs. Ahmad Subarjo, M.Pd." required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">No. Handphone / WhatsApp</label>
                            <input type="tel" name="kontak" pattern="\d{10,13}" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Nomor HP/WA harus 10-13 digit angka" placeholder="Contoh: 0812xxxxxxxx" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Status Tugas</label>
                            <select name="status_tugas" class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 font-semibold text-slate-700 transition-all">
                                <option value="Aktif Mengajar">Aktif Mengajar</option>
                                <option value="Cuti">Cuti / Non-Aktif Sementara</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Notes / Catatan (Opsional)</label>
                            <textarea name="notes" placeholder="Contoh: Cuti melahirkan s.d Desember / Sedang sakit..." class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium h-24 resize-none"></textarea>
                        </div>
                        <div class="pt-4 border-t border-slate-100 mt-4">
                            <h3 class="text-sm font-extrabold text-slate-800 mb-4">Akun Login Guru</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Username Login</label>
                                    <input type="text" name="username" pattern="[a-zA-Z0-9_]+" title="Hanya huruf, angka, dan underscore" required placeholder="Cth: budi_123" class="w-full p-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Password Login</label>
                                    <input type="password" name="password" required placeholder="Masukkan kata sandi" class="w-full p-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-4">
                            <a href="guru.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-all">Batal</a>
                            <button type="submit" name="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">Simpan Record Guru</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
        
        const formData = new FormData(this);
        formData.append('ajax', '1');
        
        fetch('', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => window.location = 'guru.php');
            } else {
                Swal.fire('Gagal!', data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(err => {
            Swal.fire('Gagal!', 'Terjadi kesalahan jaringan.', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
    </script>
</body>
</html>