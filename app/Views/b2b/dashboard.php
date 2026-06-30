<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($customer['company_name'] ?? 'B2B Portal') ?> - LANEXS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4e73df',
                        secondary: '#224abe',
                        success: '#1cc88a'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

<!-- Header -->
<header class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-md">
                <i class="bi bi-truck text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-800">LANEXS B2B Portal</h1>
                <p class="text-xs text-slate-400"><?= htmlspecialchars($customer['company_name'] ?? '') ?></p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <div class="hidden sm:flex items-center space-x-2 text-sm text-slate-600">
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                    <?= strtoupper(substr($customer['pic_name'] ?? 'K', 0, 1)) ?>
                </div>
                <span class="font-medium"><?= htmlspecialchars($customer['pic_name'] ?? '') ?></span>
            </div>
            <a href="<?= BASE_URL ?>/logout" class="text-sm text-slate-500 hover:text-red-500 transition flex items-center">
                <i class="bi bi-box-arrow-right mr-1"></i> Keluar
            </a>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-8">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-6 mb-8 text-white shadow-lg shadow-primary/30">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <p class="text-primary-100 font-medium text-sm opacity-80">Selamat Datang,</p>
                <h2 class="text-2xl font-bold"><?= htmlspecialchars($customer['company_name'] ?? '') ?></h2>
                <p class="text-sm opacity-70 mt-1">PIC: <?= htmlspecialchars($customer['pic_name'] ?? '') ?> | <?= htmlspecialchars($customer['phone'] ?? '') ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs opacity-70 uppercase tracking-wider">Limit Kredit</p>
                <p class="text-2xl font-bold">Rp <?= number_format($customer['credit_limit'] ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
                <i class="bi bi-box-seam text-blue-500 text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?= $total ?></p>
            <p class="text-sm text-slate-500">Total Pengiriman</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
                <i class="bi bi-check-circle text-emerald-500 text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?= $delivered ?></p>
            <p class="text-sm text-slate-500">Terkirim</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center mb-3">
                <i class="bi bi-truck text-orange-500 text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?= $inTransit ?></p>
            <p class="text-sm text-slate-500">Dalam Perjalanan</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <?php if ($countUnpaid > 0): ?>
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-3">
                    <i class="bi bi-receipt text-red-500 text-lg"></i>
                </div>
                <p class="text-2xl font-bold text-red-600"><?= $countUnpaid ?></p>
                <p class="text-sm text-slate-500">Tagihan Belum Lunas</p>
                <p class="text-xs font-bold text-red-500 mt-1">Rp <?= number_format($totalUnpaid, 0, ',', '.') ?></p>
            <?php else: ?>
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
                    <i class="bi bi-shield-check text-emerald-500 text-lg"></i>
                </div>
                <p class="text-2xl font-bold text-emerald-600">Lunas</p>
                <p class="text-sm text-slate-500">Status Tagihan</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Packages Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800"><i class="bi bi-list-ul text-primary mr-2"></i> Riwayat Pengiriman Anda</h3>
        </div>
        <div class="overflow-x-auto p-2">
            <table id="b2bPackagesTable" class="w-full whitespace-nowrap text-sm">
                <thead class="bg-slate-50/50 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-xl">No. Resi</th>
                        <th class="px-6 py-4">Penerima</th>
                        <th class="px-6 py-4">Rute</th>
                        <th class="px-6 py-4">Status Paket</th>
                        <th class="px-6 py-4">Status Bayar</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4 rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($packages as $pkg): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-primary"><?= htmlspecialchars($pkg['resi']) ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium"><?= htmlspecialchars($pkg['receiver_name']) ?></div>
                                <div class="text-xs text-slate-400"><?= htmlspecialchars($pkg['receiver_phone']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="font-medium text-slate-600"><?= htmlspecialchars($pkg['origin_city'] ?? '') ?></span>
                                <i class="bi bi-arrow-right mx-1 text-slate-400"></i>
                                <span class="font-medium text-slate-600"><?= htmlspecialchars($pkg['dest_city'] ?? '') ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $sc = 'bg-slate-100 text-slate-700';
                                    if ($pkg['status'] === 'SELESAI') $sc = 'bg-emerald-100 text-emerald-700';
                                    elseif (in_array($pkg['status'], ['TRANSIT','PICKUP','DELIVERY'])) $sc = 'bg-orange-100 text-orange-700';
                                    elseif ($pkg['status'] === 'RETUR') $sc = 'bg-red-100 text-red-700';
                                ?>
                                <span class="px-2 py-1 rounded text-xs font-bold <?= $sc ?>"><?= $pkg['status'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $pc = 'bg-slate-100 text-slate-700';
                                    if ($pkg['payment_status'] === 'PAID') $pc = 'bg-emerald-100 text-emerald-700';
                                    elseif ($pkg['payment_status'] === 'COD') $pc = 'bg-orange-100 text-orange-700';
                                    elseif ($pkg['payment_status'] === 'UNPAID') $pc = 'bg-red-100 text-red-700';
                                ?>
                                <span class="px-2 py-1 rounded text-xs font-bold <?= $pc ?>"><?= $pkg['payment_status'] ?? 'N/A' ?></span>
                            </td>
                            <td class="px-6 py-4 font-bold">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4">
                                <a href="<?= BASE_URL ?>/tracking?resi=<?= urlencode($pkg['resi']) ?>" target="_blank"
                                   class="bg-primary/10 text-primary hover:bg-primary hover:text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center w-max">
                                    <i class="bi bi-geo-alt mr-1"></i> Track
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#b2bPackagesTable').DataTable({
            "pageLength": 15,
            "order": [[0, "desc"]],
            "language": { "search": "", "searchPlaceholder": "Cari resi, penerima..." },
            "dom": '<"flex justify-between items-center py-3 px-4 border-b"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center p-4"ip>'
        });
    });
</script>
</body>
</html>
