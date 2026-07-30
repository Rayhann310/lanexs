<?php
// kontak-kami.php
$navbarWhite = true;
$meta = ['icon' => 'bi-telephone-inbound', 'label' => 'Kontak', 'color' => 'from-slate-600 to-slate-800'];

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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div data-aos="fade-right">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 md:p-12 transition-colors h-full">
                    <div class="prose-content dark:text-slate-300">
                        <?php if (!empty($page['content'])): ?>
                            <?= $page['content'] ?>
                        <?php else: ?>
                            <h2>Hubungi Kami</h2>
                            <p>Silakan gunakan informasi di samping untuk menghubungi tim support kami.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div data-aos="fade-left">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 md:p-12 transition-colors">
                    <h3 class="font-heading font-bold text-2xl mb-6 dark:text-white">Kirim Pesan</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                            <input type="text" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-transparent dark:text-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-colors" placeholder="Masukkan nama">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                            <input type="email" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-transparent dark:text-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-colors" placeholder="Masukkan email">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pesan</label>
                            <textarea rows="4" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-transparent dark:text-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-colors" placeholder="Tulis pesan Anda..."></textarea>
                        </div>
                        <button type="button" class="w-full bg-primary hover:bg-primaryHover text-white py-3 rounded-lg font-bold transition-colors">Kirim Pesan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
