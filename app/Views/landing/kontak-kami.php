<?php
$settingModel = new \App\Models\Setting();
$prefix = 'kontak_kami';

$tagline = $settingModel->get($prefix . '_tagline', 'Siap Melayani Anda 24/7');
$email = $settingModel->get($prefix . '_email', 'cs@lanexgroup.com');
$phone = $settingModel->get($prefix . '_phone', '1500-569');
$wa = $settingModel->get($prefix . '_wa', '6281234567890');
$address = $settingModel->get($prefix . '_address', 'Gedung LANEXS Center, Jl. Jend. Sudirman Kav 21, Jakarta Pusat, 10220');
$map = $settingModel->get($prefix . '_map', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.86989441113!2d106.74108821948523!3d-6.251458931102941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid');

$pageTitle = $page['title'] ?? 'Hubungi Kami';
ob_start();
?>

<!-- Hero Section -->
<div class="pt-32 pb-16 bg-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#e11d48 1px, transparent 1px); background-size: 32px 32px;"></div>
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="fade-up">
        <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-rose-50 text-rose-600 text-sm font-bold tracking-wide uppercase mb-6 border border-rose-100">
            <i class="bi bi-headset mr-2"></i> <?= htmlspecialchars($tagline) ?>
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight mb-6 leading-tight">
            Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">Kami</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-500 max-w-3xl mx-auto font-light leading-relaxed">
            Kami siap membantu dan menjawab pertanyaan Anda seputar layanan ekspedisi, pelacakan, dan kemitraan.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="py-12 bg-slate-50 relative z-20 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Contact Info Cards -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Phone -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="bi bi-telephone-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Call Center</h3>
                    <p class="text-slate-500 mb-4">Layanan pelanggan siap membantu Anda setiap hari.</p>
                    <a href="tel:<?= htmlspecialchars($phone) ?>" class="text-2xl font-black text-rose-600 hover:text-rose-700 transition-colors">
                        <?= htmlspecialchars($phone) ?>
                    </a>
                </div>

                <!-- WhatsApp -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="bi bi-whatsapp text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">WhatsApp</h3>
                    <p class="text-slate-500 mb-4">Konsultasi pengiriman via chat secara real-time.</p>
                    <a href="https://wa.me/<?= htmlspecialchars($wa) ?>" target="_blank" class="inline-flex items-center text-green-600 font-bold hover:text-green-700 transition-colors">
                        Chat Sekarang <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="bi bi-envelope-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Email Layanan</h3>
                    <p class="text-slate-500 mb-4">Untuk keperluan bisnis dan kemitraan.</p>
                    <a href="mailto:<?= htmlspecialchars($email) ?>" class="text-lg font-bold text-slate-800 hover:text-blue-600 transition-colors break-all">
                        <?= htmlspecialchars($email) ?>
                    </a>
                </div>
            </div>

            <!-- Map & Office Location -->
            <div class="lg:col-span-2 space-y-8" data-aos="fade-left" data-aos-delay="200">
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 flex flex-col h-full">
                    <div class="p-8 md:p-10 border-b border-slate-100 flex-shrink-0">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 shrink-0">
                                <i class="bi bi-geo-alt-fill text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800 mb-2">Kantor Pusat</h3>
                                <p class="text-slate-600 leading-relaxed text-lg">
                                    <?= nl2br(htmlspecialchars($address)) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-grow min-h-[400px] relative">
                        <iframe src="<?= htmlspecialchars($map) ?>" 
                                class="absolute inset-0 w-full h-full border-0" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?php if(!empty($page['content'])): ?>
<div class="py-16 bg-white border-t border-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg prose-slate">
        <?= $page['content'] ?>
    </div>
</div>
<?php endif; ?>

<?php
$slot = ob_get_clean();
$navbarWhite = false;
require __DIR__ . '/layout.php';
?>
