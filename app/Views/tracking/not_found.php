<?php
$pageTitle = 'Resi Tidak Ditemukan - ' . APP_NAME;
$pageDescription = 'Maaf, resi pengiriman tidak ditemukan di sistem ' . APP_NAME . '.';

ob_start();
?>

<div class="pt-32 pb-24 bg-slate-50 dark:bg-slate-900 transition-colors min-h-[80vh] flex flex-col items-center justify-center">
    <div class="bg-white dark:bg-slate-800 p-10 md:p-14 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-100 dark:border-slate-700 max-w-lg w-full text-center transition-colors">
        <div class="w-28 h-28 bg-red-50 dark:bg-red-900/30 text-red-500 rounded-full flex items-center justify-center text-5xl mx-auto mb-8 shadow-inner transition-colors">
            <i class="bi bi-search"></i>
            <i class="bi bi-x absolute text-white dark:text-red-200 text-3xl translate-x-3 translate-y-3 drop-shadow-md"></i>
        </div>
        
        <h2 class="text-3xl font-extrabold mb-3 text-slate-900 dark:text-white transition-colors">Resi Tidak Ditemukan</h2>
        
        <p class="text-slate-500 dark:text-slate-400 mb-8 text-lg transition-colors">
            Maaf, paket dengan nomor resi <br>
            <strong class="text-slate-800 dark:text-white bg-slate-100 dark:bg-slate-700 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-600 inline-block mt-3 text-xl tracking-wider transition-colors"><?= htmlspecialchars($resi) ?></strong> <br>
            tidak dapat kami temukan di dalam sistem.
        </p>
        
        <div class="flex flex-col gap-3">
            <a href="<?= BASE_URL ?>/tracking" class="bg-primary hover:bg-secondary text-white px-6 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-primary/30 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
                <i class="bi bi-arrow-repeat mr-2"></i> Coba Resi Lain
            </a>
            <a href="<?= BASE_URL ?>/" class="bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3.5 rounded-2xl font-bold transition-all flex items-center justify-center">
                <i class="bi bi-house-door mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<?php
$slot = ob_get_clean();
require __DIR__ . '/../landing/layout.php';
?>
