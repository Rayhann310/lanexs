<?php
// layanan-tracking.php
$navbarWhite = false;
$meta = ['icon' => 'bi-geo-alt', 'label' => 'Layanan', 'color' => 'from-teal-500 to-cyan-700'];

$settingModel = new \App\Models\Setting();
$prefix = 'page_layanan_tracking';

$img       = $settingModel->get($prefix . '_img', 'https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=1470&auto=format&fit=crop');
$title     = $settingModel->get($prefix . '_title', 'Pantau Real-time');
$subtitle  = $settingModel->get($prefix . '_subtitle', 'Informasi 24/7');
$tagline   = $settingModel->get($prefix . '_tagline', 'Tracking System');
$desc      = $settingModel->get($prefix . '_desc', 'Sistem pelacakan real-time kami memungkinkan Anda memantau setiap langkah pengiriman. Dari gudang hingga tujuan akhir, kami memberikan transparansi penuh untuk ketenangan pikiran Anda.');
$feat1_title = $settingModel->get($prefix . '_feat1_title', 'Update Otomatis');
$feat1_desc  = $settingModel->get($prefix . '_feat1_desc', 'Notifikasi status paket langsung ke perangkat Anda.');
$feat2_title = $settingModel->get($prefix . '_feat2_title', 'Akurasi GPS');
$feat2_desc  = $settingModel->get($prefix . '_feat2_desc', 'Melacak koordinat pengiriman dengan tingkat presisi tinggi.');
$feat3_title = $settingModel->get($prefix . '_feat3_title', 'Riwayat Rinci');
$feat3_desc  = $settingModel->get($prefix . '_feat3_desc', 'Log perjalanan paket yang tercatat rapi di setiap checkpoint.');
$feat4_title = $settingModel->get($prefix . '_feat4_title', 'Multi Platform');
$feat4_desc  = $settingModel->get($prefix . '_feat4_desc', 'Akses dari Web, Mobile App, maupun layanan WhatsApp.');
$stat1_num   = $settingModel->get($prefix . '_stat1_num', '100%');
$stat1_label = $settingModel->get($prefix . '_stat1_label', 'Transparansi');
$stat2_num   = $settingModel->get($prefix . '_stat2_num', 'Real-time');
$stat2_label = $settingModel->get($prefix . '_stat2_label', 'Monitoring');
$cta_title   = $settingModel->get($prefix . '_cta_title', 'Cek Status Kiriman Anda');
$cta_desc    = $settingModel->get($prefix . '_cta_desc', 'Masukkan nomor resi untuk mengetahui lokasi dan status paket secara langsung.');
$wa_number   = $settingModel->get($prefix . '_wa', '6281234567890');
$wa_link     = "https://wa.me/" . preg_replace('/[^0-9]/', '', $wa_number);

// Load FAQs
$faqs = [];
for ($i = 1; $i <= 5; $i++) {
    $q = $settingModel->get($prefix . '_q' . $i, '');
    $a = $settingModel->get($prefix . '_a' . $i, '');
    if (!empty($q)) {
        $faqs[] = ['q' => $q, 'a' => $a];
    }
}

// Fallback if empty
if (empty($faqs)) {
    $faqs = [
        ['q' => 'Bagaimana cara melacak paket saya?', 'a' => 'Anda dapat melacak paket melalui form di halaman ini dengan memasukkan nomor resi.'],
        ['q' => 'Berapa lama estimasi pengiriman?', 'a' => 'Tergantung jenis layanan dan jarak. Reguler biasanya 2-4 hari, sedangkan Express 1-2 hari.'],
        ['q' => 'Apakah barang saya diasuransikan?', 'a' => 'Ya, kami menyediakan opsi asuransi pengiriman untuk perlindungan ekstra.']
    ];
}

