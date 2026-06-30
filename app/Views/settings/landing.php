<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Landing Page</h2>
        <p class="text-slate-500 mt-1">Konfigurasi tampilan Hero (Beranda Depan)</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-start shadow-sm max-w-4xl">
            <i class="bi bi-check-circle-fill mr-3 mt-0.5"></i>
            <div>
                <div class="font-bold"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-center shadow-sm max-w-4xl">
            <i class="bi bi-exclamation-triangle-fill mr-3"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-4xl">
        <!-- Form Pengaturan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                        <i class="bi bi-layout-text-window-reverse"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Section 1 (Hero)</h3>
                </div>
                
                <form action="<?= BASE_URL ?>/settings/landing" method="POST">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Utama (Title)</label>
                            <input type="text" name="landing_hero_title" value="<?= htmlspecialchars($settings['landing_hero_title']) ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                            <p class="text-xs text-slate-400 mt-2">Tips: Buat judul singkat namun kuat (contoh: Solusi Ekspedisi Terbaik).</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Subjudul (Subtitle)</label>
                            <textarea name="landing_hero_subtitle" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required><?= htmlspecialchars($settings['landing_hero_subtitle']) ?></textarea>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-primary/30 transition-all active:scale-95">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Info Widget -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10">
                    <i class="bi bi-lightbulb-fill text-9xl"></i>
                </div>
                <div class="relative z-10">
                    <h4 class="text-lg font-bold mb-2 flex items-center"><i class="bi bi-info-circle mr-2 text-blue-400"></i> Informasi</h4>
                    <p class="text-slate-300 text-sm leading-relaxed mb-4">Pengaturan ini akan langsung mengubah tampilan di halaman beranda (landing page).</p>
                    <a href="<?= BASE_URL ?>/" target="_blank" class="inline-flex items-center text-sm font-medium text-blue-400 hover:text-white transition-colors">
                        Lihat Landing Page <i class="bi bi-box-arrow-up-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \App\Helpers\View::endSection(); ?>
