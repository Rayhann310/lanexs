<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Solusi Logistik Modern</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/favicon.ico">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#4f46e5', // Deep Indigo
                        primaryLight: '#6366f1',
                        secondary: '#0d9488', // Vibrant Teal
                        darkBg: '#0f172a'
                    },
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    animation: {
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'marquee': 'marquee 25s linear infinite',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': {
                                'background-size': '200% 200%',
                                'background-position': 'left center'
                            },
                            '50%': {
                                'background-size': '200% 200%',
                                'background-position': 'right center'
                            },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        'marquee': {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-100%)' }
                        }
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hero-gradient {
            background: linear-gradient(-45deg, #4f46e5, #3b82f6, #0d9488, #2dd4bf);
            background-size: 400% 400%;
        }
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Hide scrollbar for marquee */
        .marquee-container::-webkit-scrollbar { display: none; }
        .marquee-container { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 overflow-x-hidden selection:bg-primary selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <a href="<?= BASE_URL ?>/" class="flex items-center space-x-2 group">
                    <img id="nav-logo" src="<?= BASE_URL ?>/assets/images/a.png" alt="<?= APP_NAME ?>" class="h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105 filter drop-shadow-md brightness-0 invert">
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden lg:flex space-x-8 items-center bg-white/10 backdrop-blur-md px-8 py-3 rounded-full border border-white/20 shadow-lg">
                    <a href="#beranda" class="text-white hover:text-teal-300 font-medium transition-colors text-sm tracking-wide">Beranda</a>
                    <a href="#tentang" class="text-white hover:text-teal-300 font-medium transition-colors text-sm tracking-wide">Profil</a>
                    <a href="#layanan" class="text-white hover:text-teal-300 font-medium transition-colors text-sm tracking-wide">Layanan</a>
                    <a href="#partners" class="text-white hover:text-teal-300 font-medium transition-colors text-sm tracking-wide">Partners</a>
                    <a href="#kontak" class="text-white hover:text-teal-300 font-medium transition-colors text-sm tracking-wide">Kontak</a>
                </div>
                
                <div class="hidden lg:flex items-center">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="relative inline-flex items-center justify-center px-6 py-2.5 overflow-hidden font-medium text-white bg-primary rounded-full hover:bg-primaryLight shadow-[0_0_20px_rgba(79,70,229,0.5)] transition-all group">
                            <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                            <i class="bi bi-grid-fill mr-2 relative"></i> <span class="relative">Dashboard</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="bg-white text-primary hover:bg-slate-50 px-6 py-2.5 rounded-full font-bold transition shadow-lg flex items-center hover:shadow-[0_0_20px_rgba(255,255,255,0.4)] hover:-translate-y-0.5">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Portal ERP
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-white focus:outline-none p-2 bg-white/10 backdrop-blur-md rounded-xl border border-white/20">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden lg:hidden glass-nav absolute w-full shadow-2xl transition-all origin-top border-t border-slate-200/50">
            <div class="flex flex-col px-6 pt-4 pb-8 space-y-3">
                <a href="#beranda" class="text-slate-800 hover:text-primary font-bold transition block px-4 py-3 rounded-xl hover:bg-white/50">Beranda</a>
                <a href="#tentang" class="text-slate-800 hover:text-primary font-bold transition block px-4 py-3 rounded-xl hover:bg-white/50">Profil</a>
                <a href="#layanan" class="text-slate-800 hover:text-primary font-bold transition block px-4 py-3 rounded-xl hover:bg-white/50">Layanan</a>
                <a href="#partners" class="text-slate-800 hover:text-primary font-bold transition block px-4 py-3 rounded-xl hover:bg-white/50">Partners</a>
                <a href="#kontak" class="text-slate-800 hover:text-primary font-bold transition block px-4 py-3 rounded-xl hover:bg-white/50">Kontak</a>
                
                <div class="pt-4 mt-2">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="w-full text-center flex justify-center items-center bg-primary text-white px-6 py-3.5 rounded-xl font-bold shadow-lg">
                            <i class="bi bi-grid-fill mr-2"></i> Buka Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="w-full text-center flex justify-center items-center bg-slate-900 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Portal ERP
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
        <!-- Animated Gradient Background -->
        <div class="absolute inset-0 hero-gradient animate-gradient-x z-0"></div>
        
        <!-- Abstract Shapes -->
        <div class="absolute top-1/4 left-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-float"></div>
        <div class="absolute bottom-1/4 right-10 w-96 h-96 bg-teal-300 rounded-full mix-blend-overlay filter blur-3xl opacity-30 animate-float" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full pt-10 pb-20">
            <div class="text-center max-w-4xl mx-auto">
                <div data-aos="fade-down" data-aos-duration="1000">
                    <div class="inline-flex items-center px-4 py-2 rounded-full glass-card text-white font-semibold text-xs tracking-widest uppercase mb-8 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-teal-300 mr-2 animate-ping"></span> Ekspedisi Enterprise No. 1
                    </div>
                </div>
                
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight mb-6 leading-[1.1] drop-shadow-xl" data-aos="zoom-in" data-aos-duration="1200">
                    Satu Platform,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 to-white drop-shadow-none">Ribuan Pengiriman.</span>
                </h1>
                
                <p class="text-lg sm:text-xl text-indigo-50 mb-12 leading-relaxed max-w-2xl mx-auto drop-shadow-md font-light" data-aos="fade-up" data-aos-delay="200">
                    <?= htmlspecialchars($heroSubtitle) ?> Solusi pintar, cepat, dan aman untuk mendigitalisasi setiap pergerakan paket Anda.
                </p>
                
                <!-- Tracking Form -->
                <div class="glass-card p-2 sm:p-3 rounded-[2rem] max-w-2xl mx-auto mb-16 shadow-2xl transform transition hover:scale-[1.02]" data-aos="fade-up" data-aos-delay="400">
                    <form action="<?= BASE_URL ?>/tracking" method="GET" class="flex flex-col sm:flex-row gap-2 relative">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <i class="bi bi-box-seam text-white/70 text-xl"></i>
                            </div>
                            <input type="text" name="resi" class="w-full pl-14 pr-6 py-5 bg-white/5 border border-transparent rounded-[1.5rem] focus:bg-white/10 focus:outline-none focus:border-white/30 transition-all text-lg font-medium text-white placeholder-white/60" placeholder="Masukkan Nomor Resi..." required>
                        </div>
                        <button type="submit" class="bg-white text-primary hover:bg-slate-50 px-10 py-5 rounded-[1.5rem] font-bold text-lg transition-all shadow-[0_0_20px_rgba(255,255,255,0.2)] flex items-center justify-center group">
                            <span class="group-hover:tracking-wider transition-all">Lacak</span>
                            <i class="bi bi-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Bottom Curve Decoration -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10">
            <svg class="relative block w-full h-16 lg:h-32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="#f8fafc"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="#f8fafc"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="#f8fafc"></path>
            </svg>
        </div>
    </section>

    <!-- About Section (Bento Grid) -->
    <section id="tentang" class="py-24 bg-slate-50 relative overflow-hidden">
        <!-- Abstract dots background -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNlMmU4ZjAiLz48L3N2Zz4=')] [background-size:24px_24px] opacity-60 z-0"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-bold tracking-widest uppercase text-sm mb-2 block">Tentang Kami</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">Lebih Dari Sekadar <span class="bg-gradient-to-r from-primary to-secondary text-gradient">Pengiriman</span></h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 auto-rows-min">
                <!-- Main Feature Box -->
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-8 md:p-12 shadow-xl shadow-slate-200/50 border border-slate-100 hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mb-4 tracking-tight">Sejarah & Inovasi</h3>
                    <p class="text-slate-600 text-lg leading-relaxed font-light">
                        Berawal dari visi untuk mendigitalisasi industri logistik di Indonesia, <?= APP_NAME ?> hadir untuk memberikan layanan yang tidak hanya cepat dan aman, tetapi juga terintegrasi sepenuhnya dengan teknologi terkini. Kami menghubungkan setiap titik secara *real-time*.
                    </p>
                </div>
                
                <!-- Counter Box -->
                <div class="bg-gradient-to-br from-primary to-indigo-600 rounded-[2rem] p-8 md:p-12 shadow-xl shadow-indigo-200 text-white flex flex-col justify-center relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <h4 class="text-6xl font-black mb-2 tracking-tighter">10<span class="text-teal-300">+</span></h4>
                    <p class="text-indigo-100 font-semibold tracking-wide uppercase text-sm">Tahun Pengalaman</p>
                    
                    <div class="mt-8 pt-8 border-t border-white/20">
                        <h4 class="text-4xl font-black mb-2 tracking-tighter">99<span class="text-teal-300">%</span></h4>
                        <p class="text-indigo-100 font-semibold tracking-wide uppercase text-sm">Ketepatan Waktu</p>
                    </div>
                </div>

                <!-- Visi Box -->
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 transition-transform duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-xl mr-4"><i class="bi bi-eye-fill"></i></div>
                        <h4 class="text-xl font-bold text-slate-800">Visi Kami</h4>
                    </div>
                    <p class="text-slate-600 font-light leading-relaxed">Menjadi pionir logistik modern yang mengintegrasikan teknologi terdepan dengan pelayanan pelanggan berkelas dunia.</p>
                </div>

                <!-- Misi Box -->
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 transition-transform duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl mr-4"><i class="bi bi-bullseye"></i></div>
                        <h4 class="text-xl font-bold text-slate-800">Misi Perusahaan</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start"><i class="bi bi-check-circle-fill text-teal-500 mr-3 mt-1"></i> <span class="text-slate-600 font-light">Layanan pengiriman super cepat & akurat.</span></div>
                        <div class="flex items-start"><i class="bi bi-check-circle-fill text-teal-500 mr-3 mt-1"></i> <span class="text-slate-600 font-light">Inovasi platform digital tiada henti.</span></div>
                        <div class="flex items-start"><i class="bi bi-check-circle-fill text-teal-500 mr-3 mt-1"></i> <span class="text-slate-600 font-light">Keamanan barang sebagai prioritas absolut.</span></div>
                        <div class="flex items-start"><i class="bi bi-check-circle-fill text-teal-500 mr-3 mt-1"></i> <span class="text-slate-600 font-light">Kemitraan B2B yang solid & transparan.</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16" data-aos="fade-right">
                <div class="max-w-2xl">
                    <span class="text-secondary font-bold tracking-widest uppercase text-sm mb-2 block">Layanan & Solusi</span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">Solusi Ekspedisi <br>Tanpa <span class="bg-gradient-to-r from-secondary to-primary text-gradient">Batas</span></h2>
                </div>
                <p class="text-slate-500 font-light mt-4 md:mt-0 max-w-sm">Beragam layanan dirancang khusus untuk memenuhi tingginya ekspektasi bisnis modern.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="group bg-slate-50 rounded-[2rem] p-10 hover:bg-primary transition-colors duration-500 shadow-lg hover:shadow-[0_20px_50px_rgba(79,70,229,0.3)] relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-bl-full opacity-50 transform scale-0 group-hover:scale-150 transition-transform duration-700 origin-top-right"></div>
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl text-primary shadow-sm mb-8 group-hover:shadow-lg transition-all relative z-10"><i class="bi bi-truck"></i></div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4 group-hover:text-white transition-colors relative z-10 tracking-tight">Cargo Darat & Laut</h3>
                    <p class="text-slate-500 font-light group-hover:text-indigo-100 transition-colors relative z-10">Layanan pengiriman reguler (RES) dan ekspres (ES) lintas pulau dengan armada *trucking* modern serta kapal kargo.</p>
                </div>
                
                <!-- Card 2 -->
                <div class="group bg-slate-50 rounded-[2rem] p-10 hover:bg-secondary transition-colors duration-500 shadow-lg hover:shadow-[0_20px_50px_rgba(13,148,136,0.3)] relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-bl-full opacity-50 transform scale-0 group-hover:scale-150 transition-transform duration-700 origin-top-right"></div>
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl text-secondary shadow-sm mb-8 group-hover:shadow-lg transition-all relative z-10"><i class="bi bi-airplane-engines"></i></div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4 group-hover:text-white transition-colors relative z-10 tracking-tight">Top Urgent (Udara)</h3>
                    <p class="text-slate-500 font-light group-hover:text-teal-100 transition-colors relative z-10">Pengiriman via kargo udara untuk paket prioritas tinggi (TUS). Tiba di hari yang sama atau keesokan harinya.</p>
                </div>

                <!-- Card 3 -->
                <div class="group bg-slate-50 rounded-[2rem] p-10 hover:bg-slate-900 transition-colors duration-500 shadow-lg hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)] relative overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-full transform scale-0 group-hover:scale-150 transition-transform duration-700 origin-top-right"></div>
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl text-slate-900 shadow-sm mb-8 group-hover:shadow-lg transition-all relative z-10"><i class="bi bi-box-seam"></i></div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4 group-hover:text-white transition-colors relative z-10 tracking-tight">Pergudangan & Packing</h3>
                    <p class="text-slate-500 font-light group-hover:text-slate-300 transition-colors relative z-10">Layanan WMS, penjemputan (*pickup*), dan repacking (kayu/bubble wrap) demi keamanan paket ekstra.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners / Marquee Section -->
    <section id="partners" class="py-16 bg-slate-50 border-y border-slate-200/60 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 text-center">
             <span class="text-slate-400 font-bold tracking-widest uppercase text-xs">Dipercaya Oleh Berbagai Perusahaan Besar</span>
        </div>
        
        <div class="relative w-full flex overflow-x-hidden group">
            <!-- Fade edges -->
            <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-slate-50 to-transparent z-10"></div>
            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-slate-50 to-transparent z-10"></div>
            
            <div class="animate-marquee flex items-center whitespace-nowrap space-x-16 px-8 py-4">
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">ECOMMERCE PRO</span>
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">GLOBAL RETAIL</span>
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">TECH MANUFACTURE</span>
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">PHARMA MEDICA</span>
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">FINANCE CORP</span>
                <!-- Duplicate for seamless loop -->
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">ECOMMERCE PRO</span>
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">GLOBAL RETAIL</span>
                <span class="text-3xl font-black text-slate-300 uppercase tracking-widest hover:text-primary transition-colors cursor-default">TECH MANUFACTURE</span>
            </div>
        </div>
    </section>

    <!-- Contact & Map Section -->
    <section id="kontak" class="py-24 bg-darkBg relative overflow-hidden">
        <!-- Background Grid -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px] opacity-20"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary/30 rounded-full blur-[120px] mix-blend-screen pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10" data-aos="fade-up">
                
                <!-- Contact Info -->
                <div class="bg-white/5 backdrop-blur-2xl p-10 md:p-16 flex flex-col justify-center">
                    <h2 class="text-4xl font-black text-white mb-4 tracking-tight">Siap Membantu Anda.</h2>
                    <p class="text-slate-400 mb-10 text-lg font-light">Hubungi kami untuk konsultasi integrasi B2B atau pertanyaan seputar layanan logistik.</p>
                    
                    <ul class="space-y-8">
                        <li class="flex items-start group">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-teal-400 mr-5 shrink-0 border border-white/10 group-hover:bg-teal-400 group-hover:text-darkBg transition-all duration-300">
                                <i class="bi bi-geo-alt-fill text-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-white text-lg tracking-wide">Headquarter</h5>
                                <p class="text-slate-400 text-sm mt-1 leading-relaxed font-light">Gedung LANEXS Center<br>Jl. Jend. Sudirman Kav 21, Jakarta</p>
                            </div>
                        </li>
                        <li class="flex items-start group">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-teal-400 mr-5 shrink-0 border border-white/10 group-hover:bg-teal-400 group-hover:text-darkBg transition-all duration-300">
                                <i class="bi bi-telephone-fill text-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-white text-lg tracking-wide">Call Center 24/7</h5>
                                <p class="text-slate-400 text-sm mt-1 leading-relaxed font-light">1500-LNX (569)<br>+62 811 2233 4455 (WhatsApp)</p>
                            </div>
                        </li>
                        <li class="flex items-start group">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-teal-400 mr-5 shrink-0 border border-white/10 group-hover:bg-teal-400 group-hover:text-darkBg transition-all duration-300">
                                <i class="bi bi-envelope-fill text-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-white text-lg tracking-wide">Email Support</h5>
                                <p class="text-slate-400 text-sm mt-1 leading-relaxed font-light">support@lanex.co.id</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Map -->
                <div class="relative h-[400px] lg:h-auto w-full bg-slate-800">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.86989441113!2d106.74108821948523!3d-6.251458931102941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                        class="absolute inset-0 w-full h-full border-0 grayscale invert opacity-70 mix-blend-luminosity" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#0b1120] text-slate-400 pt-20 pb-8 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 text-white mb-6">
                        <img src="<?= BASE_URL ?>/assets/images/a.png" alt="<?= APP_NAME ?>" class="h-10 w-auto object-contain brightness-0 invert opacity-90">
                    </div>
                    <p class="text-slate-500 mb-8 max-w-sm leading-relaxed font-light">
                        Platform manajemen logistik enterprise masa depan. Menghubungkan Anda dengan pengalaman pengiriman kelas dunia secara *real-time*.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition-colors duration-300"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white hover:text-black transition-colors duration-300"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-colors duration-300"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors duration-300"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-widest">Pintasan</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="#beranda" class="hover:text-teal-400 transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-teal-400 transition-colors">Profil Perusahaan</a></li>
                        <li><a href="#layanan" class="hover:text-teal-400 transition-colors">Layanan & Solusi</a></li>
                        <li><a href="#tracking" class="hover:text-teal-400 transition-colors">Lacak Kiriman</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-widest">Dukungan</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="<?= BASE_URL ?>/docs" class="text-teal-400 hover:text-teal-300 font-medium transition-colors flex items-center">API Docs <i class="bi bi-box-arrow-up-right ml-2 text-xs"></i></a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center text-xs font-light">
                <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Seluruh hak cipta dilindungi.</p>
                <div class="mt-4 md:mt-0 flex items-center space-x-4 text-slate-500">
                    <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-teal-500 mr-2 animate-pulse"></span> Sistem Operasional</div>
                    <span>v2.0.1</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({ once: true, offset: 30 });
        
        // Navbar blur effect on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const logo = document.getElementById('nav-logo');
            
            if (window.scrollY > 50) {
                navbar.classList.add('glass-nav');
                // Change logo from white to colored/dark when scrolling down (if needed)
                logo.classList.remove('brightness-0', 'invert');
                // Adjust text colors for desktop menu
                document.querySelectorAll('#navbar .lg\\:flex a[href^="#"]').forEach(el => {
                    el.classList.replace('text-white', 'text-slate-800');
                    el.classList.replace('hover:text-teal-300', 'hover:text-primary');
                });
                
                // Also update the mobile button icon color
                document.getElementById('mobile-menu-btn').classList.replace('text-white', 'text-slate-800');
                
            } else {
                navbar.classList.remove('glass-nav');
                logo.classList.add('brightness-0', 'invert');
                // Revert text colors
                document.querySelectorAll('#navbar .lg\\:flex a[href^="#"]').forEach(el => {
                    el.classList.replace('text-slate-800', 'text-white');
                    el.classList.replace('hover:text-primary', 'hover:text-teal-300');
                });
                
                // Revert mobile button
                document.getElementById('mobile-menu-btn').classList.replace('text-slate-800', 'text-white');
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
    </script>
</body>
</html>
