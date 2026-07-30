<?php
// visi-misi.php
$navbarWhite = false;
$meta = ['icon' => 'bi-eye', 'label' => 'Profil Perusahaan', 'color' => 'from-purple-600 to-purple-800'];

$settingModel = new \App\Models\Setting();
$hero_bg = $settingModel->get('page_visi_misi_img', 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop');

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
@media (min-width: 768px) {
    .prose-content p { font-size: 1.05rem; line-height: 1.8; }
    .prose-content ul li { font-size: 1.05rem !important; }
}
.dark .prose-content p { color: #cbd5e1; }
.prose-content ul { list-style-type: none; padding-left: 0.5rem; margin-bottom: 1.5rem; }
.prose-content ul li { position: relative; padding-left: 1.25rem; margin-bottom: 0.5rem; color: #475569; line-height: 1.6; font-size: 0.95rem;}
.dark .prose-content ul li { color: #cbd5e1; }
.prose-content ul li::before { content: '•'; position: absolute; left: 0; color: #127B8E; font-weight: bold; font-size: 1.2rem; line-height: 1; top: 1px;}
.prose-content img { border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); max-width: 100%; height: auto; margin-bottom: 2rem;}
</style>

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
            <p class="text-base md:text-lg text-slate-600 dark:text-slate-300 font-light transition-colors">Komitmen kuat kami untuk masa depan industri pengiriman logistik.</p>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="py-20 bg-slate-50 dark:bg-slate-900 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="max-w-4xl mx-auto">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-primary/5 dark:shadow-none border border-white/50 dark:border-slate-700/50 p-8 md:p-12 transition-all hover:shadow-2xl hover:-translate-y-1 duration-500 relative overflow-hidden group" data-aos="fade-up">
                <!-- Decorative element -->
                <div class="absolute -top-32 -right-32 w-64 h-64 bg-primary/20 rounded-full blur-3xl pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-secondary/20 rounded-full blur-3xl pointer-events-none group-hover:scale-150 transition-transform duration-700 delay-100"></div>
                
                <div class="prose-content dark:text-slate-300 relative z-10">
                    <?php if (!empty($page['content'])): ?>
                        <?= $page['content'] ?>
                    <?php else: ?>
                        <div class="text-center py-12 text-slate-400 dark:text-slate-500 border border-dashed border-slate-300 dark:border-slate-600 rounded-2xl">
                            <i class="bi bi-file-earmark-plus text-5xl mb-4 block"></i>
                            <p class="font-medium">Konten halaman ini belum diisi.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Admin Quick Edit Bar -->
            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <div class="mt-8 flex justify-center">
                <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-6 py-3 bg-slate-800 hover:bg-slate-900 dark:bg-primary dark:hover:bg-primaryHover text-white text-sm font-bold rounded-xl shadow-md transition-all hover:scale-105">
                    <i class="bi bi-pencil-fill mr-2"></i> Edit Halaman Ini
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
