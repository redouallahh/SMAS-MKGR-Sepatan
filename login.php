<?php
session_start();
require_once 'config/database.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($db, trim($_POST['username']));
    $password_input = $_POST['password'];

    if (empty($username) || empty($password_input)) {
        $error = 'Username dan password wajib diisi!';
    } else {
        // Enkripsi password input dengan MD5 agar sama dengan format di database
        $password_hash = md5($password_input);

        // Cari user di database berdasarkan username
        $query = mysqli_query($db, "SELECT * FROM user WHERE username = '$username'");

        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);

            // Cek password hash atau plain text (karena request user: don't use hash)
            if ($data['password'] === $password_hash || $data['password'] === $password_input) {
                
                $id_user = $data['id'];
                $role_user = $data['role'];
                $is_cuti = false;

                // Cek status tugas Guru
                if ($role_user === 'guru') {
                    $cek_guru = mysqli_query($db, "SELECT status_tugas FROM guru WHERE id_user = '$id_user'");
                    if ($r = mysqli_fetch_assoc($cek_guru)) {
                        if ($r['status_tugas'] === 'Cuti') {
                            $is_cuti = true;
                        }
                    }
                } 
                // Cek status tugas Kepala Sekolah
                elseif ($role_user === 'kepala_sekolah') {
                    $cek_kepsek = mysqli_query($db, "SELECT status_tugas FROM kepalasekolah WHERE id_user = '$id_user'");
                    if ($r = mysqli_fetch_assoc($cek_kepsek)) {
                        if ($r['status_tugas'] === 'Cuti') {
                            $is_cuti = true;
                        }
                    }
                }

                if ($is_cuti) {
                    $error = 'Akun Anda dinonaktifkan sementara (Status: Cuti). Silakan hubungi Administrator.';
                } else {
                    // Set session
                    $_SESSION['login'] = true;
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['role'] = $data['role'];

                    header("Location: index.php");
                    exit;
                }
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏫</text></svg>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMAS MKGR Sepatan</title>
    <!-- Tailwind CSS CDN -->
    <link href="css/output.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-4 relative overflow-hidden">

    <!-- Elemen Dekoratif Background (Grid & Glow) -->
    <div class="absolute inset-0 opacity-[0.03] bg-[linear-gradient(to_right,#ffffff_1px,transparent_1px),linear-gradient(to_bottom,#ffffff_1px,transparent_1px)] bg-[size:32px_32px]"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Container Utama (Card Login Tengah) -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 md:p-10 z-10 border border-slate-100/80">

        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="mt-5 flex justify-center">
                <div class="p-3.5">
                    <img src="images/logosekolah.png" alt="Logo SMAS MKGR" class="h-16 w-16 object-contain">
                </div>
            </div>

            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Login</h2>
            <p class="mt-2 text-xs text-slate-400 max-w-[280px] mx-auto leading-relaxed">
                Silakan otentikasi akun Anda untuk mengelola instrumen jadwal pengajaran terintegrasi.
            </p>

            <!-- LOGO DI BAWAH PORTAL LOGIN (Dikasih base putih biar gak bolong) -->
            
        </div>

        <!-- Notifikasi Error State -->
        <?php if (!empty($error)): ?>
            <div class="mb-5 p-4 bg-red-50 border border-red-100 text-red-900 rounded-2xl flex items-start gap-3 text-xs font-medium shadow-sm">
                <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="leading-relaxed"><?= $error; ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Eksekusi -->
        <form action="login.php" method="POST" class="space-y-5">

            <!-- Input Username -->
            <div>
                <label for="username" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Username / NIP</label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 z-10">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input id="username" name="username" type="text" pattern="[a-zA-Z0-9_]+" oninput="this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '')" title="Username hanya boleh berisi huruf, angka, dan underscore (tanpa spasi)" required autocomplete="off" placeholder="Masukkan username"
                        class="block w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5 transition-all outline-none font-medium">
                </div>
            </div>

            <!-- Input Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 z-10">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password" name="password" type="password" required placeholder="••••••••"
                        class="block w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5 transition-all outline-none font-medium">
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button type="submit" class="group w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl focus:ring-4 focus:ring-slate-900/20 active:scale-[0.98] transition-all shadow-md tracking-wide flex items-center justify-center gap-2">
                    Masuk ke Dashboard
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 border-t border-slate-100 pt-5">
            <p class="text-[11px] text-slate-400 text-center font-medium">&copy; SMAS MKGR Sepatan. All rights reserved.</p>
        </div>
    </div>

    <script>
    // Global Password Toggle (Ponytail)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="password"]').forEach(input => {
            // Wrap input inside a relative div
            const wrapper = document.createElement('div');
            wrapper.className = 'relative flex items-center w-full';
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);

            // Create toggle button
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'absolute right-3 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors z-10';
            const eyeOpen = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
            const eyeClosed = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>';
            toggleBtn.innerHTML = eyeOpen;
            
            // Toggle logic
            toggleBtn.addEventListener('click', () => {
                if (input.type === 'password') {
                    input.type = 'text';
                    toggleBtn.innerHTML = eyeClosed;
                } else {
                    input.type = 'password';
                    toggleBtn.innerHTML = eyeOpen;
                }
            });

            wrapper.appendChild(toggleBtn);
            input.style.paddingRight = '2.5rem'; // Space for the icon
        });
    });
    </script>
</body>

</html>