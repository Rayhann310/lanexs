<?php
// sejarah-perusahaan.php — Specific view for Sejarah Perusahaan
$navbarWhite = true;
$meta = ['icon' => 'bi-clock-history', 'label' => 'Profil Perusahaan', 'color' => 'from-blue-600 to-blue-800'];

// Component Texts (Editable via Settings if needed)
$settingModel = new \App\Models\Setting();
$stat_branches = $settingModel->get('sejarah_stat_branches', '150+');
$stat_packages = $settingModel->get('sejarah_stat_packages', '5M+');
$stat_cities = $settingModel->get('sejarah_stat_cities', '38');

$hero_bg = $settingModel->get('sejarah_hero_bg', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop');
$cta_bg = $settingModel->get('sejarah_cta_bg', 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?q=80&w=2075&auto=format&fit=crop');

$m1_year = $settingModel->get('sejarah_m1_year', '2025');
$m1_title = $settingModel->get('sejarah_m1_title', 'Peresmian LANEXS');
$m1_desc = $settingModel->get('sejarah_m1_desc', 'Didirikan di Bekasi dengan visi menjadi pilar logistik Indonesia.');

$m2_year = $settingModel->get('sejarah_m2_year', '2026');
$m2_title = $settingModel->get('sejarah_m2_title', 'Ekspansi Darat & Laut');
$m2_desc = $settingModel->get('sejarah_m2_desc', 'Membuka rute ke seluruh pulau Jawa dan Sumatera.');

$m3_year = $settingModel->get('sejarah_m3_year', '2028');
$m3_title = $settingModel->get('sejarah_m3_title', 'Jaringan Nasional');
$m3_desc = $settingModel->get('sejarah_m3_desc', 'Menjangkau 38 Provinsi dengan teknologi pelacakan real-time mutakhir.');

ob_start();
?>
<style>
.prose-content h1, .prose-content h2, .prose-content h3 {
    font-family: 'Outfit', sans-serif;
    color: #0f172a;
    font-weight: 800;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}
.dark .prose-content h1, .dark .prose-content h2, .dark .prose-content h3 { color: #f8fafc; }
.prose-content p {
    font-size: 0.95rem;
    line-height: 1.7;
    color: #475569;
    margin-bottom: 1.25rem;
}
.dark .prose-content p { color: #cbd5e1; }
.prose-content ul { list-style-type: none; padding-left: 0.5rem; margin-bottom: 1.5rem; }
.prose-content ul li { position: relative; padding-left: 1.25rem; margin-bottom: 0.5rem; color: #475569; line-height: 1.6; font-size: 0.95rem;}
.dark .prose-content ul li { color: #cbd5e1; }
.prose-content ul li::before { content: '•'; position: absolute; left: 0; color: #127B8E; font-weight: bold; font-size: 1.2rem; line-height: 1; top: 1px;}

/* Timeline CSS */
.timeline-item::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 40px;
    bottom: -30px;
    width: 2px;
    background: #e2e8f0;
}
.dark .timeline-item::before { background: #334155; }
.timeline-item:last-child::before { display: none; }
</style>

<!-- Page Hero Banner -->
<section class="pt-32 pb-24 bg-slate-900 relative overflow-hidden">
    <!-- Logistics Background Graphic -->
    <img src="<?= htmlspecialchars($hero_bg) ?>" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent"></div>
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 28px 28px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Breadcrumb -->
        <nav class="flex items-center justify-center space-x-2 text-white/60 text-sm mb-8" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-white transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-white font-medium"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div data-aos="fade-up" class="max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center space-x-2 bg-primary/20 border border-primary/30 rounded-full px-4 py-1.5 mb-6">
                <i class="bi <?= $meta['icon'] ?> text-primary"></i>
                <span class="text-primary font-semibold text-sm tracking-widest uppercase">Perjalanan Kami</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-heading font-black text-white leading-tight mb-4">
                Menghubungkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">Nusantara</span> Tanpa Batas
            </h1>
            <p class="text-base md:text-lg text-slate-300 font-light">Sejarah panjang dedikasi kami dalam membangun tulang punggung logistik Indonesia.</p>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<div class="relative z-20 -mt-12 mb-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 p-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-slate-700 transition-colors">
            <div data-aos="fade-up" data-aos-delay="100">
                <p class="text-4xl font-black text-slate-800 dark:text-white font-heading"><?= htmlspecialchars($stat_branches) ?></p>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-2">Cabang & Agen</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="200" class="pt-8 md:pt-0">
                <p class="text-4xl font-black text-primary font-heading"><?= htmlspecialchars($stat_packages) ?></p>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-2">Paket Terkirim</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="300" class="pt-8 md:pt-0">
                <p class="text-4xl font-black text-slate-800 dark:text-white font-heading"><?= htmlspecialchars($stat_cities) ?></p>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-2">Provinsi Jangkauan</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content & Timeline -->
<section class="pb-24 bg-white dark:bg-slate-900 transition-colors overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            
            <!-- Story Side -->
            <div class="lg:col-span-7" data-aos="fade-right">
                <h2 class="text-3xl font-heading font-black text-slate-800 dark:text-white mb-8 relative inline-block">
                    Kisah Kami
                    <span class="absolute -bottom-2 left-0 w-1/2 h-1 bg-primary rounded-full"></span>
                </h2>
                
                <div class="prose-content dark:text-slate-300">
                    <?php if (!empty($page['content'])): ?>
                        <?= $page['content'] ?>
                    <?php else: ?>
                        <div class="text-center py-12 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600">
                            <i class="bi bi-file-earmark-plus text-5xl mb-4 block text-slate-400"></i>
                            <p class="font-medium text-slate-500">Konten halaman ini belum diisi di database.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Admin Quick Edit Bar -->
                <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                <div class="mt-8 flex justify-start">
                    <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-primary dark:hover:bg-primaryHover text-white text-sm font-semibold rounded-xl shadow-md transition-colors">
                        <i class="bi bi-pencil-fill mr-2"></i> Edit Teks Sejarah Ini
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Timeline Side -->
            <div class="lg:col-span-5" data-aos="fade-left">
                <div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <h3 class="text-2xl font-heading font-black text-slate-800 dark:text-white mb-8">Tonggak Sejarah</h3>
                    
                    <div class="relative space-y-8">
                        <!-- Milestone 1 -->
                        <div class="relative timeline-item flex gap-4 md:gap-6">
                            <div class="w-5 h-5 md:w-6 md:h-6 bg-primary rounded-full border-4 border-white dark:border-slate-800 flex-shrink-0 relative z-10 shadow-sm mt-1"></div>
                            <div>
                                <span class="text-primary font-bold text-base md:text-lg"><?= htmlspecialchars($m1_year) ?></span>
                                <h4 class="font-bold text-slate-800 dark:text-white text-lg md:text-xl mt-1"><?= htmlspecialchars($m1_title) ?></h4>
                                <p class="text-slate-500 dark:text-slate-400 mt-1 md:mt-2 text-sm md:text-base leading-relaxed"><?= htmlspecialchars($m1_desc) ?></p>
                            </div>
                        </div>
                        
                        <!-- Milestone 2 -->
                        <div class="relative timeline-item flex gap-4 md:gap-6">
                            <div class="w-5 h-5 md:w-6 md:h-6 bg-blue-500 rounded-full border-4 border-white dark:border-slate-800 flex-shrink-0 relative z-10 shadow-sm mt-1"></div>
                            <div>
                                <span class="text-blue-500 font-bold text-base md:text-lg"><?= htmlspecialchars($m2_year) ?></span>
                                <h4 class="font-bold text-slate-800 dark:text-white text-lg md:text-xl mt-1"><?= htmlspecialchars($m2_title) ?></h4>
                                <p class="text-slate-500 dark:text-slate-400 mt-1 md:mt-2 text-sm md:text-base leading-relaxed"><?= htmlspecialchars($m2_desc) ?></p>
                            </div>
                        </div>

                        <!-- Milestone 3 -->
                        <div class="relative timeline-item flex gap-4 md:gap-6">
                            <div class="w-5 h-5 md:w-6 md:h-6 bg-emerald-500 rounded-full border-4 border-white dark:border-slate-800 flex-shrink-0 relative z-10 shadow-sm mt-1"></div>
                            <div>
                                <span class="text-emerald-500 font-bold text-base md:text-lg"><?= htmlspecialchars($m3_year) ?></span>
                                <h4 class="font-bold text-slate-800 dark:text-white text-lg md:text-xl mt-1"><?= htmlspecialchars($m3_title) ?></h4>
                                <p class="text-slate-500 dark:text-slate-400 mt-1 md:mt-2 text-sm md:text-base leading-relaxed"><?= htmlspecialchars($m3_desc) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-8 relative overflow-hidden rounded-3xl bg-primary shadow-xl">
                    <img src="<?= htmlspecialchars($cta_bg) ?>" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay">
                    <div class="relative p-8 text-center">
                        <h4 class="text-white font-bold text-xl mb-3">Jadilah Bagian dari Sejarah Kami</h4>
                        <p class="text-white/80 text-sm mb-6">Percayakan pengiriman bisnis Anda kepada yang terbaik.</p>
                        <a href="<?= BASE_URL ?>/page/layanan-pengiriman" class="inline-block bg-white text-primary font-bold px-6 py-3 rounded-xl hover:bg-slate-50 transition-colors shadow-lg">
                            Lihat Layanan Kami
                        </a>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</section>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
