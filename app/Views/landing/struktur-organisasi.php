<?php
// struktur-organisasi.php
$navbarWhite = false;
$meta = ['icon' => 'bi-diagram-3', 'label' => 'Profil Perusahaan', 'color' => 'from-emerald-600 to-emerald-800'];

$settingModel = new \App\Models\Setting();
$hero_bg = $settingModel->get('page_struktur_organisasi_img', 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop');
$org_chart = $settingModel->get('org_chart_img', '');

ob_start();
?>

<!-- Page Hero Banner -->
<section class="pt-32 pb-24 bg-white dark:bg-slate-900 relative overflow-hidden transition-colors border-b border-slate-200 dark:border-slate-800">
    <img src="<?= htmlspecialchars($hero_bg) ?>" class="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-20 mix-blend-overlay">
    <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent dark:from-slate-900 dark:via-slate-900/80 transition-colors"></div>
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Breadcrumb -->
        <nav class="flex items-center justify-center space-x-2 text-slate-500 dark:text-white/60 text-sm mb-8 font-medium" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-primary dark:hover:text-white transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-800 dark:text-white font-bold"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div data-aos="fade-up" class="max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center space-x-2 bg-primary/10 border border-primary/20 rounded-full px-4 py-1.5 mb-6">
                <i class="bi <?= $meta['icon'] ?> text-primary"></i>
                <span class="text-primary font-semibold text-sm tracking-widest uppercase"><?= htmlspecialchars($meta['label']) ?></span>
            </div>
            <h1 class="text-3xl md:text-5xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-4 transition-colors">
                <?= htmlspecialchars($page['title']) ?>
            </h1>
            <p class="text-base md:text-lg text-slate-600 dark:text-slate-300 font-light transition-colors">Pondasi kuat di balik layanan logistik prima kami.</p>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="py-20 bg-slate-50 dark:bg-slate-900 transition-colors relative">
    <div class="absolute inset-0 opacity-40 dark:opacity-20 pointer-events-none" style="background-image: radial-gradient(var(--tw-gradient-stops));"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="max-w-5xl mx-auto">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-primary/5 dark:shadow-none border border-white/50 dark:border-slate-700/50 p-8 md:p-12 transition-all hover:shadow-2xl hover:-translate-y-1 duration-500 relative overflow-hidden group" data-aos="fade-up">
                <!-- Decorative element -->
                <div class="absolute -top-32 -right-32 w-64 h-64 bg-primary/20 rounded-full blur-3xl pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-secondary/20 rounded-full blur-3xl pointer-events-none group-hover:scale-150 transition-transform duration-700 delay-100"></div>
                
                <div class="relative z-10 text-center">
                    <?php if (!empty($org_chart)): ?>
                        <div class="inline-block p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 mb-8 w-full transition-transform duration-500 hover:scale-[1.02]">
                            <img src="<?= htmlspecialchars($org_chart) ?>" alt="Struktur Organisasi" class="w-full h-auto rounded-xl">
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16 px-4 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl mb-8">
                            <i class="bi bi-diagram-3 text-5xl text-slate-300 dark:text-slate-600 mb-4 block"></i>
                            <h3 class="text-lg font-bold text-slate-700 dark:text-slate-300 mb-2">Bagan Struktur Belum Tersedia</h3>
                            <p class="text-slate-500 dark:text-slate-400">Silakan unggah gambar struktur organisasi melalui halaman admin.</p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($page['content'])): ?>
                        <div class="prose-content dark:text-slate-300 text-left mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                            <?= $page['content'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Admin Quick Edit Bar -->
            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <div class="mt-8 flex justify-center">
                <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-6 py-3 bg-slate-800 hover:bg-slate-900 dark:bg-primary dark:hover:bg-primaryHover text-white text-sm font-bold rounded-xl shadow-md transition-all hover:scale-105">
                    <i class="bi bi-pencil-fill mr-2"></i> Pengaturan Struktur
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
