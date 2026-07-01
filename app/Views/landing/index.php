<?php
// index.php — Landing Page Beranda
// Uses shared landing/layout.php for Navbar & Footer

$pageTitle = APP_NAME . ' - Best of The Best Service';
$pageDescription = htmlspecialchars($heroSubtitle ?? '');

ob_start();
?>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 hero-bg overflow-hidden border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Hero Text -->
                <div data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-200 text-slate-700 font-semibold text-xs tracking-wide uppercase mb-6">
                        <span class="w-2 h-2 rounded-full bg-secondary mr-2"></span> Logistic • Cargo • Courier
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-heading font-black text-slate-900 tracking-tight mb-6 leading-tight">
                        <?= htmlspecialchars($heroTitle) ?>
                    </h1>
                    
                    <p class="text-lg text-slate-600 mb-10 leading-relaxed font-light max-w-lg">
                        <?= htmlspecialchars($heroSubtitle) ?>
                    </p>
                    
                    <!-- Tracking Form -->
                    <div class="bg-white p-2 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200 max-w-md">
                        <form action="<?= BASE_URL ?>/tracking" method="GET" class="flex items-center">
                            <div class="pl-4 text-slate-400">
                                <i class="bi bi-box-seam text-lg"></i>
                            </div>
                            <input type="text" name="resi" class="w-full pl-3 pr-4 py-3 bg-transparent border-none focus:ring-0 outline-none text-slate-700 placeholder-slate-400 font-medium" placeholder="Masukkan Nomor Resi..." required>
                            <button type="submit" class="bg-primary hover:bg-primaryHover text-white px-6 py-3 rounded-lg font-semibold transition-colors whitespace-nowrap">
                                Lacak
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-8 flex items-center gap-6 text-sm font-medium text-slate-500">
                        <div class="flex items-center"><i class="bi bi-check-circle-fill text-secondary mr-2"></i> Aman</div>
                        <div class="flex items-center"><i class="bi bi-check-circle-fill text-secondary mr-2"></i> Cepat</div>
                        <div class="flex items-center"><i class="bi bi-check-circle-fill text-secondary mr-2"></i> Terpercaya</div>
                    </div>
                </div>

                <!-- Hero Image Slider (Self-Healing logic provides default image if empty) -->
                <div class="relative h-[400px] lg:h-[500px] rounded-2xl overflow-hidden shadow-2xl" data-aos="fade-left" data-aos-duration="1200">
                    
                    <div class="swiper heroSwiper w-full h-full">
                        <div class="swiper-wrapper">
                            <?php foreach($heroImages as $img): ?>
                            <div class="swiper-slide w-full h-full">
                                <img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-primary/20 mix-blend-multiply z-10 pointer-events-none"></div>
                    
                    <!-- Floating badge -->
                    <div class="absolute bottom-6 left-6 bg-white px-6 py-4 rounded-xl shadow-lg border border-slate-100 flex items-center gap-4 z-20">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-secondary text-2xl">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Profesional</p>
                            <p class="text-slate-900 font-bold text-lg">Layanan B2B</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Partners Logos -->
    <section class="py-10 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-6">Dipercaya Oleh Perusahaan Terkemuka</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-50 grayscale">
                <?php if(empty($partners)): ?>
                    <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800">Nama Dipercaya Perusahaan</span>
                <?php else: ?>
                    <?php foreach($partners as $partner): ?>
                        <?php if(!empty($partner['logo_path']) && file_exists(BASE_PATH . '/public' . $partner['logo_path'])): ?>
                            <img src="<?= BASE_URL . $partner['logo_path'] ?>" alt="<?= htmlspecialchars($partner['name']) ?>" class="h-12 w-auto object-contain">
                        <?php else: ?>
                            <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800"><?= htmlspecialchars($partner['name']) ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <div class="order-2 lg:order-1" data-aos="fade-up">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <h4 class="text-4xl font-heading font-black text-primary mb-2">10+</h4>
                            <p class="text-sm text-slate-500 font-medium">Tahun Pengalaman Logistik</p>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 mt-8">
                            <h4 class="text-4xl font-heading font-black text-primary mb-2">99%</h4>
                            <p class="text-sm text-slate-500 font-medium">SLA Pengiriman Terpenuhi</p>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <h4 class="text-4xl font-heading font-black text-primary mb-2">24/7</h4>
                            <p class="text-sm text-slate-500 font-medium">Dukungan Pelanggan</p>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 mt-8">
                            <h4 class="text-4xl font-heading font-black text-primary mb-2">B2B</h4>
                            <p class="text-sm text-slate-500 font-medium">Fokus Layanan Korporat</p>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2" data-aos="fade-up" data-aos-delay="100">
                    <span class="text-secondary font-bold tracking-widest uppercase text-sm mb-3 block">Profil Perusahaan</span>
                    <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 mb-6 leading-tight">Berdedikasi Untuk Kemajuan Bisnis Anda.</h2>
                    <p class="text-slate-600 font-light leading-relaxed mb-6">
                        LANEXS (Lintas Area Nusantara) didirikan dengan visi kuat untuk menjadi mitra strategis dalam rantai pasok bisnis di Indonesia. Kami mengombinasikan keandalan operasional dengan teknologi modern untuk menghadirkan layanan logistik yang presisi.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="mt-1 w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0 mr-4">
                                <i class="bi bi-check2"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800">Visi</h5>
                                <p class="text-sm text-slate-600 font-light mt-1">Menjadi pionir logistik modern yang paling diandalkan di tingkat nasional.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="mt-1 w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0 mr-4">
                                <i class="bi bi-check2"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800">Misi</h5>
                                <p class="text-sm text-slate-600 font-light mt-1">Menyediakan layanan pengiriman multi-moda yang cepat, aman, dan inovatif.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-24 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-primary font-bold tracking-widest uppercase text-sm mb-3 block">Layanan Utama</span>
                <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 mb-4">Solusi Ekspedisi Komprehensif</h2>
                <p class="text-slate-500 font-light">Layanan terpadu yang dirancang khusus untuk memenuhi kebutuhan distribusi barang perusahaan skala menengah hingga besar.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 transition-all duration-300 accent-border-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-2xl text-primary mb-6">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Cargo Darat & Laut</h3>
                    <p class="text-slate-600 font-light text-sm leading-relaxed">
                        Pengiriman reguler (RES) dan ekspres (ES) lintas pulau. Melayani model FTL (Full Truck Load) maupun LTL dengan keamanan armada terpantau.
                    </p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 transition-all duration-300 accent-border-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-2xl text-primary mb-6">
                        <i class="bi bi-airplane-engines"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Top Urgent Service (Udara)</h3>
                    <p class="text-slate-600 font-light text-sm leading-relaxed">
                        Layanan prioritas tinggi via kargo udara. Solusi tepat untuk pengiriman dokumen penting, alat medis, atau barang berharga dengan SLA 1x24 jam.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 transition-all duration-300 accent-border-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-2xl text-primary mb-6">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pergudangan & Packing</h3>
                    <p class="text-slate-600 font-light text-sm leading-relaxed">
                        Manajemen WMS yang akurat, fasilitas *pickup* barang massal, serta layanan *repacking* (kayu/bubble wrap) sesuai standar keselamatan tinggi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="keunggulan" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <div data-aos="fade-right">
                    <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 mb-6 leading-tight">Mengapa Memilih LANEXS?</h2>
                    <p class="text-slate-600 font-light leading-relaxed mb-8">
                        Berbeda dari ekspedisi ritel konvensional, LANEXS diformulasikan khusus untuk menangani kerumitan rantai pasok B2B dengan pendekatan yang terstruktur, transparan, dan dapat diandalkan.
                    </p>
                    <a href="#kontak" class="inline-flex items-center px-6 py-3 bg-slate-900 text-white font-semibold rounded-lg hover:bg-slate-800 transition-colors">
                        Hubungi Tim Sales Kami <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" data-aos="fade-left">
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <i class="bi bi-shield-check text-secondary text-3xl mb-4 block"></i>
                        <h4 class="font-bold text-slate-900 mb-2">Keamanan Ekstra</h4>
                        <p class="text-sm text-slate-600 font-light">Asuransi komprehensif dan standar *handling* barang yang ketat.</p>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <i class="bi bi-lightning-charge-fill text-secondary text-3xl mb-4 block"></i>
                        <h4 class="font-bold text-slate-900 mb-2">Tepat Waktu</h4>
                        <p class="text-sm text-slate-600 font-light">Komitmen kuat pada SLA dengan tingkat keberhasilan tinggi.</p>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <i class="bi bi-phone-vibrate text-secondary text-3xl mb-4 block"></i>
                        <h4 class="font-bold text-slate-900 mb-2">Real-time Tracking</h4>
                        <p class="text-sm text-slate-600 font-light">Pantau status dan lokasi barang secara presisi melalui sistem kami.</p>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <i class="bi bi-headset text-secondary text-3xl mb-4 block"></i>
                        <h4 class="font-bold text-slate-900 mb-2">Support Prioritas</h4>
                        <p class="text-sm text-slate-600 font-light">Akun manajer khusus untuk menangani kebutuhan perusahaan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section id="testimoni" class="py-24 bg-slate-900 text-white relative border-y border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-secondary font-bold tracking-widest uppercase text-sm mb-3 block">Testimoni Klien</span>
                <h2 class="text-3xl md:text-4xl font-heading font-black mb-4">Ulasan Mitra Bisnis</h2>
            </div>
            
            <div class="swiper testSwiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    <?php if(empty($testimonials)): ?>
                        <div class="swiper-slide bg-slate-800 p-8 rounded-2xl border border-slate-700">
                            <p class="text-slate-300 font-light text-sm leading-relaxed mb-6">Belum ada testimoni.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($testimonials as $testi): ?>
                        <div class="swiper-slide bg-slate-800 p-8 rounded-2xl border border-slate-700">
                            <div class="flex text-secondary mb-4 text-sm">
                                <?php for($i=0; $i<$testi['rating']; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                            </div>
                            <p class="text-slate-300 font-light text-sm leading-relaxed mb-6">"<?= htmlspecialchars($testi['content']) ?>"</p>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center font-bold text-sm mr-3 uppercase"><?= htmlspecialchars($testi['avatar_initials']) ?></div>
                                <div>
                                    <h4 class="font-bold text-sm"><?= htmlspecialchars($testi['name']) ?></h4>
                                    <?php if(!empty($testi['position'])): ?>
                                        <p class="text-xs text-slate-400"><?= htmlspecialchars($testi['position']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination !relative !mt-8"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-50 rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm" data-aos="fade-up">
                <div class="grid grid-cols-1 lg:grid-cols-5">
                    
                    <!-- Contact Info -->
                    <div class="p-10 lg:p-14 lg:col-span-2 flex flex-col justify-center bg-white border-b lg:border-b-0 lg:border-r border-slate-200">
                        <h2 class="text-3xl font-heading font-black text-slate-900 mb-4 tracking-tight">Hubungi Kami</h2>
                        <p class="text-slate-500 mb-10 text-sm font-light leading-relaxed">Punya pertanyaan seputar layanan kami atau ingin konsultasi kerjasama pengiriman B2B?</p>
                        
                        <ul class="space-y-6">
                            <li class="flex items-start">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-primary shrink-0 mr-4">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 text-sm">Headquarter</h5>
                                    <p class="text-slate-500 text-sm mt-1 leading-relaxed font-light"><?= $contactAddress ?></p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-primary shrink-0 mr-4">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 text-sm">Call Center</h5>
                                    <p class="text-slate-500 text-sm mt-1 leading-relaxed font-light"><?= $contactPhone ?></p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-primary shrink-0 mr-4">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 text-sm">Email Support</h5>
                                    <p class="text-slate-500 text-sm mt-1 font-light"><?= htmlspecialchars($contactEmail) ?></p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Map -->
                    <div class="relative h-[400px] lg:h-auto lg:col-span-3 bg-slate-200">
                        <iframe src="<?= htmlspecialchars(explode('"', explode('src="', $contactMap)[1] ?? $contactMap)[0]) ?>" 
                            class="absolute inset-0 w-full h-full border-0 grayscale opacity-80" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                </div>
            </div>
        </div>
    </section>


<?php
$slot = ob_get_clean();

$extraScripts = '
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var heroSlideCount = document.querySelectorAll(".heroSwiper .swiper-slide").length;
    var heroSwiper = new Swiper(".heroSwiper", {
        effect: "fade",
        autoplay: { delay: 3500, disableOnInteraction: false },
        loop: heroSlideCount > 1,
        allowTouchMove: false
    });
    var testSwiper = new Swiper(".testSwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: { el: ".swiper-pagination", clickable: true },
        breakpoints: {
            768: { slidesPerView: 2, spaceBetween: 30 },
            1024: { slidesPerView: 3, spaceBetween: 40 }
        }
    });
</script>
';

require __DIR__ . '/layout.php';
