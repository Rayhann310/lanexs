<?php
$settingModel = new \App\Models\Setting();
$prefix = 'page_exp';

$tagline = $settingModel->get($prefix . '_tagline', 'Our Experience');
$desc = $settingModel->get($prefix . '_desc', 'Galeri pengalaman dan momen terbaik kami dalam melayani pelanggan dengan penuh dedikasi dan profesionalitas.');
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
/* Modern Premium Masonry Layout */
.masonry-grid {
    column-count: 2;
    column-gap: 0.75rem;
}
@media (min-width: 640px) {
    .masonry-grid { column-count: 3; column-gap: 1.5rem; }
}
@media (min-width: 1024px) {
    .masonry-grid { column-count: 3; column-gap: 2rem; }
}
@media (min-width: 1280px) {
    .masonry-grid { column-count: 4; column-gap: 2rem; }
}

.masonry-item {
    break-inside: avoid;
    margin-bottom: 0.75rem;
}
@media (min-width: 640px) {
    .masonry-item { margin-bottom: 1.5rem; }
}
@media (min-width: 1024px) {
    .masonry-item { margin-bottom: 2rem; }
}

/* Custom glow on hover */
.glow-card { position: relative; }
.glow-card::before {
    content: '';
    position: absolute;
    inset: -1px;
    background: linear-gradient(135deg, #0ea5e9, #3b82f6, #8b5cf6);
    border-radius: inherit;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.5s ease;
}
.glow-card:hover::before { opacity: 1; }
</style>

<!-- ============================================
     PREMIUM HERO SECTION (Dark & Modern)
     ============================================ -->
<div class="relative pt-32 pb-24 lg:pt-48 lg:pb-32 bg-[#0a0f1c] overflow-hidden">
    <!-- Futuristic Grid Background -->
    <div class="absolute inset-0 pointer-events-none opacity-20" 
         style="background-image: 
            linear-gradient(to right, #334155 1px, transparent 1px),
            linear-gradient(to bottom, #334155 1px, transparent 1px);
            background-size: 4rem 4rem;
            mask-image: radial-gradient(ellipse at center, black 20%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 20%, transparent 80%);">
    </div>
    
    <!-- Floating Glowing Orbs -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-cyan-500/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3 pointer-events-none mix-blend-screen"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[120px] translate-y-1/3 -translate-x-1/3 pointer-events-none mix-blend-screen"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center flex flex-col items-center justify-center min-h-[30vh]">
        <div data-aos="fade-down" data-aos-duration="1000">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md text-cyan-400 text-xs font-bold tracking-[0.2em] uppercase mb-8 shadow-2xl">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                </span>
                <?= htmlspecialchars($tagline) ?>
            </div>
        </div>
        
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-heading font-black text-white tracking-tight mb-8 leading-[1.1]" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
            Jejak <br class="hidden md:block" />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-500">
                Langkah Kami
            </span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto font-light leading-relaxed mb-12" data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
            <?= htmlspecialchars($desc) ?>
        </p>

        <!-- Scroll Indicator -->
        <div class="animate-bounce inline-flex justify-center items-center w-12 h-12 rounded-full bg-white/5 border border-white/10 text-white/50 backdrop-blur-sm" data-aos="fade-up" data-aos-delay="600">
            <i class="bi bi-arrow-down text-xl"></i>
        </div>
    </div>
</div>

<!-- ============================================
     PREMIUM MASONRY GALLERY
     ============================================ -->
<div class="py-24 bg-slate-50 dark:bg-slate-900 transition-colors relative z-20">
    <!-- Decorative subtle gradient at the top -->
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-800 to-transparent"></div>

    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (empty($galleries)): ?>
            <div class="text-center py-32 bg-white dark:bg-slate-800 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-700/50" data-aos="fade-up">
                <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 dark:text-slate-600 mx-auto mb-6 border border-slate-100 dark:border-slate-700 shadow-inner">
                    <i class="bi bi-camera text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-3">Belum Ada Momen</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">Galeri portofolio dan pengalaman operasional kami akan segera hadir di sini.</p>
            </div>
        <?php else: ?>
            <div class="masonry-grid">
                <?php foreach ($galleries as $index => $item): ?>
                    <?php if ($item['photo']): ?>
                        <div class="masonry-item glow-card group rounded-2xl md:rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-700 bg-white dark:bg-slate-800 border border-slate-200/50 dark:border-slate-700/50" data-aos="fade-up" data-aos-delay="<?= ($index % 4) * 100 ?>">
                            
                            <!-- Image Container with Cinematic Zoom Effect -->
                            <div class="relative overflow-hidden aspect-auto bg-slate-100 dark:bg-slate-800">
                                <img src="<?= htmlspecialchars($item['photo']) ?>" alt="<?= htmlspecialchars($item['caption']) ?>" loading="lazy" class="w-full h-auto object-cover transform transition-transform duration-1000 ease-out group-hover:scale-[1.05] will-change-transform">
                                
                                <!-- Sleek Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/30 to-transparent opacity-60 md:opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                
                                <!-- Content / Caption -->
                                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-8 translate-y-2 md:translate-y-8 opacity-100 md:opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 ease-out">
                                    <div class="hidden md:flex items-center gap-3 mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-700 delay-100">
                                        <span class="w-8 h-[2px] bg-cyan-400"></span>
                                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">Dokumentasi</span>
                                    </div>
                                    <?php if (!empty($item['caption'])): ?>
                                        <p class="text-white font-bold text-sm md:text-xl lg:text-2xl leading-snug drop-shadow-lg md:drop-shadow-none">
                                            <?= htmlspecialchars($item['caption']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($page['content'])): ?>
<div class="py-24 bg-white dark:bg-slate-950 transition-colors border-t border-slate-100 dark:border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg md:prose-xl dark:prose-invert prose-slate mx-auto">
            <?= $page['content'] ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$slot = ob_get_clean();
$navbarWhite = true; // Make navbar transparent with white text initially since hero is dark
require __DIR__ . '/layout.php';
?>
