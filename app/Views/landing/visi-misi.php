<?php
// visi-misi.php
$navbarWhite = false;
$meta = ['icon' => 'bi-eye', 'label' => 'Profil Perusahaan', 'color' => 'from-purple-600 to-purple-800'];

$settingModel = new \App\Models\Setting();
$hero_bg = $settingModel->get('page_visi_misi_img', 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop');

// Load structured Visi Misi fields
$def_visi = 'Menjadi mitra ekspedisi andalan masyarakat Indonesia dengan menghadirkan layanan logistik yang andal, inovatif, dan berdaya saing tinggi.';
$def_m1 = 'Mendukung pemerataan ekonomi nasional melalui jangkauan yang luas;';
$def_m2 = 'Menjaga hubungan baik, profesional dan responsif dengan pelanggan;';
$def_m3 = 'Mengembangkan teknologi modern untuk mempermudah pelacakan dan pengelolaan pengiriman barang;';
$def_m4 = 'Memberikan pelayanan pengiriman yang cepat, aman, dan terpercaya ke seluruh penjuru Nusantara;';

$visi_text = $settingModel->get('vm_visi', $def_visi);
$m1 = $settingModel->get('vm_m1', $def_m1);
$m2 = $settingModel->get('vm_m2', $def_m2);
$m3 = $settingModel->get('vm_m3', $def_m3);
$m4 = $settingModel->get('vm_m4', $def_m4);

ob_start();
?>
<style>
/* Reset and Base */
.prose-content h1, .prose-content h2, .prose-content h3 {
    font-family: 'Outfit', sans-serif;
    color: #0f172a;
    font-weight: 800;
    line-height: 1.3;
}
.dark .prose-content h1, .dark .prose-content h2, .dark .prose-content h3 { color: #f8fafc; }
.prose-content p { color: #475569; }
.dark .prose-content p { color: #cbd5e1; }

/* --- VISI SECTION --- */
.prose-content h2:first-of-type {
    text-align: center;
    font-size: 2.25rem;
    background: linear-gradient(135deg, #127B8E, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-top: 1rem;
    margin-bottom: 2rem;
}

.prose-content p:first-of-type {
    text-align: center;
    font-size: 1.15rem;
    line-height: 1.8;
    color: #334155;
    font-style: italic;
    font-weight: 600;
    padding: 2.5rem 2rem;
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    border-radius: 2rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), inset 0 2px 4px rgba(255, 255, 255, 1);
    margin-bottom: 5rem;
    position: relative;
    z-index: 1;
}
.dark .prose-content p:first-of-type {
    background: linear-gradient(145deg, #1e293b, #0f172a);
    color: #e2e8f0;
    border-color: #334155;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
}

/* Quote Icons for Visi */
.prose-content p:first-of-type::before {
    content: '\F6B0'; /* quote-left from bootstrap icons */
    font-family: 'bootstrap-icons';
    position: absolute;
    top: -1.5rem;
    left: 2rem;
    font-size: 3rem;
    color: #127B8E;
    opacity: 0.2;
    z-index: -1;
}
.prose-content p:first-of-type::after {
    content: '\F6B1'; /* quote-right */
    font-family: 'bootstrap-icons';
    position: absolute;
    bottom: -1.5rem;
    right: 2rem;
    font-size: 3rem;
    color: #3b82f6;
    opacity: 0.2;
    z-index: -1;
}

@media (min-width: 768px) {
    .prose-content p:first-of-type { font-size: 1.35rem; padding: 3rem; }
}

/* --- MISI SECTION --- */
.prose-content h2:nth-of-type(2) {
    text-align: center;
    font-size: 2.25rem;
    background: linear-gradient(135deg, #3b82f6, #127B8E);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 2.5rem;
}

.prose-content ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}
@media (min-width: 768px) {
    .prose-content ul {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
}

.prose-content ul li {
    position: relative;
    background: white;
    padding: 1.75rem 1.75rem 1.75rem 4.5rem;
    border-radius: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    border: 1px solid #f1f5f9;
    color: #475569;
    line-height: 1.7;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
}
.dark .prose-content ul li {
    background: #1e293b;
    border-color: #334155;
    color: #cbd5e1;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
}

.prose-content ul li:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    border-color: #127B8E;
    z-index: 10;
}
.dark .prose-content ul li:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
}

/* Custom Checkmark for Misi */
.prose-content ul li::before {
    content: '\F26E'; /* check-circle-fill */
    font-family: 'bootstrap-icons';
    position: absolute;
    left: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #127B8E;
    font-size: 1.75rem;
    transition: transform 0.4s ease;
}
.prose-content ul li:hover::before {
    transform: translateY(-50%) scale(1.2) rotate(360deg);
}

.prose-content img { border-radius: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); max-width: 100%; height: auto; margin-bottom: 2rem;}
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
                    <h2>🧪 Test Mode</h2>
                    <p>Konten Visi &amp; Misi sedang diuji. Silakan lakukan <code>git pull</code> di server dan cek apakah gambar-gambar tetap ada.</p>
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
