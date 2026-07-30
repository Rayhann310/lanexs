<?php
// layanan-pengiriman.php
$navbarWhite = true;
$meta = ['icon' => 'bi-truck', 'label' => 'Layanan', 'color' => 'from-amber-500 to-orange-700'];

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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Graphic Side -->
            <div class="lg:col-span-5 order-2 lg:order-2" data-aos="fade-in">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop" alt="LANEXS" class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <div class="bg-primary text-white text-xs font-bold uppercase tracking-wider py-1.5 px-3 rounded-full inline-block mb-3">Jangkauan seluruh nusantara</div>
                        <h3 class="text-white font-heading font-bold text-2xl">Cepat & Aman</h3>
                    </div>
                </div>
            </div>
            
            <!-- Content Side -->
            <div class="lg:col-span-7 order-1 lg:order-1" data-aos="fade-up">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 md:p-12 transition-colors">
                    <div class="prose-content dark:text-slate-300">
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

    </div>
</section>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
