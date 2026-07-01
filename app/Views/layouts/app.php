<?php
$uri = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard    = strpos($uri, '/dashboard')    !== false;
$isBranches     = strpos($uri, '/branches')     !== false;
$isWarehouses   = strpos($uri, '/warehouses')   !== false;
$isPackages     = strpos($uri, '/packages')     !== false && strpos($uri, '/packages/print') === false;
$isManifests    = strpos($uri, '/manifests')    !== false;
$isScan         = strpos($uri, '/scan')         !== false;
$isFleet        = strpos($uri, '/fleet')        !== false;
$isFinance      = strpos($uri, '/finance')      !== false;
$isTariffs      = strpos($uri, '/tariffs')      !== false;
$isCustomers    = strpos($uri, '/customers')    !== false;
$isEmployees    = strpos($uri, '/employees')    !== false;
$isAudit        = strpos($uri, '/audit-logs')   !== false;
$isAnalytics    = strpos($uri, '/analytics')    !== false;
$isSettingsSystem  = strpos($uri, '/settings/system')  !== false;
$isSettingsProfile = strpos($uri, '/settings/profile') !== false;

$isOperational = $isPackages || $isManifests || $isScan;
$isMasterData  = $isBranches || $isWarehouses || $isFleet || $isFinance || $isTariffs || $isCustomers || $isEmployees;
$isSettings    = $isSettingsSystem || $isSettingsProfile;
$roleId = $_SESSION['role_id'] ?? 4;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/favicon.ico">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config for Custom Colors/Fonts -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4e73df',
                        secondary: '#858796',
                        success: '#1cc88a',
                        info: '#36b9cc',
                        warning: '#f6c23e',
                        danger: '#e74a3b',
                        darkbg: '#1a1a27',
                        darkerbg: '#12121b',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons (still useful with Tailwind) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tom Select — searchable/creatable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/indonesia-cities.js"></script>
    
    <style>
        /* Tom Select — match Tailwind/app style */
        .ts-wrapper { width: 100%; }
        .ts-control { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; background: #f8fafc !important; padding: 0.45rem 1rem !important; font-size: 0.875rem !important; min-height: 42px !important; }
        .ts-control:focus-within { border-color: #4e73df !important; box-shadow: 0 0 0 3px rgba(78,115,223,0.15) !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; font-size: 0.875rem !important; }
        .ts-dropdown .option { padding: 0.5rem 1rem !important; }
        .ts-dropdown .option.selected, .ts-dropdown .option:hover { background: #eff3ff !important; color: #4e73df !important; }
        .ts-wrapper.focus .ts-control { border-color: #4e73df !important; }
        /* Compact version for table cells */
        .ts-compact .ts-control { min-height: 30px !important; padding: 0.2rem 0.5rem !important; font-size: 0.7rem !important; border-radius: 0.5rem !important; }
        .ts-compact .ts-dropdown { font-size: 0.7rem !important; }
    </style>
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- DataTables (Tailwind) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- Custom styling to override DataTables for Tailwind look -->
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar for sidebar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
        
        /* Modern DataTables Styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.25rem 0.75rem;
            margin-left: 0.5rem;
            outline: none;
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.2);
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.25rem 2rem 0.25rem 0.75rem;
            outline: none;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid #f1f5f9;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.5rem;
            border: 1px solid transparent;
            padding: 0.25rem 0.75rem;
            margin: 0 0.25rem;
            color: #64748b !important;
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #4e73df !important;
            color: white !important;
            border-color: #4e73df !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">

    <!-- Sidebar Overlay for Mobile -->
    <div x-show="mobileSidebarOpen" 
         x-transition.opacity
         @click="mobileSidebarOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 md:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
           class="bg-white text-slate-600 w-64 flex-shrink-0 transition-all duration-300 flex flex-col h-full z-50 fixed inset-y-0 left-0 md:relative border-r border-slate-200"
           :style="!sidebarOpen && 'margin-left: -16rem;'">
        <!-- Sidebar Header -->
        <div class="h-20 px-6 flex items-center justify-between md:justify-center border-b border-slate-100">
            <a href="<?= BASE_URL ?>/" class="flex items-center space-x-3 group">
                <img src="<?= APP_LOGO ?>" alt="<?= APP_NAME ?>" class="w-10 h-10 object-contain rounded-xl p-1 bg-white border border-slate-100 shadow-sm transition-all group-hover:scale-105">
                <span class="font-bold text-xl tracking-tight text-slate-800"><?= explode(' ', APP_NAME)[0] ?> <span class="font-light text-slate-500"><?= implode(' ', array_slice(explode(' ', APP_NAME), 1)) ?: 'ERP' ?></span></span>
            </a>
            <button @click="mobileSidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-800 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto py-5 px-3 sidebar-scroll space-y-0.5">

            <!-- Dashboard -->
            <a href="<?= BASE_URL ?>/dashboard" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isDashboard ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-grid-1x2-fill w-4 text-center"></i>
                <span>Dashboard</span>
            </a>

            <!-- ── OPERASIONAL ── -->
            <div class="pt-4 pb-1 px-3"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Operasional</p></div>

            <a href="<?= BASE_URL ?>/packages" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isPackages ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-box-seam w-4 text-center"></i>
                <span>Manajemen Paket</span>
            </a>

            <a href="<?= BASE_URL ?>/manifests" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isManifests ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-journals w-4 text-center"></i>
                <span>Karung &amp; Manifest</span>
            </a>

            <a href="<?= BASE_URL ?>/scan" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isScan ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-upc-scan w-4 text-center"></i>
                <span>Scanner &amp; Status</span>
                <?php if (!$isScan): ?><span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Live</span><?php endif; ?>
            </a>

            <!-- ── MASTER DATA (Admin Only) ── -->
            <?php if ($roleId == 1): ?>
            <div class="pt-4 pb-1 px-3"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Master Data</p></div>

            <a href="<?= BASE_URL ?>/branches" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isBranches ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-building w-4 text-center"></i>
                <span>Cabang</span>
            </a>

            <a href="<?= BASE_URL ?>/warehouses" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isWarehouses ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-archive w-4 text-center"></i>
                <span>Gudang</span>
            </a>

            <a href="<?= BASE_URL ?>/fleet" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isFleet ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-truck w-4 text-center"></i>
                <span>Armada &amp; Kurir</span>
            </a>

            <a href="<?= BASE_URL ?>/tariffs" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isTariffs ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-tags w-4 text-center"></i>
                <span>Manajemen Tarif</span>
            </a>

            <a href="<?= BASE_URL ?>/customers" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isCustomers ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-briefcase w-4 text-center"></i>
                <span>Klien B2B</span>
            </a>

            <a href="<?= BASE_URL ?>/employees" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isEmployees ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-people w-4 text-center"></i>
                <span>Data Karyawan</span>
            </a>
            <?php elseif ($roleId == 2): ?>

            <!-- ── Owner sees employees & finance ── -->
            <div class="pt-4 pb-1 px-3"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manajemen</p></div>
            <a href="<?= BASE_URL ?>/employees" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isEmployees ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-people w-4 text-center"></i>
                <span>Data Karyawan</span>
            </a>
            <?php endif; ?>

            <!-- ── KEUANGAN (Admin + Owner) ── -->
            <?php if (in_array($roleId, [1, 2])): ?>
            <div class="pt-4 pb-1 px-3"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keuangan</p></div>

            <a href="<?= BASE_URL ?>/finance" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isFinance ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-cash-stack w-4 text-center"></i>
                <span>Keuangan &amp; Kas</span>
            </a>
            <?php endif; ?>

            <!-- ── LAPORAN & SISTEM (Admin Only) ── -->
            <?php if ($roleId == 1): ?>
            <div class="pt-4 pb-1 px-3"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Laporan &amp; Sistem</p></div>

            <a href="<?= BASE_URL ?>/analytics" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isAnalytics ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-bar-chart-line w-4 text-center"></i>
                <span>Analytics</span>
            </a>

            <a href="<?= BASE_URL ?>/audit-logs" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isAudit ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-shield-lock w-4 text-center"></i>
                <span>Audit Trail</span>
            </a>
            <?php endif; ?>

            <!-- ── PENGATURAN ── -->
            <div class="pt-4 pb-1 px-3"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pengaturan</p></div>

            <a href="<?= BASE_URL ?>/settings/profile" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isSettingsProfile ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-person-gear w-4 text-center"></i>
                <span>Profil Saya</span>
            </a>

            <?php if ($roleId == 1): ?>
            <a href="<?= BASE_URL ?>/settings/system" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isSettingsSystem ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-gear w-4 text-center"></i>
                <span>Sistem & Database</span>
            </a>
            
            <a href="<?= BASE_URL ?>/settings/landing" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all font-medium <?= $isSettingsLanding ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' ?>">
                <i class="bi bi-layout-text-window-reverse w-4 text-center"></i>
                <span>Landing Page</span>
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center space-x-3 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs text-slate-500 font-medium">Sistem Online</span>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full relative bg-slate-50/50">
        <!-- Navbar Glassmorphism -->
        <header class="bg-white/70 backdrop-blur-md border-b border-slate-200/60 z-10 px-6 h-20 flex items-center justify-between w-full sticky top-0 shadow-sm shadow-slate-100/50 transition-all">
            <button @click="if(window.innerWidth < 768) { mobileSidebarOpen = true } else { sidebarOpen = !sidebarOpen }" class="text-slate-500 hover:text-primary focus:outline-none transition-colors p-2 rounded-lg hover:bg-slate-100">
                <i class="bi bi-list text-2xl"></i>
            </button>
            
            <div class="flex items-center space-x-5">
                <!-- Notifications -->
                <button class="text-slate-500 hover:text-primary relative transition-colors p-2 rounded-full hover:bg-slate-100 bg-white shadow-sm border border-slate-100">
                    <i class="bi bi-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                </button>
                
                <div class="h-8 w-px bg-slate-200"></div>
                
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center space-x-3 focus:outline-none hover:opacity-80 transition-opacity bg-white pl-2 pr-4 py-1.5 rounded-full shadow-sm border border-slate-100">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['fullname'] ?? 'User') ?>&background=4e73df&color=fff&bold=true" class="w-8 h-8 rounded-full border border-slate-200">
                        <div class="text-left hidden md:block">
                            <p class="text-sm font-bold text-slate-700 leading-none"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Guest') ?></p>
                            <p class="text-[10px] text-slate-500 mt-0.5 uppercase tracking-wide font-semibold text-primary">Administrator</p>
                        </div>
                        <i class="bi bi-chevron-down text-xs text-slate-400 hidden md:block ml-2"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" 
                         x-transition
                         style="display: none;"
                         class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl py-2 border border-slate-100 z-50 origin-top-right">
                        <div class="px-4 py-3 border-b border-slate-50 mb-1">
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-0.5">Masuk sebagai</p>
                            <p class="text-sm font-bold text-slate-800 truncate">@<?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></p>
                        </div>
                        <a href="<?= BASE_URL ?>/settings/profile" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><i class="bi bi-person mr-3 text-lg"></i> Profil Saya</a>
                        <a href="<?= BASE_URL ?>/settings/system" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><i class="bi bi-gear mr-3 text-lg"></i> Pengaturan Sistem</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="<?= BASE_URL ?>/logout" class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"><i class="bi bi-box-arrow-right mr-3 text-lg"></i> Keluar Aplikasi</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Scrollable -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto relative flex flex-col justify-between">
            <div class="pb-12">
                <?php \App\Helpers\View::renderSection('content'); ?>
            </div>
            
            <!-- Dashboard Footer -->
            <footer class="bg-white border-t border-slate-200 mt-auto py-4 px-6 text-sm text-slate-500 flex flex-col md:flex-row justify-between items-center">
                <div>&copy; <?= date('Y') ?> <b><?= APP_NAME ?></b>. All rights reserved.</div>
                <div class="flex space-x-4 mt-2 md:mt-0">
                    <a href="<?= BASE_URL ?>/docs" target="_blank" class="text-primary hover:text-secondary font-semibold transition-colors flex items-center">
                        <i class="bi bi-book mr-1.5"></i> Baca Dokumentasi (Manual)
                    </a>
                </div>
            </footer>
        </main>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <?php \App\Helpers\View::renderSection('scripts'); ?>
</body>
</html>