ob_start();
?>
<style>
/* ===== LAYANAN TRACKING PREMIUM ===== */
/* Feature Card */
.track-card { 
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 1.75rem;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}
.dark .track-card { background: #1e293b; border-color: #334155; }
.track-card::before { 
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 4px; height: 100%;
    background: linear-gradient(to bottom, #14b8a6, #06b6d4);
    opacity: 0;
    transition: opacity 0.3s;
}
.track-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.08); transform: translateY(-4px); border-color: #99f6e4; }
.dark .track-card:hover { border-color: #115e59; }
.track-card:hover::before { opacity: 1; }

.track-icon { 
    width: 52px; height: 52px;
    border-radius: 14px;
    background: #f0fdfa;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    color: #0d9488;
    transition: all 0.3s;
}
.dark .track-icon { background: #134e4a; color: #5eead4; }
.track-card:hover .track-icon { background: #0d9488; color: #fff; transform: rotate(5deg) scale(1.1); }

/* ===== FAQ ACCORDION ===== */
.faq-item {
    border-bottom: 1px solid #e2e8f0;
}
.dark .faq-item {
    border-bottom-color: #334155;
}
.faq-question {
    cursor: pointer;
    transition: all 0.3s ease;
}
.faq-question:hover {
    color: #0d9488; /* teal */
}
.dark .faq-question:hover {
    color: #5eead4;
}
.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.4s ease;
}
.faq-item.active .faq-answer {
    max-height: 500px;
    padding-top: 1rem;
    padding-bottom: 1.5rem;
}
.faq-item.active .faq-icon {
    transform: rotate(180deg);
}
.faq-icon {
    transition: transform 0.3s ease;
}
</style>

<!-- ====================================
     HERO SECTION (White/Light) - Tracking
     ==================================== -->
<section class="pt-32 pb-24 bg-white dark:bg-slate-900 relative overflow-hidden border-b border-slate-100 dark:border-slate-800 transition-colors">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.06]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    <!-- Accent Shapes Teal/Cyan -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-teal-400/10 rounded-full -translate-y-1/2 -translate-x-1/2 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-cyan-300/10 rounded-full translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="flex items-center space-x-2 text-slate-400 dark:text-slate-500 text-sm mb-8" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-teal-600 transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-500 dark:text-slate-400"><?= htmlspecialchars($meta['label']) ?></span>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-800 dark:text-white font-semibold"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div data-aos="fade-right">
                <div class="inline-flex items-center space-x-2 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 rounded-full px-4 py-1.5 mb-6">
                    <i class="bi bi-geo-alt-fill text-teal-600"></i>
                    <span class="text-teal-600 font-semibold text-sm tracking-wide uppercase"><?= htmlspecialchars($tagline) ?></span>
                </div>
                <h1 class="text-4xl md:text-6xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-4">
                    <?= htmlspecialchars($page['title']) ?>
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-light leading-relaxed mb-8"><?= htmlspecialchars($desc) ?></p>
                <div class="flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/tracking" class="px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-bold rounded-xl hover:from-teal-600 hover:to-cyan-600 transition-all hover:scale-105 shadow-lg flex items-center gap-2">
                        <i class="bi bi-search"></i> Cek Resi Sekarang
                    </a>
                </div>
            </div>
            <!-- Interactive Illustration / Image -->
            <div class="relative hidden lg:block" data-aos="fade-left">
                <div class="relative rounded-[2rem] overflow-hidden shadow-2xl group border-4 border-white dark:border-slate-800">
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($page['title']) ?>" class="w-full h-[400px] object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-teal-900/70 via-teal-900/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <span class="inline-block bg-teal-500 text-white text-xs font-bold uppercase tracking-wider py-1.5 px-3 rounded-full mb-2"><?= htmlspecialchars($subtitle) ?></span>
                        <h3 class="text-white font-heading font-bold text-2xl"><?= htmlspecialchars($title) ?></h3>
                    </div>
                </div>
                
                <!-- Floating Stats -->
                <div class="absolute -bottom-8 -left-8 bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 items-center gap-4 flex border border-slate-100 dark:border-slate-700 animate-bounce" style="animation-duration: 3s;">
                    <div class="w-14 h-14 rounded-full bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center">
                        <i class="bi bi-clock-history text-teal-600 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($stat1_label) ?></div>
                        <div class="font-black text-slate-800 dark:text-white text-xl"><?= htmlspecialchars($stat1_num) ?></div>
                    </div>
                </div>
                <div class="absolute -top-8 -right-8 bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 items-center gap-4 flex border border-slate-100 dark:border-slate-700">
                    <div>
                        <div class="text-sm text-slate-500 dark:text-slate-400 text-right"><?= htmlspecialchars($stat2_label) ?></div>
                        <div class="font-black text-slate-800 dark:text-white text-xl text-right"><?= htmlspecialchars($stat2_num) ?></div>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-cyan-100 dark:bg-cyan-900/50 flex items-center justify-center">
                        <i class="bi bi-broadcast text-cyan-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================
     HOW IT WORKS & FEATURES GRID
     ==================================== -->
<section class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            
            <!-- Left Side: Content & Extra text -->
            <div class="lg:col-span-5 sticky top-24" data-aos="fade-up">
                <span class="inline-block text-xs font-black tracking-[0.3em] uppercase text-teal-600 mb-4">Fitur Utama</span>
                <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 dark:text-white mb-6 leading-tight">
                    Mengapa Sistem Tracking Kami Berbeda?
                </h2>
                
                <?php if (!empty($page['content'])): ?>
                <div class="prose-content mb-8"><?= $page['content'] ?></div>
                <?php endif; ?>

                <div class="bg-teal-50 dark:bg-teal-900/10 rounded-2xl p-6 border border-teal-100 dark:border-teal-900/30">
                    <div class="flex items-start gap-4">
                        <i class="bi bi-shield-check text-2xl text-teal-600"></i>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white mb-1">Keamanan Data Terjamin</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Hanya Anda dan penerima yang memiliki akses ke detail pengiriman.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Grid Features (4 items) -->
            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <?php foreach ([
                        ['bi-bell', $feat1_title, $feat1_desc],
                        ['bi-geo-alt', $feat2_title, $feat2_desc],
                        ['bi-journal-text', $feat3_title, $feat3_desc],
                        ['bi-phone', $feat4_title, $feat4_desc],
                    ] as $i => $f): ?>
                    <div class="track-card" data-aos="fade-up" data-aos-delay="<?= ($i % 2) * 100 ?>">
                        <div class="track-icon"><i class="bi <?= $f[0] ?>"></i></div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg mb-2"><?= htmlspecialchars($f[1]) ?></h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed"><?= htmlspecialchars($f[2]) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- ====================================
     FAQ SECTION (Merged)
     ==================================== -->
<section class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors border-t border-slate-100 dark:border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 dark:text-white mb-4">Pusat Bantuan & FAQ</h2>
            <p class="text-lg text-slate-500 dark:text-slate-400">Temukan jawaban cepat untuk pertanyaan umum mengenai layanan kami.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 md:p-12" data-aos="fade-up">
            <div class="space-y-2">
                <?php foreach ($faqs as $i => $faq): ?>
                <div class="faq-item group">
                    <button class="faq-question w-full flex items-center justify-between py-5 text-left font-bold text-slate-800 dark:text-white text-lg focus:outline-none" onclick="this.parentElement.classList.toggle('active')">
                        <span><?= htmlspecialchars($faq['q']) ?></span>
                        <i class="bi bi-chevron-down text-slate-400 faq-icon text-xl"></i>
                    </button>
                    <div class="faq-answer text-slate-600 dark:text-slate-400 leading-relaxed px-2">
                        <?= nl2br(htmlspecialchars($faq['a'])) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-12 text-center pt-8 border-t border-slate-100 dark:border-slate-800">
                <p class="text-slate-500 mb-4">Masih Butuh Bantuan?</p>
                <a href="<?= $wa_link ?>?text=Halo%20LANEXS,%20saya%20butuh%20bantuan%20terkait%20tracking" target="_blank" class="inline-flex items-center px-6 py-3 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 font-bold rounded-xl hover:bg-teal-100 dark:hover:bg-teal-900/40 transition-colors border border-teal-200 dark:border-teal-800 gap-2">
                    <i class="bi bi-whatsapp"></i> Hubungi Customer Service
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ====================================
     TRACKING FORM CTA
     ==================================== -->
<section class="py-24 bg-white dark:bg-slate-900 transition-colors border-t border-slate-100 dark:border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="zoom-in">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-600 mb-6">
            <i class="bi bi-box-seam text-4xl"></i>
        </div>
        <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 dark:text-white mb-4"><?= htmlspecialchars($cta_title) ?></h2>
        <p class="text-slate-500 dark:text-slate-400 text-lg mb-10 max-w-2xl mx-auto"><?= htmlspecialchars($cta_desc) ?></p>
        
        <form action="<?= BASE_URL ?>/tracking/result" method="GET" class="max-w-xl mx-auto relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            <div class="relative flex items-center bg-white dark:bg-slate-800 rounded-2xl p-2 shadow-xl border border-slate-200 dark:border-slate-700">
                <div class="pl-4 text-slate-400"><i class="bi bi-upc-scan text-xl"></i></div>
                <input type="text" name="resi" placeholder="Masukkan Nomor Resi..." required class="w-full py-3 px-4 bg-transparent outline-none text-slate-700 dark:text-white font-medium placeholder-slate-400">
                <button type="submit" class="bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md shrink-0">
                    Lacak Paket
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Admin Edit Bar -->
<?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
<div class="fixed bottom-6 right-6 z-50">
    <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-5 py-3 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl shadow-2xl transition-all hover:scale-105">
        <i class="bi bi-pencil-fill mr-2"></i> Edit Halaman
    </a>
</div>
<?php endif; ?>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
