<?php
$settingModel = new \App\Models\Setting();
$prefix = 'page_exp';

$tagline = $settingModel->get($prefix . '_tagline', 'Our Experience');
$desc = $settingModel->get($prefix . '_desc', 'Galeri pengalaman dan momen terbaik kami dalam melayani pelanggan.');
$exp_gallery_data = $settingModel->get('exp_gallery_data', '[]');
$galleries = json_decode($exp_gallery_data, true) ?: [];

// Get the actual photos
foreach ($galleries as &$g) {
    $g['photo'] = $settingModel->get('exp_img_' . $g['id'], '');
}
unset($g);

$pageTitle = $page['title'] ?? 'Experience';
ob_start();
?>
<style>
.masonry-grid {
    column-count: 1;
    column-gap: 1.5rem;
}
@media (min-width: 640px) {
    .masonry-grid { column-count: 2; }
}
@media (min-width: 1024px) {
    .masonry-grid { column-count: 3; }
}
.masonry-item {
    break-inside: avoid;
    margin-bottom: 1.5rem;
}
</style>

<!-- Hero Section -->
<div class="pt-32 pb-20 bg-white dark:bg-slate-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 32px 32px;"></div>
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="fade-up">
        <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-bold tracking-wide uppercase mb-6 border border-indigo-100 dark:border-indigo-800">
            <i class="bi bi-stars mr-2"></i> <?= htmlspecialchars($tagline) ?>
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight mb-6 leading-tight">
            Galeri <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Perjalanan Kami</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-500 dark:text-slate-400 max-w-3xl mx-auto font-light leading-relaxed">
            <?= htmlspecialchars($desc) ?>
        </p>
    </div>
</div>

<!-- Gallery Section -->
<div class="py-16 bg-slate-50 dark:bg-slate-900 transition-colors border-t border-slate-100 dark:border-slate-800 min-h-screen relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($galleries)): ?>
            <div class="text-center py-20" data-aos="fade-up">
                <div class="w-24 h-24 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-600 mx-auto mb-6">
                    <i class="bi bi-images text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Belum ada foto</h3>
                <p class="text-slate-500 dark:text-slate-400">Galeri pengalaman kami akan segera diperbarui dengan momen-momen terbaik.</p>
            </div>
        <?php else: ?>
            <div class="masonry-grid">
                <?php foreach ($galleries as $index => $item): ?>
                    <?php if ($item['photo']): ?>
                        <div class="masonry-item relative group rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100 ?>">
                            <img src="<?= htmlspecialchars($item['photo']) ?>" alt="<?= htmlspecialchars($item['caption']) ?>" class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-110">
                            
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Caption -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-6 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <?php if (!empty($item['caption'])): ?>
                                    <p class="text-white font-medium text-lg drop-shadow-md border-l-4 border-indigo-500 pl-3">
                                        <?= htmlspecialchars($item['caption']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($page['content'])): ?>
<div class="py-16 bg-white dark:bg-slate-800 transition-colors">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg dark:prose-invert">
        <?= $page['content'] ?>
    </div>
</div>
<?php endif; ?>

<?php
$slot = ob_get_clean();
$navbarWhite = false; // Hero background is white
require __DIR__ . '/layout.php';
?>
