<?php
$role = $_SESSION['role'] ?? 'admin';
$username = $_SESSION['username'] ?? 'User';

$role_display = 'Administrator';
if ($role == 'guru') $role_display = 'Tenaga Pengajar';
if ($role == 'kepala_sekolah') $role_display = 'Kepala Sekolah';
?>
<!-- Top Navigation Header -->
<div class="bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-3">
        <h2 class="text-sm font-bold text-slate-700 hidden sm:block">Sistem Manajemen Jadwal</h2>
    </div>

    <!-- Profile Area (Right) -->
    <div class="relative group">
        <button type="button" class="flex items-center gap-3 focus:outline-none cursor-pointer">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-extrabold text-slate-800 leading-none"><?= htmlspecialchars($username) ?></p>
                <p class="text-[10px] font-semibold text-slate-400 mt-1 uppercase tracking-wider"><?= $role_display ?></p>
            </div>
            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-white shadow-sm ring-2 ring-indigo-50">
                <?= strtoupper(substr($username, 0, 1)) ?>
            </div>
        </button>

        <!-- Dropdown Menu -->
        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
            <div class="p-2 space-y-1">
                <a href="pengaturan.php" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-indigo-600 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan Akun
                </a>
                <div class="h-px bg-slate-100 my-1"></div>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Global SweetAlert2 Interceptor for Native Confirm Links -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- TomSelect for AJAX-like Select UI -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[onclick^="return confirm"]').forEach(link => {
        const match = link.getAttribute('onclick').match(/confirm\('([^']+)'\)/);
        if (match) {
            const message = match[1];
            link.removeAttribute('onclick');
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                const isDelete = url.includes('hapus_');
                
                Swal.fire({
                    title: isDelete ? 'Peringatan Destruktif!' : 'Konfirmasi',
                    text: isDelete ? message + " (Perhatian: Data yang terhubung dengan ini seperti Jadwal mungkin ikut terhapus!)" : message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        }
    });

    // Initialize TomSelect on all select inputs
    document.querySelectorAll('select').forEach((el) => {
        new TomSelect(el, {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });

    // Global Password Toggle (Ponytail)
    document.querySelectorAll('input[type="password"]').forEach(input => {
        // Wrap input inside a relative div
        const wrapper = document.createElement('div');
        wrapper.className = 'relative flex items-center w-full';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'absolute right-3 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors';
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