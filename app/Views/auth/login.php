<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LANEX Enterprise</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#3b82f6', // blue-500
                        secondary: '#14b8a6' // teal-500
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-900">

    <!-- Background Elements -->
    <div class="absolute inset-0 z-0">
        <!-- Main background image with overlay -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1586528116311-ad8ed7c83a00?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center opacity-30 mix-blend-luminosity"></div>
        
        <!-- Gradient overlays -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-slate-900/95 to-teal-900/90"></div>
        
        <!-- Animated glowing orbs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/30 rounded-full blur-[100px] animate-float" style="animation-delay: 0s;"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-teal-500/20 rounded-full blur-[100px] animate-float" style="animation-delay: -3s;"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-5xl p-4 sm:p-6 lg:p-8 flex flex-col md:flex-row items-stretch">
        
        <!-- Left Panel: Branding & Info -->
        <div class="hidden md:flex flex-col justify-between w-1/2 p-10 lg:p-14 text-white">
            <div>
                <a href="<?= BASE_URL ?>/" class="inline-block mb-12 hover:scale-105 transition-transform duration-300">
                    <img src="<?= BASE_URL ?>/assets/images/a.png" alt="LANEX" class="h-14 w-auto brightness-0 invert drop-shadow-md">
                </a>
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-6 leading-tight drop-shadow-lg">
                    Sistem Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-300">Ekspedisi Terpadu</span>
                </h1>
                <p class="text-lg text-slate-300 leading-relaxed max-w-md drop-shadow">
                    Kendalikan seluruh operasional cabang, pelacakan armada, dan manajemen manifes dalam satu platform enterprise.
                </p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://ui-avatars.com/api/?name=Admin+1&background=random" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://ui-avatars.com/api/?name=Manager+2&background=random" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://ui-avatars.com/api/?name=Kurir+3&background=random" alt="User">
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-white">1,200+ Pengguna Aktif</p>
                    <p class="text-slate-400 text-xs">Di seluruh cabang Indonesia</p>
                </div>
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center">
            <div class="glass-panel rounded-[2.5rem] p-8 sm:p-12 w-full max-w-md shadow-[0_20px_50px_rgba(0,0,0,0.3)]">
                
                <!-- Mobile Logo -->
                <div class="md:hidden flex justify-center mb-8">
                    <img src="<?= BASE_URL ?>/assets/images/a.png" alt="LANEX" class="h-12 w-auto drop-shadow-sm">
                </div>

                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight mb-2">Selamat Datang</h2>
                    <p class="text-slate-500 text-sm">Silakan masukkan kredensial Anda untuk masuk.</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm mb-6 border border-red-100 flex items-start shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill mr-3 mt-0.5 text-lg"></i>
                        <span class="font-medium"><?= $_SESSION['error'] ?></span>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/login" method="POST" class="space-y-6">
                    <?= \App\Helpers\SecurityHelper::csrfField() ?>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Username</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="bi bi-person text-slate-400 text-lg group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" name="username" class="w-full pl-12 pr-5 py-4 bg-white/60 border-2 border-white/80 rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-slate-800 placeholder-slate-400 font-medium shadow-inner" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2 ml-1">
                            <label class="block text-sm font-bold text-slate-700">Password</label>
                            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lupa Password?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="bi bi-lock text-slate-400 text-lg group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="password" name="password" class="w-full pl-12 pr-5 py-4 bg-white/60 border-2 border-white/80 rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-slate-800 placeholder-slate-400 font-medium shadow-inner" placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-2xl transition-all shadow-[0_10px_20px_-10px_rgba(37,99,235,0.6)] hover:shadow-[0_15px_30px_-10px_rgba(37,99,235,0.8)] flex justify-center items-center transform hover:-translate-y-0.5 active:scale-95">
                            <span>Masuk ke Sistem</span>
                            <i class="bi bi-arrow-right-short text-2xl ml-1 -mr-2"></i>
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 text-center">
                    <p class="text-xs font-medium text-slate-400">&copy; <?= date('Y') ?> LANEX Logistics Enterprise.</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
