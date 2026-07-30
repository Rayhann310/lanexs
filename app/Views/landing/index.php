<?php
// index.php — Landing Page Beranda
// Uses shared landing/layout.php for Navbar & Footer

$pageTitle = APP_NAME . ' - Best of The Best Service';
$pageDescription = htmlspecialchars($heroSubtitle ?? '');

ob_start();
?>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden border-b border-slate-200 dark:border-slate-800 transition-colors flex items-center justify-center min-h-[60vh] lg:min-h-[70vh] max-h-[800px]">
        
        <!-- Hero Background Slider -->
        <div class="absolute inset-0 z-0">
            <div class="swiper heroSwiper w-full h-full">
                <div class="swiper-wrapper">
                    <?php foreach($heroImages as $img): ?>
                    <div class="swiper-slide w-full h-full">
                        <img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Overlay to ensure text readability but keep the banner visible -->
            <div class="absolute inset-0 bg-white/40 dark:bg-slate-900/60 z-10 transition-colors"></div>
            <!-- Additional gradient overlay to make text pop -->
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/60 to-transparent dark:from-slate-900 dark:via-slate-900/80 z-10 transition-colors"></div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 text-center mt-2 lg:mt-10">
            
            <div data-aos="zoom-in" data-aos-duration="1000">
                
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/90 dark:bg-slate-800/90 text-slate-800 dark:text-slate-200 font-bold text-xs tracking-[0.2em] uppercase mb-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/50 dark:border-slate-700/50 transition-all hover:scale-105 cursor-default backdrop-blur-md">
                    <span class="w-2.5 h-2.5 rounded-full bg-secondary mr-2.5 animate-pulse"></span> 
                    Premium B2B Logistics
                </div>
                
                <h1 class="text-5xl sm:text-6xl lg:text-[5rem] font-heading font-black text-slate-900 dark:text-white tracking-tighter mb-6 leading-[1.2] transition-colors drop-shadow-sm flex flex-col items-center justify-center min-h-[120px] sm:min-h-[160px] lg:min-h-[180px]">
                    <span>Solusi Logistik Terbaik Untuk</span>
                    <span class="text-primary relative inline-block mt-2 h-[60px] sm:h-[80px] lg:h-[100px] flex items-center justify-center">
                        <span id="typewriter-text">Perusahaan</span><span class="animate-pulse border-r-[5px] border-primary ml-2 h-[70%]"></span>
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 mb-12 leading-relaxed font-light max-w-2xl mx-auto transition-colors">
                    <?= htmlspecialchars($heroSubtitle) ?>
                </p>
                
                <!-- Tracking Form: Ultra Modern -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-2 md:p-3 rounded-full shadow-[0_20px_50px_rgb(18,123,142,0.15)] dark:shadow-[0_20px_50px_rgb(0,0,0,0.5)] border border-white/50 dark:border-slate-700/50 max-w-2xl mx-auto transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_30px_60px_rgb(18,123,142,0.2)] relative z-20 group">
                    <form action="<?= BASE_URL ?>/tracking" method="GET" class="flex items-center">
                        <div class="pl-6 pr-2 text-primary group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-upc-scan text-2xl"></i>
                        </div>
                        <input type="text" name="resi" class="w-full px-4 py-4 bg-transparent border-none focus:ring-0 outline-none text-slate-800 dark:text-white placeholder-slate-400 font-medium transition-colors text-lg" placeholder="Ketik Nomor Resi Anda..." required autocomplete="off">
                        <button type="submit" class="bg-primary hover:bg-primaryHover text-white px-8 md:px-10 py-4 rounded-full font-bold transition-all duration-300 whitespace-nowrap shadow-lg shadow-primary/40 hover:shadow-primary/60 text-lg flex items-center gap-2 group/btn">
                            <span>Lacak</span>
                            <i class="bi bi-arrow-right-short text-2xl -mr-2 group-hover/btn:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
                
                <div class="mt-16 flex flex-wrap justify-center items-center gap-4 md:gap-8 text-sm font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                    <div class="flex items-center bg-white/50 dark:bg-slate-800/50 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/60 dark:border-slate-700/50 shadow-sm hover:bg-white/80 transition-colors" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center mr-3"><i class="bi bi-shield-check text-primary text-lg"></i></div>
                        Keamanan Ekstra
                    </div>
                    <div class="flex items-center bg-white/50 dark:bg-slate-800/50 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/60 dark:border-slate-700/50 shadow-sm hover:bg-white/80 transition-colors" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center mr-3"><i class="bi bi-clock-history text-secondary text-lg"></i></div>
                        99% SLA Presisi
                    </div>
                    <div class="flex items-center bg-white/50 dark:bg-slate-800/50 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/60 dark:border-slate-700/50 shadow-sm hover:bg-white/80 transition-colors" data-aos="fade-up" data-aos-delay="400">
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center mr-3"><i class="bi bi-globe text-primary text-lg"></i></div>
                        Seluruh Indonesia
                    </div>
                </div>
            </div>

        </div>

        <!-- Floating Decorative Element (Modern SaaS Touch) -->
        <div class="hidden lg:flex absolute right-[5%] top-[20%] bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-4 rounded-3xl border border-white/60 dark:border-slate-700/50 shadow-[0_20px_50px_rgb(0,0,0,0.1)] flex-col items-center gap-2 animate-[bounce_8s_infinite] z-20">
            <div class="w-12 h-12 rounded-full bg-secondary/20 flex items-center justify-center text-secondary text-xl font-black">
                <i class="bi bi-check-all"></i>
            </div>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">ISO 9001</span>
            <span class="text-[10px] text-slate-500 font-medium">Certified</span>
        </div>
    </section>

    <!-- Partners Logos -->
    <!-- Partners Logos -->
    <section class="py-12 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 transition-colors relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-8 transition-colors">Dipercaya Oleh Perusahaan Terkemuka</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 opacity-60 dark:opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                <?php if(empty($partners)): ?>
                    <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800 dark:text-slate-400 transition-colors">Nama Dipercaya Perusahaan</span>
                <?php else: ?>
                    <?php foreach($partners as $partner): ?>
                        <?php $type = $partner['display_type'] ?? 'logo'; ?>
                        
                        <div class="flex flex-col items-center justify-center gap-2 group cursor-default transition-all duration-300 hover:scale-105" <?php if(!empty($partner['description'])) echo 'title="'.htmlspecialchars($partner['description']).'"'; ?>>
                            <?php if($type === 'logo' || $type === 'both'): ?>
                                <?php if(!empty($partner['logo_path']) && file_exists(BASE_PATH . '/public' . $partner['logo_path'])): ?>
                                    <img src="<?= BASE_URL . $partner['logo_path'] ?>" alt="<?= htmlspecialchars($partner['name']) ?>" class="h-8 md:h-12 w-auto object-contain dark:brightness-200 dark:contrast-100 transition-all group-hover:opacity-100 duration-300">
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php if($type === 'text' || $type === 'both'): ?>
                                <span class="text-sm md:text-base font-bold uppercase tracking-widest font-heading text-slate-800 dark:text-slate-200 transition-colors opacity-70 group-hover:opacity-100 group-hover:text-primary"><?= htmlspecialchars($partner['name']) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Statistik Pencapaian (Counter) -->
    <!-- Statistik Pencapaian (Counter) -->
    <section class="py-16 bg-slate-50 dark:bg-slate-900/50 relative overflow-hidden transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-700/50 hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-4xl md:text-5xl font-black font-heading mb-3 text-slate-900 dark:text-white transition-colors">1M+</h4>
                    <p class="text-xs text-primary uppercase tracking-widest font-bold">Paket Terkirim</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-700/50 hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-4xl md:text-5xl font-black font-heading mb-3 text-slate-900 dark:text-white transition-colors">99.9%</h4>
                    <p class="text-xs text-primary uppercase tracking-widest font-bold">SLA Tercapai</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-700/50 hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-4xl md:text-5xl font-black font-heading mb-3 text-slate-900 dark:text-white transition-colors">150+</h4>
                    <p class="text-xs text-primary uppercase tracking-widest font-bold">Kota Tujuan</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-700/50 hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                    <h4 class="text-4xl md:text-5xl font-black font-heading mb-3 text-slate-900 dark:text-white transition-colors">24/7</h4>
                    <p class="text-xs text-primary uppercase tracking-widest font-bold">Support Klien</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-16 md:py-24 bg-white dark:bg-slate-900 relative transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <div class="order-2 lg:order-1" data-aos="fade-up">
                    <div class="grid grid-cols-2 gap-4 lg:gap-6">
                        <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:-translate-y-2 hover:shadow-xl hover:shadow-primary/5 duration-300 group">
                            <h4 class="text-4xl md:text-5xl font-heading font-black text-primary mb-3 transition-transform group-hover:scale-110 origin-left">10+</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium transition-colors">Tahun Pengalaman Logistik</p>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm mt-8 transition-all hover:-translate-y-2 hover:shadow-xl hover:shadow-primary/5 duration-300 group">
                            <h4 class="text-4xl md:text-5xl font-heading font-black text-primary mb-3 transition-transform group-hover:scale-110 origin-left">99%</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium transition-colors">SLA Pengiriman Terpenuhi</p>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:-translate-y-2 hover:shadow-xl hover:shadow-primary/5 duration-300 group">
                            <h4 class="text-4xl md:text-5xl font-heading font-black text-primary mb-3 transition-transform group-hover:scale-110 origin-left">24/7</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium transition-colors">Dukungan Pelanggan Prioritas</p>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm mt-8 transition-all hover:-translate-y-2 hover:shadow-xl hover:shadow-primary/5 duration-300 group">
                            <h4 class="text-4xl md:text-5xl font-heading font-black text-primary mb-3 transition-transform group-hover:scale-110 origin-left">B2B</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium transition-colors">Fokus Penuh Layanan Korporat</p>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2" data-aos="fade-up" data-aos-delay="100">
                    <span class="text-primary font-bold tracking-widest uppercase text-sm mb-3 block">Profil Perusahaan</span>
                    <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 dark:text-white mb-6 leading-tight transition-colors">Berdedikasi Untuk Kemajuan Bisnis Anda.</h2>
                    <p class="text-slate-600 dark:text-slate-400 font-light leading-relaxed mb-6 transition-colors">
                        LANEXS (Lintas Area Nusantara) didirikan dengan visi kuat untuk menjadi mitra strategis dalam rantai pasok bisnis di Indonesia. Kami mengombinasikan keandalan operasional dengan teknologi modern untuk menghadirkan layanan logistik yang presisi.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start" data-aos="fade-right" data-aos-delay="200">
                            <div class="mt-1 w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0 mr-4 transition-colors">
                                <i class="bi bi-check2"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800 dark:text-slate-200 transition-colors">Visi</h5>
                                <p class="text-sm text-slate-600 dark:text-slate-400 font-light mt-1 transition-colors">Menjadi pionir logistik modern yang paling diandalkan di tingkat nasional.</p>
                            </div>
                        </div>
                        <div class="flex items-start" data-aos="fade-right" data-aos-delay="300">
                            <div class="mt-1 w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0 mr-4 transition-colors">
                                <i class="bi bi-check2"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800 dark:text-slate-200 transition-colors">Misi</h5>
                                <p class="text-sm text-slate-600 dark:text-slate-400 font-light mt-1 transition-colors">Menyediakan layanan pengiriman multi-moda yang cepat, aman, dan inovatif.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-16 md:py-24 bg-slate-50 dark:bg-slate-900/50 border-y border-slate-200 dark:border-slate-800 transition-colors relative z-10 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16" data-aos="fade-up">
                <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Layanan Utama</span>
                <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 dark:text-white mb-6 transition-colors tracking-tight">Solusi Ekspedisi Komprehensif</h2>
                <p class="text-slate-500 dark:text-slate-400 font-light text-lg transition-colors">Layanan terpadu yang dirancang khusus untuk memenuhi kebutuhan distribusi barang perusahaan skala menengah hingga besar.</p>
            </div>
            
            <div class="swiper servicesSwiper !overflow-visible md:!overflow-hidden pb-10 md:pb-0">
                <div class="swiper-wrapper md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-8">
                    <!-- Card 1 -->
                    <div class="swiper-slide md:!w-auto md:!mr-0 bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden h-full flex flex-col" data-aos="fade-up" data-aos-delay="100">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-primary/10 to-transparent rounded-bl-full -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-150"></div>
                        <div class="w-16 h-16 bg-primary/10 rounded-[1.25rem] flex items-center justify-center text-3xl text-primary mb-8 transition-all group-hover:scale-110 group-hover:rotate-6 duration-300 relative z-10 shadow-sm border border-primary/20">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h3 class="text-2xl font-black font-heading text-slate-900 dark:text-white mb-4 transition-colors">Cargo Darat & Laut</h3>
                        <p class="text-slate-600 dark:text-slate-400 font-light text-sm leading-relaxed transition-colors flex-grow">
                            Pengiriman reguler (RES) dan ekspres (ES) lintas pulau. Melayani model FTL (Full Truck Load) maupun LTL dengan keamanan armada terpantau.
                        </p>
                    </div>
                    
                    <!-- Card 2 -->
                    <div class="swiper-slide md:!w-auto md:!mr-0 bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden h-full flex flex-col" data-aos="fade-up" data-aos-delay="200">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-secondary/10 to-transparent rounded-bl-full -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-150"></div>
                        <div class="w-16 h-16 bg-secondary/10 rounded-[1.25rem] flex items-center justify-center text-3xl text-secondary mb-8 transition-all group-hover:scale-110 group-hover:rotate-6 duration-300 relative z-10 shadow-sm border border-secondary/20">
                            <i class="bi bi-airplane-engines"></i>
                        </div>
                        <h3 class="text-2xl font-black font-heading text-slate-900 dark:text-white mb-4 transition-colors">Top Urgent Service</h3>
                        <p class="text-slate-600 dark:text-slate-400 font-light text-sm leading-relaxed transition-colors flex-grow">
                            Layanan prioritas tinggi via kargo udara. Solusi tepat untuk pengiriman dokumen penting, alat medis, atau barang berharga dengan SLA 1x24 jam.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="swiper-slide md:!w-auto md:!mr-0 bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden h-full flex flex-col" data-aos="fade-up" data-aos-delay="300">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-primary/10 to-transparent rounded-bl-full -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-150"></div>
                        <div class="w-16 h-16 bg-primary/10 rounded-[1.25rem] flex items-center justify-center text-3xl text-primary mb-8 transition-all group-hover:scale-110 group-hover:rotate-6 duration-300 relative z-10 shadow-sm border border-primary/20">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h3 class="text-2xl font-black font-heading text-slate-900 dark:text-white mb-4 transition-colors">Gudang & Packing</h3>
                        <p class="text-slate-600 dark:text-slate-400 font-light text-sm leading-relaxed transition-colors flex-grow">
                            Manajemen WMS akurat, layanan pickup massal, serta repacking (kayu/bubble wrap) yang memenuhi standar proteksi keamanan tinggi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Kerja Section -->
    <section class="py-16 md:py-24 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 transition-colors relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 md:mb-20" data-aos="fade-up">
                <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Cara Kerja</span>
                <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 dark:text-white mb-6 transition-colors tracking-tight">Pengiriman Mudah & Transparan</h2>
                <p class="text-slate-500 dark:text-slate-400 font-light text-lg transition-colors">Hanya 4 langkah mudah dari penjemputan hingga barang tiba di tujuan dengan aman.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-8 relative">
                <!-- Connecting Line Desktop -->
                <div class="hidden md:block absolute top-[4.5rem] left-[10%] w-[80%] h-[2px] bg-slate-100 dark:bg-slate-800 z-0">
                    <div class="h-full bg-gradient-to-r from-primary to-secondary w-full origin-left transform scale-x-0 transition-transform duration-1000 delay-500" data-aos="fade-right"></div>
                </div>
                
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-28 h-28 mx-auto bg-white dark:bg-slate-900 border-4 border-slate-100 dark:border-slate-800 rounded-full flex items-center justify-center relative z-10 mb-6 shadow-sm group-hover:border-primary/50 group-hover:-translate-y-2 group-hover:shadow-lg transition-all duration-300">
                        <i class="bi bi-box-seam text-4xl text-slate-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                        <div class="absolute -bottom-3 right-0 w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center font-bold text-xs text-slate-500 dark:text-slate-400 border-[3px] border-white dark:border-slate-900">1</div>
                    </div>
                    <h4 class="font-bold font-heading text-slate-900 dark:text-white text-xl mb-3">Booking & Pickup</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-light leading-relaxed px-2">Buat pesanan via sistem kami, tim akan langsung menjemput barang di lokasi Anda.</p>
                </div>
                
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-28 h-28 mx-auto bg-white dark:bg-slate-900 border-4 border-slate-100 dark:border-slate-800 rounded-full flex items-center justify-center relative z-10 mb-6 shadow-sm group-hover:border-primary/50 group-hover:-translate-y-2 group-hover:shadow-lg transition-all duration-300">
                        <i class="bi bi-check2-square text-4xl text-slate-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                        <div class="absolute -bottom-3 right-0 w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center font-bold text-xs text-slate-500 dark:text-slate-400 border-[3px] border-white dark:border-slate-900">2</div>
                    </div>
                    <h4 class="font-bold font-heading text-slate-900 dark:text-white text-xl mb-3">Sortir & Packing</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-light leading-relaxed px-2">Barang diverifikasi, diukur, dan dipacking ulang sesuai standar keselamatan tinggi.</p>
                </div>
                
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-28 h-28 mx-auto bg-white dark:bg-slate-900 border-4 border-slate-100 dark:border-slate-800 rounded-full flex items-center justify-center relative z-10 mb-6 shadow-sm group-hover:border-primary/50 group-hover:-translate-y-2 group-hover:shadow-lg transition-all duration-300">
                        <i class="bi bi-truck text-4xl text-slate-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                        <div class="absolute -bottom-3 right-0 w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center font-bold text-xs text-slate-500 dark:text-slate-400 border-[3px] border-white dark:border-slate-900">3</div>
                    </div>
                    <h4 class="font-bold font-heading text-slate-900 dark:text-white text-xl mb-3">Transit Aman</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-light leading-relaxed px-2">Proses pengiriman via udara, laut, atau darat secara aman dan terpantau *real-time*.</p>
                </div>
                
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-28 h-28 mx-auto bg-primary border-[6px] border-primary/20 rounded-full flex items-center justify-center relative z-10 mb-6 shadow-xl shadow-primary/30 text-white transition-all duration-300 group-hover:scale-110">
                        <i class="bi bi-house-check text-4xl"></i>
                        <div class="absolute -bottom-3 right-0 w-8 h-8 bg-secondary rounded-full flex items-center justify-center font-bold text-xs text-slate-900 border-[3px] border-white dark:border-slate-900">4</div>
                    </div>
                    <h4 class="font-bold font-heading text-slate-900 dark:text-white text-xl mb-3">Tiba di Tujuan</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-light leading-relaxed px-2">Barang sampai di lokasi tujuan dengan tepat waktu dan Bukti Penerimaan valid.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="keunggulan" class="py-16 md:py-24 bg-white dark:bg-slate-900 relative transition-colors overflow-hidden">
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1/3 h-1/2 bg-primary/5 dark:bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 md:gap-16 items-center">
                <div data-aos="fade-right">
                    <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Nilai Lebih</span>
                    <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 dark:text-white mb-6 leading-tight transition-colors tracking-tight">Mengapa Memilih LANEXS?</h2>
                    <p class="text-slate-600 dark:text-slate-400 font-light text-lg leading-relaxed mb-10 transition-colors">
                        Berbeda dari ekspedisi ritel konvensional, LANEXS diformulasikan khusus untuk menangani kerumitan rantai pasok B2B dengan pendekatan yang terstruktur, transparan, dan dapat diandalkan.
                    </p>
                    <a href="<?= BASE_URL ?>/page/kontak-kami" class="inline-flex items-center px-8 py-4 bg-slate-900 dark:bg-primary text-white font-bold rounded-full shadow-xl hover:shadow-slate-900/20 dark:hover:shadow-primary/40 hover:-translate-y-1 transition-all group">
                        Hubungi Tim Sales Kami <i class="bi bi-arrow-right ml-3 text-xl group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6" data-aos="fade-left">
                    <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2 duration-300 group relative overflow-hidden">
                        <div class="w-14 h-14 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm mb-6 transition-all group-hover:scale-110 origin-left border border-slate-100 dark:border-slate-800 group-hover:bg-primary/10 group-hover:border-primary/20">
                            <i class="bi bi-shield-check text-primary text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-xl mb-2 transition-colors group-hover:text-primary">Keamanan Ekstra</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-light transition-colors leading-relaxed">Asuransi komprehensif dan standar penanganan (*handling*) barang yang ketat.</p>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2 duration-300 group relative overflow-hidden mt-0 sm:mt-8">
                        <div class="w-14 h-14 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm mb-6 transition-all group-hover:scale-110 origin-left border border-slate-100 dark:border-slate-800 group-hover:bg-primary/10 group-hover:border-primary/20">
                            <i class="bi bi-lightning-charge-fill text-primary text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-xl mb-2 transition-colors group-hover:text-primary">Tepat Waktu</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-light transition-colors leading-relaxed">Komitmen kuat pada Service Level Agreement (SLA) dengan tingkat keberhasilan tinggi.</p>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2 duration-300 group relative overflow-hidden">
                        <div class="w-14 h-14 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm mb-6 transition-all group-hover:scale-110 origin-left border border-slate-100 dark:border-slate-800 group-hover:bg-primary/10 group-hover:border-primary/20">
                            <i class="bi bi-phone-vibrate text-primary text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-xl mb-2 transition-colors group-hover:text-primary">Real-time Tracking</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-light transition-colors leading-relaxed">Pantau status dan lokasi barang secara presisi melalui sistem kami yang transparan.</p>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2 duration-300 group relative overflow-hidden mt-0 sm:mt-8">
                        <div class="w-14 h-14 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm mb-6 transition-all group-hover:scale-110 origin-left border border-slate-100 dark:border-slate-800 group-hover:bg-primary/10 group-hover:border-primary/20">
                            <i class="bi bi-headset text-primary text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-xl mb-2 transition-colors group-hover:text-primary">Support Prioritas</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-light transition-colors leading-relaxed">Akun manajer khusus untuk menangani kebutuhan unik logistik perusahaan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section id="testimoni" class="py-16 md:py-24 bg-slate-50 dark:bg-slate-900/50 relative border-y border-slate-100 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16" data-aos="fade-up">
                <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Testimoni Klien</span>
                <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 dark:text-white mb-4 transition-colors tracking-tight">Ulasan Mitra Bisnis</h2>
            </div>
            
            <div class="swiper testSwiper pb-12" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper cursor-grab active:cursor-grabbing">
                    <?php if(empty($testimonials)): ?>
                        <div class="swiper-slide bg-white dark:bg-slate-800 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm transition-colors text-center">
                            <p class="text-slate-500 dark:text-slate-400 font-light text-sm leading-relaxed transition-colors">Belum ada testimoni.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($testimonials as $testi): ?>
                        <div class="swiper-slide bg-white dark:bg-slate-800 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700 transition-all hover:-translate-y-2 hover:shadow-xl hover:shadow-primary/5 group flex flex-col h-full">
                            <?php $type = $testi['display_type'] ?? 'text'; ?>
                            
                            <?php if($type === 'logo'): ?>
                                <div class="flex-1 flex flex-col items-center justify-center p-4">
                                    <?php if(!empty($testi['logo'])): ?>
                                        <img src="<?= BASE_URL . $testi['logo'] ?>" alt="<?= htmlspecialchars($testi['name']) ?>" class="max-h-24 max-w-full object-contain grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300">
                                    <?php endif; ?>
                                    <h4 class="font-bold text-slate-900 dark:text-white transition-colors mt-4 text-center group-hover:text-primary"><?= htmlspecialchars($testi['name']) ?></h4>
                                </div>
                            <?php else: ?>
                                <!-- Text or Both -->
                                <div class="flex text-secondary mb-5 text-sm gap-1">
                                    <?php for($i=0; $i<($testi['rating']?:5); $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                </div>
                                <p class="text-slate-700 dark:text-slate-300 font-light text-base leading-relaxed mb-6 transition-colors italic flex-1">"<?= htmlspecialchars($testi['content']) ?>"</p>
                                
                                <div class="flex items-center pt-4 border-t border-slate-100 dark:border-slate-700/50 mt-auto">
                                    <?php if($type === 'both' && !empty($testi['logo'])): ?>
                                        <div class="w-16 h-12 mr-4 flex-shrink-0 flex items-center justify-center">
                                            <img src="<?= BASE_URL . $testi['logo'] ?>" class="max-w-full max-h-full object-contain">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center font-bold text-sm mr-4 uppercase text-primary border border-primary/20">
                                            <?= htmlspecialchars($testi['avatar_initials']) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <h4 class="font-bold text-slate-900 dark:text-white transition-colors leading-tight mb-1"><?= htmlspecialchars($testi['name']) ?></h4>
                                        <?php if(!empty($testi['position'])): ?>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium transition-colors"><?= htmlspecialchars($testi['position']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- CTA Banner Section -->
    <section class="py-12 md:py-20 bg-white dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 dark:bg-slate-800 rounded-[2.5rem] overflow-hidden relative shadow-2xl" data-aos="zoom-in" data-aos-duration="1000">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-gradient-to-br from-primary to-secondary rounded-full blur-[80px] opacity-30 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[20rem] h-[20rem] bg-secondary rounded-full blur-[60px] opacity-20 translate-y-1/3 -translate-x-1/4 pointer-events-none"></div>
                
                <div class="relative z-10 p-10 md:p-20 flex flex-col md:flex-row items-center justify-between text-center md:text-left gap-10">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl md:text-5xl font-heading font-black text-white mb-6 leading-[1.2] tracking-tight">Tingkatkan Efisiensi Logistik Perusahaan Anda.</h2>
                        <p class="text-slate-300 font-light text-lg">Bergabunglah dengan ratusan perusahaan lain yang telah mempercayakan pengiriman B2B mereka kepada sistem handal LANEXS.</p>
                    </div>
                    <div class="shrink-0">
                        <a href="<?= BASE_URL ?>/page/kontak-kami" class="inline-flex items-center justify-center bg-white text-slate-900 font-bold px-8 py-5 rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 text-lg group">
                            Hubungi Tim Kami
                            <i class="bi bi-arrow-right-short text-3xl -mr-2 ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-16 md:py-24 bg-slate-50 dark:bg-slate-900/30 relative transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700/50 overflow-hidden shadow-2xl shadow-slate-200/50 dark:shadow-none transition-colors duration-300 relative" data-aos="fade-up">
                
                <div class="grid grid-cols-1 lg:grid-cols-5 h-full">
                    
                    <!-- Contact Info -->
                    <div class="p-8 md:p-12 lg:p-16 lg:col-span-2 flex flex-col justify-center relative z-10 bg-white dark:bg-slate-800">
                        <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Konsultasi</span>
                        <h2 class="text-4xl font-heading font-black text-slate-900 dark:text-white mb-4 tracking-tight transition-colors">Hubungi Kami</h2>
                        <p class="text-slate-500 dark:text-slate-400 mb-12 text-sm font-light leading-relaxed transition-colors">Punya pertanyaan seputar layanan kami atau ingin konsultasi kerjasama pengiriman B2B?</p>
                        
                        <ul class="space-y-8">
                            <li class="flex items-start group">
                                <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700/50 rounded-2xl flex items-center justify-center text-primary shrink-0 mr-5 transition-all group-hover:bg-primary group-hover:text-white group-hover:shadow-lg group-hover:shadow-primary/30 group-hover:scale-110">
                                    <i class="bi bi-geo-alt-fill text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-1 transition-colors group-hover:text-primary">Headquarter</h5>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed font-light transition-colors"><?= $contactAddress ?></p>
                                </div>
                            </li>
                            <li class="flex items-start group">
                                <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700/50 rounded-2xl flex items-center justify-center text-primary shrink-0 mr-5 transition-all group-hover:bg-primary group-hover:text-white group-hover:shadow-lg group-hover:shadow-primary/30 group-hover:scale-110">
                                    <i class="bi bi-telephone-fill text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-1 transition-colors group-hover:text-primary">Call Center</h5>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed font-light transition-colors"><?= $contactPhone ?></p>
                                </div>
                            </li>
                            <li class="flex items-start group">
                                <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700/50 rounded-2xl flex items-center justify-center text-primary shrink-0 mr-5 transition-all group-hover:bg-primary group-hover:text-white group-hover:shadow-lg group-hover:shadow-primary/30 group-hover:scale-110">
                                    <i class="bi bi-envelope-fill text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-1 transition-colors group-hover:text-primary">Email Support</h5>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm font-light transition-colors"><?= htmlspecialchars($contactEmail) ?></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Map -->
                    <div class="relative h-[400px] lg:h-auto lg:col-span-3 bg-slate-100 dark:bg-slate-900 transition-colors">
                        <iframe src="<?= htmlspecialchars(explode('"', explode('src="', $contactMap)[1] ?? $contactMap)[0]) ?>" 
                            class="absolute inset-0 w-full h-full border-0 dark:opacity-70 dark:invert-[90%] dark:hue-rotate-180 dark:mix-blend-screen transition-all duration-300 dark:hover:opacity-100" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
    var servicesSwiper = new Swiper(".servicesSwiper", {
        slidesPerView: 1.15,
        spaceBetween: 16,
        breakpoints: {
            768: {
                enabled: false,
                spaceBetween: 0,
            }
        }
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
    // Typewriter Effect
    const words = ["Pergudangan", "Kargo Udara", "Kargo Darat", "Perusahaan", "E-Commerce"];
    let i = 0;
    let timer;

    function typingEffect() {
        let word = words[i].split("");
        var loopTyping = function() {
            if (word.length > 0) {
                document.getElementById("typewriter-text").innerHTML += word.shift();
            } else {
                setTimeout(deletingEffect, 2000);
                return false;
            }
            timer = setTimeout(loopTyping, 100);
        };
        loopTyping();
    }

    function deletingEffect() {
        let word = words[i].split("");
        var loopDeleting = function() {
            if (word.length > 0) {
                word.pop();
                document.getElementById("typewriter-text").innerHTML = word.join("");
            } else {
                if (words.length > (i + 1)) {
                    i++;
                } else {
                    i = 0;
                }
                setTimeout(typingEffect, 500);
                return false;
            }
            timer = setTimeout(loopDeleting, 50);
        };
        loopDeleting();
    }
    
    // Clear initial text and start typing
    setTimeout(function() {
        document.getElementById("typewriter-text").innerHTML = "";
        typingEffect();
    }, 1000);
</script>
';

require __DIR__ . '/layout.php';
