<?php
// tracking/index.php
$pageTitle = APP_NAME . ' - Tracking Paket';
$pageDescription = 'Lacak status dan posisi paket Anda secara real-time bersama ' . APP_NAME . '.';
ob_start();
?>

<!-- Header Section -->
<div class="pt-32 pb-16 bg-gradient-to-br from-slate-900 to-slate-800 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="px-4 py-1.5 rounded-full bg-primary/20 text-primary border border-primary/30 text-sm font-semibold tracking-wide uppercase mb-6 inline-block">
            Tracking & Manifest
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6">
            Lacak Status Paket Anda
        </h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto">
            Pantau pergerakan pengiriman barang Anda secara real-time dengan akurat.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 pb-24">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-100 dark:border-slate-700 overflow-hidden transition-colors">
        <div class="p-8 md:p-12">
            <div class="flex items-center justify-center mb-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary/20 to-secondary/20 dark:from-primary/30 dark:to-secondary/30 flex items-center justify-center text-primary text-4xl transition-colors shadow-inner">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-center text-slate-800 dark:text-white mb-3 transition-colors">Masukkan Nomor Resi</h3>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-8 transition-colors">Nomor resi LANEXS (LNX) dapat ditemukan pada bukti pengiriman Anda.</p>
            
            <form action="<?= BASE_URL ?>/tracking" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="bi bi-search text-slate-400 dark:text-slate-500 text-lg"></i>
                    </div>
                    <input type="text" name="resi" placeholder="Contoh: LNX-12345..." required 
                           class="w-full pl-12 pr-5 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all text-slate-800 dark:text-white font-medium text-lg tracking-wide placeholder-slate-400 dark:placeholder-slate-500">
                </div>
                <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all shadow-lg shadow-primary/30 hover:shadow-xl hover:-translate-y-1 whitespace-nowrap flex items-center justify-center">
                    Lacak Sekarang <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
        <div class="bg-slate-50 dark:bg-slate-900/50 p-6 border-t border-slate-100 dark:border-slate-700 text-center transition-colors">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">
                <i class="bi bi-info-circle mr-1.5 text-primary"></i> Data tracking diupdate secara real-time.
            </p>
        </div>
    </div>
</div>

<?php
$slot = ob_get_clean();
$navbarWhite = true; // Use white navbar text style since the hero background is dark
require __DIR__ . '/../landing/layout.php';
?>
