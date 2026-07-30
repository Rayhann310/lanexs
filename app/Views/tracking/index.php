<?php
// tracking/index.php
$pageTitle = APP_NAME . ' - Tracking Paket';
$pageDescription = 'Lacak status dan posisi paket Anda secara real-time bersama ' . APP_NAME . '.';
ob_start();
?>

<!-- Header Section -->
<div class="pt-32 pb-24 bg-white dark:bg-slate-900 relative overflow-hidden border-b border-slate-100 dark:border-slate-800">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.06]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    <!-- Accent Shapes Teal/Cyan -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-teal-400/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="fade-up">
        <div class="inline-flex items-center justify-center space-x-2 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 rounded-full px-4 py-1.5 mb-6 mx-auto">
            <i class="bi bi-box-seam text-teal-600"></i>
            <span class="text-teal-600 font-semibold text-sm tracking-wide uppercase">Tracking & Manifest</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-6">
            Lacak Status Paket Anda
        </h1>
        <p class="text-xl text-slate-500 dark:text-slate-400 font-light leading-relaxed max-w-2xl mx-auto">
            Pantau pergerakan pengiriman barang Anda secara real-time dengan akurat dan cepat.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 pb-24">
    <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-2xl shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-100 dark:border-slate-700 overflow-hidden transition-colors" data-aos="zoom-in">
        <div class="p-8 md:p-12">
            <h3 class="text-2xl font-bold text-center text-slate-800 dark:text-white mb-3 transition-colors">Masukkan Nomor Resi</h3>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-8 transition-colors">Nomor resi LANEXS (LNX) dapat ditemukan pada bukti pengiriman Anda.</p>
            
            <form action="<?= BASE_URL ?>/tracking" method="GET" class="flex flex-col sm:flex-row gap-4 group">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="bi bi-upc-scan text-slate-400 dark:text-slate-500 text-xl"></i>
                    </div>
                    <input type="text" name="resi" placeholder="Contoh: LNX-12345..." required 
                           class="w-full pl-12 pr-5 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all text-slate-800 dark:text-white font-medium text-lg tracking-wide placeholder-slate-400 dark:placeholder-slate-500 shadow-inner">
                </div>
                <button type="submit" class="bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all shadow-lg shadow-teal-500/30 hover:shadow-xl hover:-translate-y-1 whitespace-nowrap flex items-center justify-center shrink-0">
                    Lacak Sekarang <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
        <div class="bg-slate-50 dark:bg-slate-900/50 p-6 border-t border-slate-100 dark:border-slate-700 text-center transition-colors">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">
                <i class="bi bi-info-circle mr-1.5 text-teal-600"></i> Data tracking diupdate secara real-time.
            </p>
        </div>
    </div>
</div>

<?php
$slot = ob_get_clean();
$navbarWhite = false; // Background is white now
require __DIR__ . '/../landing/layout.php';
?>
