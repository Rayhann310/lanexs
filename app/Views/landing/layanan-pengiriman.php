<?php
// layanan-pengiriman.php
$navbarWhite = false;
$meta = ['icon' => 'bi-truck', 'label' => 'Layanan', 'color' => 'from-amber-500 to-orange-700'];

$settingModel = new \App\Models\Setting();
$prefix = 'page_layanan_pengiriman';
$img       = $settingModel->get($prefix . '_img', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop');
$img2      = $settingModel->get($prefix . '_img2', 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?q=80&w=2075&auto=format&fit=crop');
$title     = $settingModel->get($prefix . '_title', 'Cepat & Aman');
$subtitle  = $settingModel->get($prefix . '_subtitle', 'Jangkauan seluruh nusantara');
$tagline   = $settingModel->get($prefix . '_tagline', 'Pengiriman Terpercaya');
$desc      = $settingModel->get($prefix . '_desc', 'Kami menjamin setiap paket Anda tiba tepat waktu dan dalam kondisi sempurna ke seluruh penjuru nusantara dengan armada modern dan tim profesional berpengalaman.');
$feat1_title = $settingModel->get($prefix . '_feat1_title', 'Pengiriman Same Day');
$feat1_desc  = $settingModel->get($prefix . '_feat1_desc', 'Paket tiba di hari yang sama untuk area tertentu.');
$feat2_title = $settingModel->get($prefix . '_feat2_title', 'Armada Berpendingin');
$feat2_desc  = $settingModel->get($prefix . '_feat2_desc', 'Kendaraan berpendingin untuk kargo sensitif suhu.');
$feat3_title = $settingModel->get($prefix . '_feat3_title', 'Asuransi Kargo');
$feat3_desc  = $settingModel->get($prefix . '_feat3_desc', 'Semua pengiriman dilindungi asuransi penuh.');
$feat4_title = $settingModel->get($prefix . '_feat4_title', 'Lacak Real-time');
$feat4_desc  = $settingModel->get($prefix . '_feat4_desc', 'Monitor posisi paket Anda setiap saat.');
$feat5_title = $settingModel->get($prefix . '_feat5_title', 'Penjemputan Langsung');
$feat5_desc  = $settingModel->get($prefix . '_feat5_desc', 'Tim kami menjemput langsung dari lokasi Anda.');
$feat6_title = $settingModel->get($prefix . '_feat6_title', 'Laporan Digital');
$feat6_desc  = $settingModel->get($prefix . '_feat6_desc', 'Bukti pengiriman digital dikirim otomatis.');
$stat1_num   = $settingModel->get($prefix . '_stat1_num', '5M+');
$stat1_label = $settingModel->get($prefix . '_stat1_label', 'Paket Dikirim');
$stat2_num   = $settingModel->get($prefix . '_stat2_num', '38');
$stat2_label = $settingModel->get($prefix . '_stat2_label', 'Provinsi Terjangkau');
$stat3_num   = $settingModel->get($prefix . '_stat3_num', '98%');
$stat3_label = $settingModel->get($prefix . '_stat3_label', 'Tingkat Kepuasan');
$stat4_num   = $settingModel->get($prefix . '_stat4_num', '24/7');
$stat4_label = $settingModel->get($prefix . '_stat4_label', 'Layanan Aktif');
$cta_title   = $settingModel->get($prefix . '_cta_title', 'Siap Mengirim Sekarang?');
$cta_desc    = $settingModel->get($prefix . '_cta_desc', 'Hubungi kami dan dapatkan estimasi biaya pengiriman gratis.');

ob_start();
?>
<style>
/* ===== LAYANAN PENGIRIMAN PREMIUM ===== */
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
    background: linear-gradient(to bottom, #f59e0b, #f97316);
    opacity: 0;
    transition: opacity 0.3s;
}
.feat-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.08); transform: translateY(-4px); border-color: #fde68a; }
.dark .feat-card:hover { border-color: #92400e; }
.feat-card:hover::before { opacity: 1; }

.feat-icon { 
    width: 52px; height: 52px;
    border-radius: 14px;
    background: #fef3c7;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    color: #d97706;
    transition: all 0.3s;
}
.dark .feat-icon { background: #451a03; color: #fbbf24; }
.feat-card:hover .feat-icon { background: #f59e0b; color: #fff; transform: rotate(5deg) scale(1.1); }

/* Stats bar */
.stat-item {
    text-align: center;
    padding: 2rem;
    border-right: 1px solid rgba(255,255,255,0.15);
}
.stat-item:last-child { border-right: none; }

/* Highlight band */
.highlight-band {
    background: linear-gradient(135deg, #fef3c7, #fff7ed);
    border: 1px solid #fde68a;
    border-radius: 20px;
    padding: 2.5rem;
}
.dark .highlight-band { background: linear-gradient(135deg, #451a03, #3d1a03); border-color: #92400e; }
</style>

<!-- ====================================
     HERO SECTION (White/Light)
     ==================================== -->
<section class="pt-32 pb-24 bg-white dark:bg-slate-900 relative overflow-hidden border-b border-slate-100 dark:border-slate-800 transition-colors">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.06]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    <!-- Color accent shapes -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-400/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-orange-300/10 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="flex items-center space-x-2 text-slate-400 dark:text-slate-500 text-sm mb-8" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-amber-600 transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-500 dark:text-slate-400"><?= htmlspecialchars($meta['label']) ?></span>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-800 dark:text-white font-semibold"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-up">
                <div class="inline-flex items-center space-x-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-full px-4 py-1.5 mb-6">
                    <i class="bi bi-truck text-amber-600"></i>
                    <span class="text-amber-600 font-semibold text-sm tracking-wide uppercase"><?= htmlspecialchars($tagline) ?></span>
                </div>
                <h1 class="text-4xl md:text-6xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-4">
                    <?= htmlspecialchars($page['title']) ?>
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-light leading-relaxed mb-8"><?= htmlspecialchars($desc) ?></p>
                <div class="flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/tracking" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all hover:scale-105 shadow-lg flex items-center gap-2">
                        <i class="bi bi-search"></i> Lacak Paket
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
                    <div class="absolute inset-0 bg-gradient-to-t from-amber-900/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <span class="inline-block bg-amber-500 text-white text-xs font-bold uppercase tracking-wider py-1.5 px-3 rounded-full mb-2"><?= htmlspecialchars($subtitle) ?></span>
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
<section class="bg-amber-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4">
            <?php foreach ([
                [$stat1_num, $stat1_label, 'bi-box-seam'],
                [$stat2_num, $stat2_label, 'bi-geo-alt'],
                [$stat3_num, $stat3_label, 'bi-star-fill'],
                [$stat4_num, $stat4_label, 'bi-clock'],
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
            <!-- Image -->
            <div class="relative" data-aos="fade-right">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl group aspect-w-4 aspect-h-3">
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($page['title']) ?>" class="w-full h-80 lg:h-[480px] object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-amber-900/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <span class="inline-block bg-amber-500 text-white text-xs font-bold uppercase tracking-wider py-1.5 px-3 rounded-full mb-3"><?= htmlspecialchars($subtitle) ?></span>
                        <h3 class="text-white font-heading font-bold text-2xl"><?= htmlspecialchars($title) ?></h3>
                    </div>
                </div>
                <!-- Floating badge -->
                <div class="absolute -bottom-6 -right-6 hidden md:flex bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-4 items-center gap-3 border border-slate-100 dark:border-slate-700">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <i class="bi bi-award-fill text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Kepuasan Klien</div>
                        <div class="font-black text-slate-800 dark:text-white text-lg"><?= htmlspecialchars($stat3_num) ?></div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div data-aos="fade-left">
                <span class="inline-block text-xs font-black tracking-[0.3em] uppercase text-amber-600 mb-4">Mengapa Pilih Kami</span>
                <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 dark:text-white mb-4 leading-tight">
                    Solusi Pengiriman <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">Terdepan</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 text-lg mb-8 leading-relaxed"><?= htmlspecialchars($desc) ?></p>

                <?php if (!empty($page['content'])): ?>
                <div class="prose-content mb-6"><?= $page['content'] ?></div>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/contact" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all hover:scale-105 shadow-lg gap-2">
                    <i class="bi bi-send-fill"></i> Mulai Pengiriman
                </a>
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
            <span class="inline-block text-xs font-black tracking-[0.3em] uppercase text-amber-600 mb-4">Keunggulan Layanan</span>
            <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 dark:text-white mb-3">Apa yang Kami Tawarkan</h2>
            <div class="w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ([
                ['bi-lightning-fill', $feat1_title, $feat1_desc],
                ['bi-snow2',          $feat2_title, $feat2_desc],
                ['bi-shield-check',   $feat3_title, $feat3_desc],
                ['bi-map',            $feat4_title, $feat4_desc],
                ['bi-truck',          $feat5_title, $feat5_desc],
                ['bi-file-earmark-check', $feat6_title, $feat6_desc],
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
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-500 to-orange-600 p-10 md:p-16 text-center shadow-2xl" data-aos="zoom-in">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 24px 24px;"></div>
            <i class="bi bi-truck text-6xl text-white/20 absolute -right-4 -bottom-4 text-9xl"></i>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-heading font-black text-white mb-3"><?= htmlspecialchars($cta_title) ?></h2>
                <p class="text-white/80 text-lg mb-8 max-w-xl mx-auto"><?= htmlspecialchars($cta_desc) ?></p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?= BASE_URL ?>/contact" class="px-8 py-3.5 bg-white text-amber-600 font-black rounded-xl hover:bg-amber-50 transition-all hover:scale-105 shadow-lg">
                        Mulai Sekarang
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
    <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl shadow-2xl transition-all hover:scale-105">
        <i class="bi bi-pencil-fill mr-2"></i> Edit Halaman
    </a>
</div>
<?php endif; ?>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
