<?php
// layanan-pengemasan.php
$navbarWhite = false;
$meta = ['icon' => 'bi-box-seam', 'label' => 'Layanan', 'color' => 'from-orange-500 to-red-700'];

$settingModel = new \App\Models\Setting();
$prefix = 'page_layanan_pengemasan';
$img       = $settingModel->get($prefix . '_img', 'https://images.unsplash.com/photo-1577705998148-6da4f3963bc8?q=80&w=2070&auto=format&fit=crop');
$title     = $settingModel->get($prefix . '_title', 'Kemasan Kuat');
$subtitle  = $settingModel->get($prefix . '_subtitle', 'Perlindungan maksimal');
$tagline   = $settingModel->get($prefix . '_tagline', 'Packaging Premium');
$desc      = $settingModel->get($prefix . '_desc', 'Kami menghadirkan solusi pengemasan berkualitas tinggi yang melindungi setiap produk Anda dari risiko kerusakan selama proses pengiriman dengan material berstandar industri.');
$feat1_title = $settingModel->get($prefix . '_feat1_title', 'Bubble Wrap Premium');
$feat1_desc  = $settingModel->get($prefix . '_feat1_desc', 'Material peredam benturan berkualitas tinggi untuk produk fragile.');
$feat2_title = $settingModel->get($prefix . '_feat2_title', 'Kemas Kayu / Peti');
$feat2_desc  = $settingModel->get($prefix . '_feat2_desc', 'Kemasan kayu kokoh untuk barang berat dan industri.');
$feat3_title = $settingModel->get($prefix . '_feat3_title', 'Vacuum Seal');
$feat3_desc  = $settingModel->get($prefix . '_feat3_desc', 'Kemasan vakum untuk produk yang butuh sterilitas tinggi.');
$feat4_title = $settingModel->get($prefix . '_feat4_title', 'Karton Tebal');
$feat4_desc  = $settingModel->get($prefix . '_feat4_desc', 'Kardus double-wall untuk kekuatan ekstra dan tahan benturan.');
$feat5_title = $settingModel->get($prefix . '_feat5_title', 'Labeling Profesional');
$feat5_desc  = $settingModel->get($prefix . '_feat5_desc', 'Label barcode dan informasi pengiriman cetak jelas dan rapi.');
$feat6_title = $settingModel->get($prefix . '_feat6_title', 'Custom Branding');
$feat6_desc  = $settingModel->get($prefix . '_feat6_desc', 'Kemasan dengan logo dan desain perusahaan Anda.');
$stat1_num   = $settingModel->get($prefix . '_stat1_num', '2M+');
$stat1_label = $settingModel->get($prefix . '_stat1_label', 'Paket Dikemas');
$stat2_num   = $settingModel->get($prefix . '_stat2_num', '99%');
$stat2_label = $settingModel->get($prefix . '_stat2_label', 'Bebas Kerusakan');
$stat3_num   = $settingModel->get($prefix . '_stat3_num', '15+');
$stat3_label = $settingModel->get($prefix . '_stat3_label', 'Jenis Kemasan');
$stat4_num   = $settingModel->get($prefix . '_stat4_num', '100%');
$stat4_label = $settingModel->get($prefix . '_stat4_label', 'Eco-Friendly');
$cta_title   = $settingModel->get($prefix . '_cta_title', 'Butuh Solusi Pengemasan?');
$cta_desc    = $settingModel->get($prefix . '_cta_desc', 'Konsultasikan kebutuhan pengemasan Anda bersama tim ahli kami secara gratis.');

