<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Best of The Best Service</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/favicon.ico">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#107c8c', // Deep Teal from Logo
                        primaryHover: '#0d6573',
                        secondary: '#f3aa00', // Amber Yellow from Logo
                        secondaryHover: '#d99700',
                        darkBg: '#1e293b' // Slate-800 for footer
                    },
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        .nav-scrolled {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .hero-bg {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 32px 32px;
        }
        /* Custom scrollbar for marquees if any */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .accent-border-hover:hover {
            border-bottom: 4px solid #f3aa00;
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-700 bg-slate-50 selection:bg-primary selection:text-white overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 py-3" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <a href="<?= BASE_URL ?>/" class="flex items-center space-x-2">
                    <img src="<?= BASE_URL ?>/assets/images/a.png" alt="<?= APP_NAME ?>" class="h-12 w-auto object-contain">
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden lg:flex space-x-8 items-center">
                    <a href="#beranda" class="text-slate-600 hover:text-primary font-medium transition-colors text-sm">Beranda</a>
                    <a href="#tentang" class="text-slate-600 hover:text-primary font-medium transition-colors text-sm">Profil</a>
                    <a href="#layanan" class="text-slate-600 hover:text-primary font-medium transition-colors text-sm">Layanan</a>
                    <a href="#keunggulan" class="text-slate-600 hover:text-primary font-medium transition-colors text-sm">Keunggulan</a>
                    <a href="#testimoni" class="text-slate-600 hover:text-primary font-medium transition-colors text-sm">Testimoni</a>
                    <a href="#kontak" class="text-slate-600 hover:text-primary font-medium transition-colors text-sm">Kontak</a>
                </div>
                
                <div class="hidden lg:flex items-center">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:bg-primaryHover transition-colors shadow-sm flex items-center">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="px-6 py-2.5 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-colors flex items-center">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Login ERP
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-800 p-2 focus:outline-none">
                        <i class="bi bi-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 absolute w-full shadow-xl transition-all origin-top left-0 top-full">
            <div class="flex flex-col px-6 pt-4 pb-6 space-y-2">
                <a href="#beranda" class="text-slate-700 font-medium py-2 border-b border-slate-50">Beranda</a>
                <a href="#tentang" class="text-slate-700 font-medium py-2 border-b border-slate-50">Profil</a>
                <a href="#layanan" class="text-slate-700 font-medium py-2 border-b border-slate-50">Layanan</a>
                <a href="#keunggulan" class="text-slate-700 font-medium py-2 border-b border-slate-50">Keunggulan</a>
                <a href="#testimoni" class="text-slate-700 font-medium py-2 border-b border-slate-50">Testimoni</a>
                <a href="#kontak" class="text-slate-700 font-medium py-2 border-b border-slate-50">Kontak</a>
                
                <div class="pt-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="w-full flex justify-center items-center bg-primary text-white px-4 py-3 rounded-lg font-semibold">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="w-full flex justify-center items-center bg-primary text-white px-4 py-3 rounded-lg font-semibold">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Login ERP
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

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
                        Best of The Best <br>
                        <span class="text-primary">Service.</span>
                    </h1>
                    
                    <p class="text-lg text-slate-600 mb-10 leading-relaxed font-light max-w-lg">
                        <?= htmlspecialchars($heroSubtitle) ?> Solusi ekspedisi terpercaya dengan komitmen penuh pada keamanan, kecepatan, dan kepuasan pelanggan.
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

                <!-- Hero Image -->
                <div class="relative lg:h-[500px] rounded-2xl overflow-hidden shadow-2xl" data-aos="fade-left" data-aos-duration="1200">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c83a00?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Logistics Operations" class="w-full h-full object-cover">
                    <!-- Overlay to make it less bright and more corporate -->
                    <div class="absolute inset-0 bg-primary/20 mix-blend-multiply"></div>
                    
                    <!-- Floating badge -->
                    <div class="absolute bottom-6 left-6 bg-white px-6 py-4 rounded-xl shadow-lg border border-slate-100 flex items-center gap-4">
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
                <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800">RetailCo.</span>
                <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800">PharmaCorp</span>
                <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800">TechMakers</span>
                <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800">AgroTrade</span>
                <span class="text-xl font-black uppercase tracking-widest font-heading text-slate-800">GlobalMarket</span>
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
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex text-secondary mb-4 text-sm">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-slate-300 font-light text-sm leading-relaxed mb-6">"Distribusi produk ritel kami ke seluruh provinsi menjadi sangat mudah. Sistem tracking LANEXS terintegrasi sempurna dengan kebutuhan operasional kami."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center font-bold text-sm mr-3">AB</div>
                        <div>
                            <h4 class="font-bold text-sm">Andi Budi</h4>
                            <p class="text-xs text-slate-400">Manager Operasional, RetailCo</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex text-secondary mb-4 text-sm">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-slate-300 font-light text-sm leading-relaxed mb-6">"Harga kargo yang kompetitif dipadu dengan keandalan pengiriman. Sangat efisien untuk pengiriman bahan baku manufaktur dalam skala besar."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center font-bold text-sm mr-3">SM</div>
                        <div>
                            <h4 class="font-bold text-sm">Siti Maharani</h4>
                            <p class="text-xs text-slate-400">Dir. Logistik, TechMakers</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex text-secondary mb-4 text-sm">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-slate-300 font-light text-sm leading-relaxed mb-6">"Layanan Top Urgent sangat bisa diandalkan. Dokumen legal dan perangkat medis kami selalu sampai dengan aman dan tepat waktu."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center font-bold text-sm mr-3">DR</div>
                        <div>
                            <h4 class="font-bold text-sm">Doni R.</h4>
                            <p class="text-xs text-slate-400">CEO, PharmaCorp</p>
                        </div>
                    </div>
                </div>
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
                                    <p class="text-slate-500 text-sm mt-1 leading-relaxed font-light">Gedung LANEXS Center<br>Jl. Jend. Sudirman Kav 21, Jakarta</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-primary shrink-0 mr-4">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 text-sm">Call Center</h5>
                                    <p class="text-slate-500 text-sm mt-1 leading-relaxed font-light">1500-LNX (569)<br>+62 811 2233 4455 (WA)</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-primary shrink-0 mr-4">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-800 text-sm">Email Support</h5>
                                    <p class="text-slate-500 text-sm mt-1 font-light">support@lanex.co.id</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Map -->
                    <div class="relative h-[400px] lg:h-auto lg:col-span-3 bg-slate-200">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.86989441113!2d106.74108821948523!3d-6.251458931102941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                            class="absolute inset-0 w-full h-full border-0 grayscale opacity-80" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-darkBg text-slate-400 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 mb-6">
                        <img src="<?= BASE_URL ?>/assets/images/a.png" alt="<?= APP_NAME ?>" class="h-10 w-auto object-contain brightness-0 invert opacity-90">
                    </div>
                    <p class="text-slate-500 mb-8 max-w-sm leading-relaxed font-light text-sm">
                        Best of The Best Service. Solusi ekspedisi terpercaya dengan komitmen penuh pada keamanan dan kecepatan pengiriman paket Anda.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-colors"><i class="bi bi-facebook text-sm"></i></a>
                        <a href="#" class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center hover:bg-slate-600 hover:text-white transition-colors"><i class="bi bi-twitter-x text-sm"></i></a>
                        <a href="#" class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-colors"><i class="bi bi-instagram text-sm"></i></a>
                        <a href="#" class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-colors"><i class="bi bi-linkedin text-sm"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-wider">Perusahaan</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="#beranda" class="hover:text-primary transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-primary transition-colors">Profil Perusahaan</a></li>
                        <li><a href="#layanan" class="hover:text-primary transition-colors">Layanan & Solusi</a></li>
                        <li><a href="#keunggulan" class="hover:text-primary transition-colors">Keunggulan</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-wider">Layanan Pelanggan</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="#tracking" class="hover:text-primary transition-colors">Lacak Kiriman</a></li>
                        <li><a href="<?= BASE_URL ?>/docs" class="text-secondary hover:text-secondaryHover font-medium transition-colors flex items-center">Dokumentasi API <i class="bi bi-box-arrow-up-right ml-2 text-xs"></i></a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-6 flex flex-col md:flex-row justify-between items-center text-xs font-light">
                <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Seluruh hak cipta dilindungi.</p>
                <div class="mt-4 md:mt-0 flex items-center space-x-4 text-slate-500">
                    <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Sistem Operasional</div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({ once: true, offset: 20 });
        
        // Navbar styling on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('nav-scrolled');
                navbar.classList.remove('py-3');
                navbar.classList.add('py-2');
            } else {
                navbar.classList.remove('nav-scrolled');
                navbar.classList.remove('py-2');
                navbar.classList.add('py-3');
            }
        });

        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const icon = btn.querySelector('i');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            if(menu.classList.contains('hidden')) {
                icon.classList.replace('bi-x-lg', 'bi-list');
            } else {
                icon.classList.replace('bi-list', 'bi-x-lg');
            }
        });

        // Close mobile menu on click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                icon.classList.replace('bi-x-lg', 'bi-list');
            });
        });
    </script>
</body>
</html>
