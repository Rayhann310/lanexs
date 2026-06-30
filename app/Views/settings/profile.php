<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Profil Saya</h2>
        <p class="text-slate-500 mt-1">Kelola informasi pribadi dan keamanan akun Anda</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-center shadow-sm max-w-3xl">
            <i class="bi bi-check-circle-fill mr-3"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-center shadow-sm max-w-3xl">
            <i class="bi bi-exclamation-triangle-fill mr-3"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-3xl">
        <div class="p-6 sm:p-10">
            <div class="flex items-center mb-8">
                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary text-3xl font-bold mr-6">
                    <?= strtoupper(substr($user['fullname'], 0, 1)) ?>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($user['fullname']) ?></h3>
                    <p class="text-slate-500">@<?= htmlspecialchars($user['username']) ?></p>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/settings/profile" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">No. Handphone / WhatsApp</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email Utama</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>

                    <div class="md:col-span-2 border-t border-slate-100 pt-6 mt-2">
                        <h4 class="font-bold text-slate-700 mb-4">Keamanan Akun</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            <p class="text-xs text-slate-500 mt-1">Mengisi password ini akan mengganti password lama Anda untuk login berikutnya.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-xl font-medium transition shadow-sm shadow-primary/20 flex items-center">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php \App\Helpers\View::endSection(); ?>
