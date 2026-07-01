<?php
// Inner page — navbar should appear white (over dark gradient hero)
$navbarWhite = true;
$slugMeta = [
    'sejarah-perusahaan'  => ['icon' => 'bi-clock-history',    'label' => 'Profil Perusahaan',  'color' => 'from-blue-600 to-blue-800'],
    'visi-misi'           => ['icon' => 'bi-eye',               'label' => 'Profil Perusahaan',  'color' => 'from-purple-600 to-purple-800'],
    'struktur-organisasi' => ['icon' => 'bi-diagram-3',         'label' => 'Profil Perusahaan',  'color' => 'from-emerald-600 to-emerald-800'],
    'layanan-pengiriman'  => ['icon' => 'bi-truck',             'label' => 'Layanan',            'color' => 'from-amber-500 to-orange-700'],
    'layanan-pengemasan'  => ['icon' => 'bi-box-seam',          'label' => 'Layanan',            'color' => 'from-orange-500 to-red-700'],
    'layanan-tracking'    => ['icon' => 'bi-geo-alt',           'label' => 'Layanan',            'color' => 'from-red-500 to-rose-700'],
    'experience'          => ['icon' => 'bi-award',             'label' => 'Experience',         'color' => 'from-teal-600 to-primary'],
    'kontak-kami'         => ['icon' => 'bi-telephone-inbound', 'label' => 'Kontak',             'color' => 'from-slate-600 to-slate-800'],
];
$meta = $slugMeta[$page['slug']] ?? ['icon' => 'bi-file-earmark-text', 'label' => 'Halaman', 'color' => 'from-primary to-primaryHover'];

// Build layout slot
ob_start();
?>

<!-- Page Hero Banner -->
<section class="pt-32 pb-16 bg-gradient-to-br <?= $meta['color'] ?> relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-white/60 text-sm mb-6" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-white transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-white/60"><?= htmlspecialchars($meta['label']) ?></span>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-white font-medium"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div class="flex items-center space-x-5" data-aos="fade-up">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0">
                <i class="bi <?= $meta['icon'] ?> text-white text-3xl"></i>
            </div>
            <div>
                <p class="text-white/70 text-sm font-semibold uppercase tracking-widest mb-1"><?= htmlspecialchars($meta['label']) ?></p>
                <h1 class="text-3xl md:text-4xl font-heading font-black text-white leading-tight">
                    <?= htmlspecialchars($page['title']) ?>
                </h1>
            </div>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 md:p-12" data-aos="fade-up" data-aos-delay="100">
            <div class="prose-content">
                <?php if (!empty($page['content'])): ?>
                    <?= $page['content'] ?>
                <?php else: ?>
                    <div class="text-center py-12 text-slate-400">
                        <i class="bi bi-file-earmark-plus text-5xl mb-4 block"></i>
                        <p class="font-medium">Konten halaman ini belum diisi.</p>
                        <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                            <a href="<?= BASE_URL ?>/settings/pages" class="inline-flex items-center mt-4 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primaryHover transition-colors">
                                <i class="bi bi-pencil-fill mr-2"></i> Edit di Admin
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Admin Quick Edit Bar -->
        <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
        <div class="mt-6 flex justify-end">
            <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl shadow-md hover:bg-primaryHover transition-colors">
                <i class="bi bi-pencil-fill mr-2"></i> Edit Halaman Ini
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
$slot = ob_get_clean();

// Additional scripts for this page
$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>';

require __DIR__ . '/layout.php';
