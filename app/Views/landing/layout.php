<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME . ' - Best of The Best Service' ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'Solusi ekspedisi terpercaya LANEXS dengan komitmen penuh pada keamanan, kecepatan, dan kepuasan pelanggan.' ?>">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/favicon.ico">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9', // Neon Cyan
                        primaryHover: '#0284c7', // Darker Cyan
                        secondary: '#2dd4bf', // Teal/Neon Accent
                        secondaryHover: '#14b8a6',
                        darkBg: '#030712' // Midnight Black
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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .nav-scrolled {
            background-color: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }
        .dark .nav-scrolled {
            background-color: rgba(2, 6, 23, 0.75); /* slate-950 */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }
        .hero-bg {
            background-color: #f8fafc;
            background-image: 
                linear-gradient(to right, rgba(14, 165, 233, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(14, 165, 233, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .dark .hero-bg {
            background-color: #0f172a;
            background-image: 
                linear-gradient(to right, rgba(14, 165, 233, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(14, 165, 233, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .accent-border-hover:hover {
            border-color: #0ea5e9;
            box-shadow: 0 0 20px rgba(14,165,233, 0.3), inset 0 0 10px rgba(14,165,233, 0.1);
            transform: translateY(-4px);
        }
        /* Dropdown menu */
        .nav-dropdown { display: none; }
        .nav-item:hover .nav-dropdown { display: block; }
        /* Mobile accordion */
        .mobile-accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .mobile-accordion-content.open { max-height: 500px; }
        /* Page content prose */
        .prose-content h1, .prose-content h2, .prose-content h3 { font-family: 'Outfit', sans-serif; font-weight: 800; color: #0f172a; }
        .prose-content h1 { font-size: 2rem; margin-bottom: 1rem; }
        .prose-content h2 { font-size: 1.5rem; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .prose-content h3 { font-size: 1.2rem; margin-top: 1.25rem; margin-bottom: 0.5rem; }
        .prose-content p { line-height: 1.8; color: #475569; margin-bottom: 1rem; }
        .prose-content ul, .prose-content ol { padding-left: 1.5rem; margin-bottom: 1rem; }
        .prose-content li { color: #475569; line-height: 1.8; margin-bottom: 0.25rem; }
        .prose-content ul li { list-style-type: disc; }
        .prose-content ol li { list-style-type: decimal; }
        .prose-content strong { color: #1e293b; }
        .prose-content blockquote { border-left: 4px solid #107c8c; padding-left: 1rem; color: #64748b; font-style: italic; margin: 1.5rem 0; }

        /* Dark mode prose content */
        .dark .prose-content h1, .dark .prose-content h2, .dark .prose-content h3 { color: #f8fafc; }
        .dark .prose-content p, .dark .prose-content li { color: #cbd5e1; }
        .dark .prose-content strong { color: #f8fafc; }
        .dark .prose-content blockquote { color: #94a3b8; border-left-color: #f3aa00; }

        /* ── Navbar White Mode (for inner pages with dark hero) ── */
        .navbar-white:not(.nav-scrolled) .nav-link { color: rgba(255,255,255,0.85) !important; }
        .navbar-white:not(.nav-scrolled) .nav-link:hover { color: #fff !important; background-color: rgba(255,255,255,0.15) !important; }
        .navbar-white:not(.nav-scrolled) .nav-btn { color: rgba(255,255,255,0.85) !important; }
        .navbar-white:not(.nav-scrolled) .nav-btn:hover { color: #fff !important; background-color: rgba(255,255,255,0.15) !important; }
        .navbar-white:not(.nav-scrolled) .nav-logo { filter: brightness(0) invert(1); }
        .navbar-white:not(.nav-scrolled) .nav-cta-outline { border-color: rgba(255,255,255,0.7) !important; color: #fff !important; }
        .navbar-white:not(.nav-scrolled) .nav-cta-outline:hover { background-color: #fff !important; color: #107c8c !important; }
        .navbar-white:not(.nav-scrolled) .nav-cta-filled { background-color: rgba(255,255,255,0.15) !important; border: 2px solid rgba(255,255,255,0.5) !important; color: #fff !important; }
        .navbar-white:not(.nav-scrolled) .nav-hamburger { color: #fff !important; }
        
        /* Dark Mode overrides for navbar elements */
        .dark .nav-logo { filter: brightness(0) invert(1); }
        .dark .nav-link, .dark .nav-btn, .dark .mobile-accordion-btn { color: #e2e8f0; }
        .dark .nav-link:hover, .dark .nav-btn:hover, .dark .mobile-accordion-btn:hover { color: #f8fafc; background-color: rgba(255,255,255,0.05); }
        .dark .nav-dropdown > div { background-color: rgba(15,23,42,0.95); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); }
        .dark .nav-dropdown a { color: #e2e8f0; }
        .dark .nav-dropdown a:hover { background-color: rgba(255,255,255,0.05); color: #3b82f6; }
        
        /* Smooth transition for all nav elements */
        #navbar, #navbar * { transition: color 0.3s, background-color 0.3s, filter 0.3s, border-color 0.3s, box-shadow 0.3s, transform 0.3s; }
        
        /* Navbar sliding underline */
        .nav-link { position: relative; }
        .nav-link::after {
            content: ''; position: absolute; width: 0; height: 2px;
            bottom: 4px; left: 50%; transform: translateX(-50%);
            background-color: #0ea5e9; transition: width 0.3s ease;
            border-radius: 2px;
        }
        .nav-link:hover::after { width: 100%; }
    </style>
    <!-- Prevent FOUC -->
    <script>
        // Set Light Mode as default
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            if(!localStorage.getItem('color-theme')) localStorage.setItem('color-theme', 'light');
        }
    </script>
    <?php if(isset($extraHead)) echo $extraHead; ?>
</head>
<body class="font-sans antialiased text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 selection:bg-primary selection:text-white overflow-x-hidden transition-colors duration-300">

    <!-- ===== NAVBAR ===== -->
    <?php $navWhite = $navbarWhite ?? false; ?>
    <nav class="fixed w-full z-50 transition-all duration-300 <?= $navWhite ? 'navbar-white' : '' ?>" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <!-- Logo -->
                <a href="<?= BASE_URL ?>/" class="flex items-center space-x-2 shrink-0">
                    <img src="<?= BASE_URL ?>/assets/images/a.png" alt="<?= APP_NAME ?>" class="h-12 w-auto object-contain nav-logo">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="<?= BASE_URL ?>/" class="nav-link px-3 py-2 text-slate-600 hover:text-primary font-medium text-sm rounded-lg hover:bg-slate-100">Beranda</a>

                    <!-- Profil Dropdown -->
                    <div class="relative nav-item group">
                        <button class="nav-btn flex items-center px-3 py-2 text-slate-600 hover:text-primary font-medium text-sm rounded-lg hover:bg-slate-100">
                            Profil <i class="bi bi-chevron-down text-xs ml-1.5 transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="nav-dropdown absolute top-full left-0 pt-2 w-56 z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden py-1">
                                <a href="<?= BASE_URL ?>/page/sejarah-perusahaan" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                    <i class="bi bi-clock-history mr-3 text-primary/60"></i> Sejarah Perusahaan
                                </a>
                                <a href="<?= BASE_URL ?>/page/visi-misi" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                    <i class="bi bi-eye mr-3 text-primary/60"></i> Visi &amp; Misi
                                </a>
                                <a href="<?= BASE_URL ?>/page/struktur-organisasi" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                    <i class="bi bi-diagram-3 mr-3 text-primary/60"></i> Struktur Organisasi
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Layanan Dropdown -->
                    <div class="relative nav-item group">
                        <button class="nav-btn flex items-center px-3 py-2 text-slate-600 hover:text-primary font-medium text-sm rounded-lg hover:bg-slate-100">
                            Layanan <i class="bi bi-chevron-down text-xs ml-1.5 transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="nav-dropdown absolute top-full left-0 pt-2 w-64 z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden py-1">
                                <a href="<?= BASE_URL ?>/page/layanan-pengiriman" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                    <i class="bi bi-truck mr-3 text-primary/60"></i> Layanan Pengiriman
                                </a>
                                <a href="<?= BASE_URL ?>/page/layanan-pengemasan" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                    <i class="bi bi-box-seam mr-3 text-primary/60"></i> Layanan Pengemasan
                                </a>
                                <a href="<?= BASE_URL ?>/page/layanan-tracking" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                    <i class="bi bi-geo-alt mr-3 text-primary/60"></i> Tracking &amp; FAQ
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>/page/experience" class="nav-link px-3 py-2 text-slate-600 hover:text-primary font-medium text-sm rounded-lg hover:bg-slate-100">Experience</a>
                    <a href="<?= BASE_URL ?>/page/kontak-kami" class="nav-link px-3 py-2 text-slate-600 hover:text-primary font-medium text-sm rounded-lg hover:bg-slate-100">Kontak Kami</a>
                </div>

                <!-- CTA -->
                <div class="hidden lg:flex items-center ml-4">
                    <!-- Dark Mode Toggle Desktop -->
                    <button id="theme-toggle" type="button" class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none rounded-lg text-sm p-2.5 mr-4 transition-colors">
                        <i id="theme-toggle-dark-icon" class="bi bi-moon-fill hidden text-lg"></i>
                        <i id="theme-toggle-light-icon" class="bi bi-sun-fill hidden text-lg"></i>
                    </button>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="relative group px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-[0_0_15px_rgba(59,130,246,0.3)] hover:shadow-[0_0_25px_rgba(59,130,246,0.5)] transition-all duration-300 flex items-center text-sm hover:-translate-y-0.5">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="nav-cta-outline relative overflow-hidden group px-6 py-2.5 border-2 border-primary text-primary font-bold rounded-xl hover:text-white flex items-center text-sm transition-all duration-300">
                            <span class="absolute inset-0 w-full h-full transition-all duration-300 ease-out transform translate-x-[-100%] bg-primary group-hover:translate-x-0"></span>
                            <span class="relative flex items-center"><i class="bi bi-box-arrow-in-right mr-2 text-lg"></i> Login ERP</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Hamburger & Theme Toggle -->
                <div class="lg:hidden flex items-center">
                    <button id="theme-toggle-mobile" type="button" class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none rounded-lg text-sm p-2 mr-2 transition-colors">
                        <i id="theme-toggle-dark-icon-mobile" class="bi bi-moon-fill hidden text-xl"></i>
                        <i id="theme-toggle-light-icon-mobile" class="bi bi-sun-fill hidden text-xl"></i>
                    </button>
                    <button id="mobile-menu-btn" class="nav-hamburger text-slate-800 dark:text-white p-2 focus:outline-none">
                        <i class="bi bi-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-t border-slate-200/50 dark:border-slate-800/50 absolute w-full shadow-2xl left-0 top-full max-h-[80vh] overflow-y-auto transition-all duration-300">
            <div class="flex flex-col px-5 pt-3 pb-6 space-y-1">
                <a href="<?= BASE_URL ?>/" class="text-slate-700 dark:text-slate-200 font-medium py-2.5 px-3 border-b border-slate-50 dark:border-slate-800 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">Beranda</a>

                <!-- Mobile: Profil accordion -->
                <div class="border-b border-slate-50 dark:border-slate-800">
                    <button class="mobile-accordion-btn w-full flex justify-between items-center text-slate-700 dark:text-slate-200 font-medium py-2.5 px-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 text-left">
                        Profil <i class="bi bi-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div class="mobile-accordion-content pl-6 space-y-1 pb-2">
                        <a href="<?= BASE_URL ?>/page/sejarah-perusahaan" class="flex items-center py-2 px-3 text-sm text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i class="bi bi-clock-history mr-2 text-primary/60 dark:text-primary"></i> Sejarah Perusahaan
                        </a>
                        <a href="<?= BASE_URL ?>/page/visi-misi" class="flex items-center py-2 px-3 text-sm text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i class="bi bi-eye mr-2 text-primary/60 dark:text-primary"></i> Visi &amp; Misi
                        </a>
                        <a href="<?= BASE_URL ?>/page/struktur-organisasi" class="flex items-center py-2 px-3 text-sm text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i class="bi bi-diagram-3 mr-2 text-primary/60 dark:text-primary"></i> Struktur Organisasi
                        </a>
                    </div>
                </div>

                <!-- Mobile: Layanan accordion -->
                <div class="border-b border-slate-50 dark:border-slate-800">
                    <button class="mobile-accordion-btn w-full flex justify-between items-center text-slate-700 dark:text-slate-200 font-medium py-2.5 px-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 text-left">
                        Layanan <i class="bi bi-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div class="mobile-accordion-content pl-6 space-y-1 pb-2">
                        <a href="<?= BASE_URL ?>/page/layanan-pengiriman" class="flex items-center py-2 px-3 text-sm text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i class="bi bi-truck mr-2 text-primary/60 dark:text-primary"></i> Layanan Pengiriman
                        </a>
                        <a href="<?= BASE_URL ?>/page/layanan-pengemasan" class="flex items-center py-2 px-3 text-sm text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i class="bi bi-box-seam mr-2 text-primary/60 dark:text-primary"></i> Layanan Pengemasan
                        </a>
                        <a href="<?= BASE_URL ?>/page/layanan-tracking" class="flex items-center py-2 px-3 text-sm text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i class="bi bi-geo-alt mr-2 text-primary/60 dark:text-primary"></i> Tracking &amp; FAQ
                        </a>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>/page/experience" class="text-slate-700 dark:text-slate-200 font-medium py-2.5 px-3 border-b border-slate-50 dark:border-slate-800 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">Experience</a>
                <a href="<?= BASE_URL ?>/page/kontak-kami" class="text-slate-700 dark:text-slate-200 font-medium py-2.5 px-3 border-b border-slate-50 dark:border-slate-800 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">Kontak Kami</a>

                <div class="pt-4 pb-2">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="w-full flex justify-center items-center bg-primary text-white px-4 py-3.5 rounded-xl font-bold shadow-lg shadow-primary/30 transition-transform active:scale-95">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="w-full flex justify-center items-center bg-primary text-white px-4 py-3.5 rounded-xl font-bold shadow-lg shadow-primary/30 transition-transform active:scale-95">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Login ERP
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- ===== END NAVBAR ===== -->

    <!-- ===== PAGE CONTENT ===== -->
    <?= $slot ?? '' ?>
    <!-- ===== END PAGE CONTENT ===== -->

    <!-- ===== FOOTER ===== -->
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
                    <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-wider">Profil</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="<?= BASE_URL ?>/page/sejarah-perusahaan" class="hover:text-primary transition-colors">Sejarah Perusahaan</a></li>
                        <li><a href="<?= BASE_URL ?>/page/visi-misi" class="hover:text-primary transition-colors">Visi &amp; Misi</a></li>
                        <li><a href="<?= BASE_URL ?>/page/struktur-organisasi" class="hover:text-primary transition-colors">Struktur Organisasi</a></li>
                        <li><a href="<?= BASE_URL ?>/page/experience" class="hover:text-primary transition-colors">Experience</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-wider">Layanan</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="<?= BASE_URL ?>/page/layanan-pengiriman" class="hover:text-primary transition-colors">Layanan Pengiriman</a></li>
                        <li><a href="<?= BASE_URL ?>/page/layanan-pengemasan" class="hover:text-primary transition-colors">Layanan Pengemasan</a></li>
                        <li><a href="<?= BASE_URL ?>/page/layanan-tracking" class="hover:text-primary transition-colors">Tracking &amp; FAQ</a></li>
                        <li><a href="<?= BASE_URL ?>/page/kontak-kami" class="hover:text-primary transition-colors">Kontak Kami</a></li>
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
    <!-- ===== END FOOTER ===== -->

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ 
            once: false, // Make it alive on scroll up and down
            offset: 20,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // Navbar on scroll — also manages white-mode reversal
        const isNavbarWhite = <?= ($navbarWhite ?? false) ? 'true' : 'false' ?>;
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 60) {
                navbar.classList.add('nav-scrolled');
            } else {
                navbar.classList.remove('nav-scrolled');
            }
        });
        // Trigger once on load
        window.dispatchEvent(new Event('scroll'));

        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const icon = btn.querySelector('i');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-x-lg');
        });

        // Mobile Accordion
        document.querySelectorAll('.mobile-accordion-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                const chevron = btn.querySelector('.bi-chevron-down');
                content.classList.toggle('open');
                chevron.style.transform = content.classList.contains('open') ? 'rotate(180deg)' : '';
            });
        });

        // Dark Mode Logic
        function setupThemeToggle(btnId, darkIconId, lightIconId) {
            var themeToggleDarkIcon = document.getElementById(darkIconId);
            var themeToggleLightIcon = document.getElementById(lightIconId);
            var themeToggleBtn = document.getElementById(btnId);

            if (!themeToggleBtn) return;

            // Since default is dark, check if light mode is active
            if (localStorage.getItem('color-theme') === 'light') {
                themeToggleDarkIcon.classList.remove('hidden');
            } else {
                themeToggleLightIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');
                
                // Sync the other button if it exists
                const otherBtnId = btnId === 'theme-toggle' ? 'theme-toggle-mobile' : 'theme-toggle';
                const otherDarkId = otherBtnId === 'theme-toggle' ? 'theme-toggle-dark-icon' : 'theme-toggle-dark-icon-mobile';
                const otherLightId = otherBtnId === 'theme-toggle' ? 'theme-toggle-light-icon' : 'theme-toggle-light-icon-mobile';
                
                const otherDark = document.getElementById(otherDarkId);
                const otherLight = document.getElementById(otherLightId);
                
                if (otherDark && otherLight) {
                    if (themeToggleDarkIcon.classList.contains('hidden')) {
                        otherDark.classList.add('hidden');
                        otherLight.classList.remove('hidden');
                    } else {
                        otherDark.classList.remove('hidden');
                        otherLight.classList.add('hidden');
                    }
                }

                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        }
        
        setupThemeToggle('theme-toggle', 'theme-toggle-dark-icon', 'theme-toggle-light-icon');
        setupThemeToggle('theme-toggle-mobile', 'theme-toggle-dark-icon-mobile', 'theme-toggle-light-icon-mobile');

    </script>
    <?php if(isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
