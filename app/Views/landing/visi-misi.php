<?php
// visi-misi.php
$navbarWhite = true;
$meta = ['icon' => 'bi-eye', 'label' => 'Profil Perusahaan', 'color' => 'from-purple-600 to-purple-800'];

ob_start();
?>
<style>
.prose-content h1, .prose-content h2, .prose-content h3 {
    font-family: 'Outfit', sans-serif;
    color: #0f172a;
    font-weight: 800;
    margin-top: 1rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}
.dark .prose-content h1, .dark .prose-content h2, .dark .prose-content h3 { color: #f8fafc; }
.prose-content p {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #475569;
    margin-bottom: 1.5rem;
}
.dark .prose-content p { color: #cbd5e1; }
.prose-content ul { list-style-type: none; padding-left: 0.5rem; margin-bottom: 1.5rem; }
.prose-content ul li { position: relative; padding-left: 1.5rem; margin-bottom: 0.5rem; color: #475569; line-height: 1.7; }
.dark .prose-content ul li { color: #cbd5e1; }
.prose-content ul li::before { content: '•'; position: absolute; left: 0; color: #127B8E; font-weight: bold; font-size: 1.2rem; }
.prose-content img { border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); max-width: 100%; height: auto; margin-bottom: 2rem;}
</style>

<!-- Page Hero Banner -->
<section class="pt-32 pb-16 bg-gradient-to-br <?= $meta['color'] ?> relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
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
<section class="py-20 bg-slate-50 dark:bg-slate-900 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 md:p-12 transition-colors relative overflow-hidden" data-aos="fade-up">
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary/10 to-transparent rounded-full -mr-32 -mt-32 pointer-events-none"></div>
                
                <div class="prose-content dark:text-slate-300 relative z-10">
                    <?php if (!empty($page['content'])): ?>
                        <?= $page['content'] ?>
                    <?php else: ?>
                        <div class="text-center py-12 text-slate-400 dark:text-slate-500">
                            <i class="bi bi-file-earmark-plus text-5xl mb-4 block"></i>
                            <p class="font-medium">Konten halaman ini belum diisi.</p>
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

    </div>
</section>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