ob_start();
?>
<style>
/* ===== LAYANAN PENGEMASAN PREMIUM ===== */
/* Feature Card */
.feat-card { 
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 1.75rem;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}
.dark .feat-card { background: #1e293b; border-color: #334155; }
.feat-card::before { 
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 4px; height: 100%;
    background: linear-gradient(to bottom, #f97316, #dc2626);
    opacity: 0;
    transition: opacity 0.3s;
}
.feat-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.08); transform: translateY(-4px); border-color: #fed7aa; }
.dark .feat-card:hover { border-color: #7c2d12; }
.feat-card:hover::before { opacity: 1; }

.feat-icon { 
    width: 52px; height: 52px;
    border-radius: 14px;
    background: #fff7ed;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    color: #ea580c;
    transition: all 0.3s;
}
.dark .feat-icon { background: #431407; color: #fb923c; }
.feat-card:hover .feat-icon { background: #f97316; color: #fff; transform: rotate(5deg) scale(1.1); }

/* Stats bar */
.stat-item {
    text-align: center;
    padding: 2rem;
    border-right: 1px solid rgba(255,255,255,0.15);
}
.stat-item:last-child { border-right: none; }

/* Process steps */
.step-line { 
    position: absolute; 
    top: 26px; left: calc(50% + 26px); 
    right: 0; 
    height: 2px; 
    background: linear-gradient(to right, #f97316, transparent); 
}
.step-card { position: relative; text-align: center; }
</style>

<!-- ====================================
     HERO SECTION (White/Light)
     ==================================== -->
<section class="pt-32 pb-24 bg-white dark:bg-slate-900 relative overflow-hidden border-b border-slate-100 dark:border-slate-800 transition-colors">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.06]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-orange-400/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-red-300/10 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="flex items-center space-x-2 text-slate-400 dark:text-slate-500 text-sm mb-8" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-orange-600 transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-500 dark:text-slate-400"><?= htmlspecialchars($meta['label']) ?></span>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-800 dark:text-white font-semibold"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-up">
                <div class="inline-flex items-center space-x-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-full px-4 py-1.5 mb-6">
                    <i class="bi bi-box-seam text-orange-600"></i>
                    <span class="text-orange-600 font-semibold text-sm tracking-wide uppercase"><?= htmlspecialchars($tagline) ?></span>
                </div>
                <h1 class="text-4xl md:text-6xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-4">
                    <?= htmlspecialchars($page['title']) ?>
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-light leading-relaxed mb-8"><?= htmlspecialchars($desc) ?></p>
                <div class="flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/contact" class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-xl hover:from-orange-600 hover:to-red-600 transition-all hover:scale-105 shadow-lg flex items-center gap-2">
                        <i class="bi bi-chat-dots-fill"></i> Konsultasi Gratis
                    </a>
                    <a href="<?= BASE_URL ?>/contact" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white font-bold rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center gap-2">
                        <i class="bi bi-telephone"></i> Hubungi Kami
                    </a>
                </div>
            </div>
            <!-- Hero image -->
            <div class="relative hidden lg:block" data-aos="fade-left">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($page['title']) ?>" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-orange-900/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <span class="inline-block bg-orange-500 text-white text-xs font-bold uppercase tracking-wider py-1.5 px-3 rounded-full mb-2"><?= htmlspecialchars($subtitle) ?></span>
                        <h3 class="text-white font-heading font-bold text-xl"><?= htmlspecialchars($title) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================
     STATS BAR
     ==================================== -->
<section class="bg-orange-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4">
            <?php foreach ([
                [$stat1_num, $stat1_label, 'bi-box-seam'],
                [$stat2_num, $stat2_label, 'bi-shield-check'],
                [$stat3_num, $stat3_label, 'bi-layers'],
                [$stat4_num, $stat4_label, 'bi-leaf'],
            ] as $i => $stat): ?>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                <i class="bi <?= $stat[2] ?> text-white/60 text-lg mb-2 block"></i>
                <div class="text-3xl font-black text-white"><?= htmlspecialchars($stat[0]) ?></div>
                <div class="text-sm text-white/70 font-medium mt-1"><?= htmlspecialchars($stat[1]) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====================================
     MAIN CONTENT: 2-COL LAYOUT
     ==================================== -->
<section class="py-24 bg-white dark:bg-slate-950 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Content first on mobile -->
            <div data-aos="fade-right">
                <span class="inline-block text-xs font-black tracking-[0.3em] uppercase text-orange-600 mb-4">Standar Industri</span>
                <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 dark:text-white mb-4 leading-tight">
                    Kemasan <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-500">Terpercaya</span> untuk Setiap Produk
                </h2>
                <p class="text-slate-600 dark:text-slate-400 text-lg mb-8 leading-relaxed"><?= htmlspecialchars($desc) ?></p>

                <?php if (!empty($page['content'])): ?>
                <div class="prose-content mb-6"><?= $page['content'] ?></div>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/contact" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-xl hover:from-orange-600 hover:to-red-600 transition-all hover:scale-105 shadow-lg gap-2">
                    <i class="bi bi-send-fill"></i> Konsultasi Sekarang
                </a>
            </div>

            <!-- Image -->
            <div class="relative" data-aos="fade-left">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($page['title']) ?>" class="w-full h-80 lg:h-[480px] object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-orange-900/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <span class="inline-block bg-orange-500 text-white text-xs font-bold uppercase tracking-wider py-1.5 px-3 rounded-full mb-3"><?= htmlspecialchars($subtitle) ?></span>
                        <h3 class="text-white font-heading font-bold text-2xl"><?= htmlspecialchars($title) ?></h3>
                    </div>
                </div>
                <!-- Floating badge -->
                <div class="absolute -bottom-6 -left-6 hidden md:flex bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-4 items-center gap-3 border border-slate-100 dark:border-slate-700">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                        <i class="bi bi-shield-fill-check text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Bebas Kerusakan</div>
                        <div class="font-black text-slate-800 dark:text-white text-lg"><?= htmlspecialchars($stat2_num) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================
     FEATURES GRID
     ==================================== -->
<section class="py-24 bg-slate-50 dark:bg-slate-900 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center mb-16" data-aos="fade-up">
            <span class="inline-block text-xs font-black tracking-[0.3em] uppercase text-orange-600 mb-4">Solusi Kami</span>
            <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 dark:text-white mb-3">Jenis Layanan Pengemasan</h2>
            <div class="w-16 h-1 bg-gradient-to-r from-orange-500 to-red-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ([
                ['bi-wind',          $feat1_title, $feat1_desc],
                ['bi-tree',          $feat2_title, $feat2_desc],
                ['bi-clipboard2-check', $feat3_title, $feat3_desc],
                ['bi-boxes',         $feat4_title, $feat4_desc],
                ['bi-tag-fill',      $feat5_title, $feat5_desc],
                ['bi-palette-fill',  $feat6_title, $feat6_desc],
            ] as $i => $f): ?>
            <div class="feat-card" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
                <div class="feat-icon"><i class="bi <?= $f[0] ?>"></i></div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg mb-2"><?= htmlspecialchars($f[1]) ?></h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed"><?= htmlspecialchars($f[2]) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====================================
     CTA SECTION
     ==================================== -->
<section class="py-20 bg-white dark:bg-slate-950 transition-colors">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 to-red-600 p-10 md:p-16 text-center shadow-2xl" data-aos="zoom-in">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 24px 24px;"></div>
            <i class="bi bi-box-seam absolute -right-4 -bottom-4 text-9xl text-white/10"></i>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-heading font-black text-white mb-3"><?= htmlspecialchars($cta_title) ?></h2>
                <p class="text-white/80 text-lg mb-8 max-w-xl mx-auto"><?= htmlspecialchars($cta_desc) ?></p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?= BASE_URL ?>/contact" class="px-8 py-3.5 bg-white text-orange-600 font-black rounded-xl hover:bg-orange-50 transition-all hover:scale-105 shadow-lg">
                        Konsultasi Gratis
                    </a>
                    <a href="tel:+62" class="px-8 py-3.5 bg-white/15 backdrop-blur-sm text-white font-bold rounded-xl border border-white/30 hover:bg-white/25 transition-all">
                        <i class="bi bi-telephone-fill mr-2"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admin Edit Bar -->
<?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
<div class="fixed bottom-6 right-6 z-50">
    <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-5 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl shadow-2xl transition-all hover:scale-105">
        <i class="bi bi-pencil-fill mr-2"></i> Edit Halaman
    </a>
</div>
<?php endif; ?>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
