<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Solusi Ekspedisi Enterprise Anda</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/favicon.ico">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#4e73df', secondary: '#224abe' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-xl border-b border-white/20 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <a href="<?= BASE_URL ?>/" class="flex items-center space-x-2 text-primary group">
                    <img src="<?= BASE_URL ?>/assets/images/a.png" alt="<?= APP_NAME ?>" class="h-14 w-auto object-contain group-hover:scale-105 transition-transform">
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#beranda" class="text-gray-600 hover:text-primary font-medium transition">Beranda</a>
                    <a href="#tentang" class="text-gray-600 hover:text-primary font-medium transition">Profil</a>
                    <a href="#layanan" class="text-gray-600 hover:text-primary font-medium transition">Layanan</a>
                    <a href="#partners" class="text-gray-600 hover:text-primary font-medium transition">Partners</a>
                    <a href="#kontak" class="text-gray-600 hover:text-primary font-medium transition">Kontak Kami</a>
                    <a href="#tracking" class="text-gray-600 hover:text-primary font-medium transition">Cek Resi</a>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="bg-primary hover:bg-secondary text-white px-6 py-2.5 rounded-full font-semibold transition shadow-lg shadow-primary/30 flex items-center">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-full font-semibold transition shadow-lg flex items-center">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Sign In ERP
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-primary focus:outline-none p-2 bg-gray-50 rounded-lg">
                        <i class="bi bi-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full shadow-2xl transition-all origin-top">
            <div class="flex flex-col px-6 pt-4 pb-8 space-y-4">
                <a href="#beranda" class="text-gray-700 hover:text-primary hover:bg-blue-50 font-medium transition block px-4 py-3 rounded-xl"><i class="bi bi-house mr-2 text-gray-400"></i> Beranda</a>
                <a href="#tentang" class="text-gray-700 hover:text-primary hover:bg-blue-50 font-medium transition block px-4 py-3 rounded-xl"><i class="bi bi-person-lines-fill mr-2 text-gray-400"></i> Profil</a>
                <a href="#layanan" class="text-gray-700 hover:text-primary hover:bg-blue-50 font-medium transition block px-4 py-3 rounded-xl"><i class="bi bi-briefcase mr-2 text-gray-400"></i> Layanan</a>
                <a href="#partners" class="text-gray-700 hover:text-primary hover:bg-blue-50 font-medium transition block px-4 py-3 rounded-xl"><i class="bi bi-people mr-2 text-gray-400"></i> Partners</a>
                <a href="#kontak" class="text-gray-700 hover:text-primary hover:bg-blue-50 font-medium transition block px-4 py-3 rounded-xl"><i class="bi bi-telephone mr-2 text-gray-400"></i> Kontak Kami</a>
                <a href="#tracking" class="text-gray-700 hover:text-primary hover:bg-blue-50 font-medium transition block px-4 py-3 rounded-xl"><i class="bi bi-search mr-2 text-gray-400"></i> Cek Resi</a>
                
                <div class="pt-4 mt-2 border-t border-gray-100">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="w-full text-center flex justify-center items-center bg-primary hover:bg-secondary text-white px-6 py-3.5 rounded-xl font-semibold transition shadow-md">
                            <i class="bi bi-grid-fill mr-2"></i> Buka Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="w-full text-center flex justify-center items-center bg-gray-900 hover:bg-gray-800 text-white px-6 py-3.5 rounded-xl font-semibold transition shadow-md">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Sign In ERP
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c83a00?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Logistics Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gray-900/70 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto" data-aos="zoom-in" data-aos-duration="1000">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold text-sm mb-6 shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.8)]"></span> Sistem Ekspedisi Terintegrasi No. 1
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white tracking-tight mb-6 leading-tight drop-shadow-lg">
                    <?= htmlspecialchars($heroTitle) ?>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-10 leading-relaxed max-w-2xl mx-auto drop-shadow">
                    <?= htmlspecialchars($heroSubtitle) ?>
                </p>
                
                <!-- Tracking Form In Hero -->
                <div class="bg-white/10 backdrop-blur-md p-3 sm:p-4 rounded-3xl border border-white/20 shadow-2xl max-w-3xl mx-auto mb-8">
                    <form action="<?= BASE_URL ?>/tracking" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1 group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="bi bi-box-seam text-white/70 text-xl group-focus-within:text-white transition-colors"></i>
                            </div>
                            <input type="text" name="resi" class="w-full pl-12 pr-6 py-4 bg-white/10 border border-white/10 rounded-2xl focus:bg-white/20 focus:outline-none focus:border-white/40 transition-all text-lg font-medium text-white placeholder-white/60" placeholder="Masukkan Nomor Resi (Contoh: KTX-JKT-001)" required>
                        </div>
                        <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all shadow-lg shadow-primary/30 whitespace-nowrap flex items-center justify-center transform hover:-translate-y-0.5 border border-primary/50">
                            <i class="bi bi-search mr-2"></i> Lacak
                        </button>
                    </form>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4 px-4 sm:px-0">
                    <a href="#layanan" class="text-white hover:text-blue-200 transition-colors flex items-center justify-center text-sm font-semibold uppercase tracking-wider">
                        Pelajari Layanan Kami <i class="bi bi-arrow-down-short text-xl ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Bottom Curve/Wave Decoration -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10" style="transform: translateY(1px);">
            <svg class="relative block w-full h-12 lg:h-24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,115.35,192.34,103.54,237.52,94.85,280.9,74.79,321.39,56.44Z" fill="#ffffff"></path>
            </svg>
        </div>
    </section>


    <!-- About Section -->
    <section id="tentang" class="py-28 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="lg:w-1/2 relative" data-aos="fade-right">
                    <div class="relative z-10 w-full h-[500px] rounded-[2.5rem] overflow-hidden shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/40 to-transparent z-10 mix-blend-multiply"></div>
                        <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Logistics Operations" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                    
                    <!-- Floating Stats Card -->
                    <div class="absolute -bottom-10 -right-10 bg-white p-8 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] z-20 hidden sm:block border border-gray-50 backdrop-blur-xl animate-bounce" style="animation-duration: 3s;">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-teal-400 text-white rounded-2xl flex items-center justify-center text-3xl font-bold shadow-inner">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                            <div>
                                <h4 class="text-4xl font-black text-gray-900 tracking-tight">10<span class="text-blue-500">+</span></h4>
                                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mt-1">Tahun Pengalaman</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Decorative Dots -->
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-[radial-gradient(#cbd5e1_2px,transparent_2px)] [background-size:16px_16px] z-0 opacity-70"></div>
                </div>
                <div class="lg:w-1/2" data-aos="fade-left">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-600 font-bold text-xs uppercase tracking-widest mb-6">
                        <i class="bi bi-building mr-2"></i> Profil Perusahaan
                    </div>
                    <h3 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-8 leading-tight tracking-tight">Solusi Ekspedisi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500">Terdepan</span></h3>
                    
                    <div class="space-y-8">
                        <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow">
                            <h4 class="text-xl font-bold text-gray-900 mb-3 flex items-center"><i class="bi bi-clock-history text-blue-500 mr-3"></i> Sejarah Perusahaan</h4>
                            <p class="text-gray-600 text-base leading-relaxed">
                                Didirikan dengan visi kuat untuk mendigitalisasi dan menyederhanakan industri logistik di Indonesia, <?= APP_NAME ?> terus berinovasi memberikan layanan pengiriman yang cepat, aman, dan efisien untuk berbagai skala bisnis.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 hover:shadow-md transition-shadow">
                                <h4 class="text-lg font-bold text-gray-900 mb-3 flex items-center"><i class="bi bi-eye text-blue-500 mr-2"></i> Visi</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Menjadi perusahaan ekspedisi pilihan utama yang mengintegrasikan teknologi modern dengan pelayanan sepenuh hati.</p>
                            </div>
                            <div class="bg-teal-50/50 p-6 rounded-2xl border border-teal-100 hover:shadow-md transition-shadow">
                                <h4 class="text-lg font-bold text-gray-900 mb-3 flex items-center"><i class="bi bi-bullseye text-teal-500 mr-2"></i> Misi</h4>
                                <ul class="text-gray-600 text-sm leading-relaxed space-y-2">
                                    <li class="flex items-start"><i class="bi bi-check2 text-teal-500 mr-2 mt-0.5"></i> Layanan tepat waktu & aman</li>
                                    <li class="flex items-start"><i class="bi bi-check2 text-teal-500 mr-2 mt-0.5"></i> Inovasi teknologi</li>
                                    <li class="flex items-start"><i class="bi bi-check2 text-teal-500 mr-2 mt-0.5"></i> Kemitraan jangka panjang</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center"><i class="bi bi-diagram-3 text-blue-500 mr-3"></i> Struktur Organisasi</h4>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center hover:shadow-lg hover:-translate-y-1 transition-all">
                                    <div class="w-12 h-12 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl mb-3"><i class="bi bi-person-fill"></i></div>
                                    <div class="font-bold text-gray-900">CEO</div>
                                    <div class="text-[11px] text-gray-500 mt-1 uppercase tracking-wider">Dirut</div>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center hover:shadow-lg hover:-translate-y-1 transition-all">
                                    <div class="w-12 h-12 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl mb-3"><i class="bi bi-person-fill-gear"></i></div>
                                    <div class="font-bold text-gray-900">COO</div>
                                    <div class="text-[11px] text-gray-500 mt-1 uppercase tracking-wider">Operasional</div>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center hover:shadow-lg hover:-translate-y-1 transition-all">
                                    <div class="w-12 h-12 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xl mb-3"><i class="bi bi-pc-display"></i></div>
                                    <div class="font-bold text-gray-900">CTO</div>
                                    <div class="text-[11px] text-gray-500 mt-1 uppercase tracking-wider">Teknologi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="layanan" class="py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-600 font-bold text-xs uppercase tracking-widest mb-6">Layanan Utama</div>
                <h3 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">Mengapa Memilih <?= APP_NAME ?>?</h3>
                <p class="text-gray-500 mt-6 text-lg max-w-2xl mx-auto">Kami menghadirkan berbagai solusi logistik terpadu untuk memastikan setiap paket Anda sampai dengan aman dan efisien.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Layanan Pengiriman -->
                <div class="bg-white rounded-[2rem] p-10 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] border border-gray-100 hover:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.12)] transition-all duration-500 group relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="flex items-center mb-8 relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl mr-6 shadow-lg shadow-blue-500/30">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4 class="text-3xl font-black text-gray-900 tracking-tight">Pengiriman</h4>
                    </div>
                    
                    <ul class="space-y-4 relative z-10">
                        <li class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100"><i class="bi bi-check-circle-fill text-blue-500 text-xl mr-4 mt-0.5"></i> <span class="text-gray-700 text-base leading-relaxed"><strong>Udara, Darat, Laut:</strong> Multimoda transport untuk semua kebutuhan jarak jauh.</span></li>
                        <li class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100"><i class="bi bi-check-circle-fill text-blue-500 text-xl mr-4 mt-0.5"></i> <span class="text-gray-700 text-base leading-relaxed"><strong>TUS (Top Urgent Service):</strong> Layanan prioritas tingkat tertinggi.</span></li>
                        <li class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100"><i class="bi bi-check-circle-fill text-blue-500 text-xl mr-4 mt-0.5"></i> <span class="text-gray-700 text-base leading-relaxed"><strong>ES & RES:</strong> Express Service (1 hari sampai) & Regular Service.</span></li>
                        <li class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100"><i class="bi bi-check-circle-fill text-blue-500 text-xl mr-4 mt-0.5"></i> <span class="text-gray-700 text-base leading-relaxed"><strong>Trucking, DDS, PPS, PDS:</strong> FTL/LTL, Door-to-Door, Port-to-Port.</span></li>
                    </ul>
                </div>
                
                <!-- Layanan Pengemasan -->
                <div class="bg-white rounded-[2rem] p-10 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] border border-gray-100 hover:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.12)] transition-all duration-500 group relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-bl-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="flex items-center mb-8 relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-green-500 text-white rounded-2xl flex items-center justify-center text-3xl mr-6 shadow-lg shadow-teal-500/30">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <h4 class="text-3xl font-black text-gray-900 tracking-tight">Pengemasan</h4>
                    </div>
                    
                    <ul class="space-y-4 relative z-10">
                        <li class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100"><i class="bi bi-shield-fill-check text-teal-500 text-xl mr-4 mt-0.5"></i> <span class="text-gray-700 text-base leading-relaxed"><strong>Pergudangan (Warehousing):</strong> Penyimpanan aman dengan sistem WMS cerdas dan terpantau 24/7.</span></li>
                        <li class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100"><i class="bi bi-shield-fill-check text-teal-500 text-xl mr-4 mt-0.5"></i> <span class="text-gray-700 text-base leading-relaxed"><strong>Pickup Barang:</strong> Penjemputan paket langsung ke lokasi Anda tanpa minimal kuota.</span></li>
                        <li class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100"><i class="bi bi-shield-fill-check text-teal-500 text-xl mr-4 mt-0.5"></i> <span class="text-gray-700 text-base leading-relaxed"><strong>Packing Service:</strong> Repacking kayu, bubble wrap, dan karton sesuai standar keselamatan tinggi.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Experience Section -->
    <section id="partners" class="py-24 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-600 font-bold text-xs uppercase tracking-widest mb-6">Kolaborasi Global</div>
                <h3 class="text-4xl font-black text-gray-900 tracking-tight">Partners Experience</h3>
            </div>
            
            <div class="flex flex-wrap justify-center items-center gap-12 sm:gap-20 opacity-60 hover:opacity-100 transition-opacity duration-500" data-aos="fade-up" data-aos-delay="100">
                <span class="text-2xl font-black text-gray-400 uppercase tracking-widest">ECOMMERCE PRO</span>
                <span class="text-2xl font-black text-gray-400 uppercase tracking-widest">GLOBAL RETAIL</span>
                <span class="text-2xl font-black text-gray-400 uppercase tracking-widest">TECH MANUFACTURE</span>
                <span class="text-2xl font-black text-gray-400 uppercase tracking-widest">PHARMA MEDICA</span>
            </div>
        </div>
    </section>

    <!-- Kontak Kami -->
    <section id="kontak" class="py-28 relative">
        <div class="absolute inset-0 z-0 bg-gray-900">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1596524430615-b46475ddff6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80')] opacity-20 bg-cover bg-center mix-blend-overlay"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/95 backdrop-blur-3xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/20" data-aos="fade-up">
                <div class="grid grid-cols-1 lg:grid-cols-5">
                    <div class="p-10 lg:p-16 lg:col-span-2 flex flex-col justify-center bg-white">
                        <h2 class="text-4xl font-black text-gray-900 mb-6 tracking-tight">Kontak Kami</h2>
                        <p class="text-gray-500 mb-10 text-lg leading-relaxed">Punya pertanyaan seputar layanan kami atau ingin konsultasi kerjasama pengiriman B2B?</p>
                        
                        <ul class="space-y-8">
                            <li class="flex items-start group">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-blue-600 mr-5 shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    <i class="bi bi-geo-alt text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-900 text-lg">Kantor Pusat</h5>
                                    <p class="text-gray-500 text-sm mt-1 leading-relaxed">Gedung LANEX Center<br>Jl. Jend. Sudirman Kav 21, Jakarta</p>
                                </div>
                            </li>
                            <li class="flex items-start group">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-blue-600 mr-5 shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    <i class="bi bi-telephone text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-900 text-lg">Layanan Pelanggan</h5>
                                    <p class="text-gray-500 text-sm mt-1 leading-relaxed">1500-LNX (569)<br>+62 811 2233 4455 (WA)</p>
                                </div>
                            </li>
                            <li class="flex items-start group">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-blue-600 mr-5 shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    <i class="bi bi-envelope-at text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-900 text-lg">Email Respon Cepat</h5>
                                    <p class="text-gray-500 text-sm mt-1 leading-relaxed">support@lanex.co.id</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="relative h-80 lg:h-auto lg:col-span-3">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.86989441113!2d106.74108821948523!3d-6.251458931102941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta%20Selatan%2C%20Kota%20Jakarta%20Selatan%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                            width="100%" height="100%" style="border:0; position: absolute; inset:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="grayscale contrast-125 opacity-90"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 pt-20 pb-10 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 text-white mb-6">
                        <img src="<?= BASE_URL ?>/assets/images/a.png" alt="<?= APP_NAME ?>" class="h-14 w-auto object-contain brightness-0 invert">
                    </div>
                    <p class="text-gray-400 mb-8 max-w-md leading-relaxed">
                        Platform manajemen logistik enterprise masa depan yang menghubungkan Anda dengan pengalaman pengiriman kelas dunia.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-12 h-12 rounded-2xl bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors duration-300"><i class="bi bi-facebook text-lg"></i></a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white transition-colors duration-300"><i class="bi bi-twitter-x text-lg"></i></a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-colors duration-300"><i class="bi bi-instagram text-lg"></i></a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-gray-800 flex items-center justify-center hover:bg-blue-700 hover:text-white transition-colors duration-300"><i class="bi bi-linkedin text-lg"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold text-white mb-6">Pintasan</h4>
                    <ul class="space-y-4">
                        <li><a href="#beranda" class="hover:text-blue-400 transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-blue-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="#layanan" class="hover:text-blue-400 transition-colors">Layanan & Solusi</a></li>
                        <li><a href="#tracking" class="hover:text-blue-400 transition-colors">Lacak Kiriman</a></li>
                        <li><a href="<?= BASE_URL ?>/docs" class="hover:text-white transition-colors font-bold text-blue-500">Dokumentasi API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold text-white mb-6">Dukungan</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Karir</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm">
                <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Seluruh hak cipta dilindungi.</p>
                <div class="mt-4 md:mt-0 flex items-center space-x-6 text-gray-500">
                    <span>Sistem Terintegrasi v2.0</span>
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span>Status: Operasional</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- AOS Animation & Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50 });
        
        // Navbar blur effect on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });

        // Mobile Menu Toggle logic
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

        // Close mobile menu when a link is clicked
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                icon.classList.replace('bi-x-lg', 'bi-list');
            });
        });

        // Smooth Scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    // Header offset
                    const headerOffset = 80;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
              
                    window.scrollTo({
                         top: offsetPosition,
                         behavior: "smooth"
                    });
                }
            });
        });
    </script>
</body>
</html>
