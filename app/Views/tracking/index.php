<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8 transition-colors">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight transition-colors">Tracking & Manifest</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1 transition-colors">Lacak status dan posisi paket Anda secara real-time</p>
    </div>

    <div class="max-w-2xl mx-auto mt-12 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden transition-colors">
        <div class="p-8">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-primary text-3xl transition-colors">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-slate-800 dark:text-white mb-2 transition-colors">Lacak Paket</h3>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-8 transition-colors">Masukkan nomor resi pengiriman LANEXS di bawah ini</p>
            
            <form action="<?= BASE_URL ?>/tracking" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="bi bi-search text-slate-400 dark:text-slate-500"></i>
                    </div>
                    <input type="text" name="resi" placeholder="Contoh: LNX-..." required class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-slate-800 dark:text-white font-medium tracking-wide placeholder-slate-400 dark:placeholder-slate-500">
                </div>
                <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-3.5 rounded-xl font-bold transition shadow-sm whitespace-nowrap">
                    Lacak Sekarang
                </button>
            </form>
        </div>
        <div class="bg-slate-50 dark:bg-slate-700/30 p-6 border-t border-slate-100 dark:border-slate-700 text-center transition-colors">
            <p class="text-sm text-slate-500 dark:text-slate-400 transition-colors"><i class="bi bi-info-circle mr-1"></i> Data tracking diupdate secara real-time.</p>
        </div>
    </div>
</div>
<?php \App\Helpers\View::endSection(); ?>
