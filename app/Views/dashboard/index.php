<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<!-- Header -->
<div class="bg-white px-8 pb-8 pt-8 border-b border-slate-200">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 mb-1">Dashboard</h2>
            <p class="text-slate-500 text-sm">Selamat datang, <strong><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></strong> — <?= date('l, d F Y') ?></p>
        </div>
        <a href="<?= BASE_URL ?>/analytics" class="hidden md:flex items-center space-x-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-secondary transition shadow-sm shadow-primary/30">
            <i class="bi bi-bar-chart-line"></i>
            <span>Lihat Analytics Lengkap</span>
        </a>
    </div>
</div>

<div class="px-8 mt-8">
    <!-- KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <?php
        $cards = [
            ['label'=>'Total Paket',    'value'=>$totalPaket,   'icon'=>'box-seam',        'grad'=>'from-blue-500 to-indigo-600'],
            ['label'=>'Hari Ini',       'value'=>$paketHariIni, 'icon'=>'plus-circle',      'grad'=>'from-cyan-500 to-blue-500'],
            ['label'=>'Dalam Proses',   'value'=>$paketTransit, 'icon'=>'truck',            'grad'=>'from-orange-400 to-amber-500'],
            ['label'=>'Selesai',        'value'=>$paketSelesai, 'icon'=>'check-circle',     'grad'=>'from-emerald-500 to-teal-600'],
            ['label'=>'Pending',        'value'=>$paketPending, 'icon'=>'hourglass-split',  'grad'=>'from-slate-400 to-slate-600'],
            ['label'=>'Saldo Kas',      'value'=>$saldoKas,     'icon'=>'wallet2',          'grad'=>'from-violet-500 to-purple-600'],
        ];
        foreach ($cards as $c):
        ?>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br <?= $c['grad'] ?> flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">
                <i class="bi bi-<?= $c['icon'] ?> text-white text-sm"></i>
            </div>
            <p class="text-lg font-bold text-slate-800 leading-tight"><?= $c['value'] ?></p>
            <p class="text-xs text-slate-500 mt-0.5"><?= $c['label'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Line Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h6 class="font-bold text-slate-700 flex items-center">
                    <i class="bi bi-graph-up mr-3 text-primary"></i> Statistik Pengiriman (7 Bulan)
                </h6>
            </div>
            <div class="p-6 h-72">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Status Doughnut -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h6 class="font-bold text-slate-700 flex items-center">
                    <i class="bi bi-pie-chart mr-3 text-primary"></i> Status Paket
                </h6>
            </div>
            <div class="p-6 h-72 flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions + Recent Packages -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="<?= BASE_URL ?>/packages" class="flex items-center p-3 rounded-xl hover:bg-primary/5 transition group">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"><i class="bi bi-plus-lg"></i></div>
                    <div><p class="text-sm font-semibold text-slate-700">Buat Resi Baru</p><p class="text-xs text-slate-400">Daftarkan paket kiriman</p></div>
                </a>
                <a href="<?= BASE_URL ?>/scan" class="flex items-center p-3 rounded-xl hover:bg-orange-50 transition group">
                    <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"><i class="bi bi-upc-scan"></i></div>
                    <div><p class="text-sm font-semibold text-slate-700">Scan Barcode</p><p class="text-xs text-slate-400">Proses inbound/outbound</p></div>
                </a>
                <a href="<?= BASE_URL ?>/manifests" class="flex items-center p-3 rounded-xl hover:bg-indigo-50 transition group">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"><i class="bi bi-truck"></i></div>
                    <div><p class="text-sm font-semibold text-slate-700">Buat Manifest</p><p class="text-xs text-slate-400">Siapkan surat jalan</p></div>
                </a>
                <a href="<?= BASE_URL ?>/finance" class="flex items-center p-3 rounded-xl hover:bg-emerald-50 transition group">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"><i class="bi bi-wallet2"></i></div>
                    <div><p class="text-sm font-semibold text-slate-700">Kas & Keuangan</p><p class="text-xs text-slate-400">Mutasi & setoran COD</p></div>
                </a>
            </div>
        </div>

        <!-- Recent Packages -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h6 class="font-bold text-slate-700"><i class="bi bi-clock-history mr-2 text-primary"></i> Paket Terbaru</h6>
                <a href="<?= BASE_URL ?>/packages" class="text-xs text-primary hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100">
                <?php if (empty($recentPackages)): ?>
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">
                        <i class="bi bi-inbox text-3xl mb-2 block opacity-30"></i>
                        Belum ada paket. <a href="<?= BASE_URL ?>/packages" class="text-primary">Buat resi pertama Anda!</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($recentPackages as $p): ?>
                    <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50/80 transition">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary text-xs font-bold flex items-center justify-center flex-shrink-0">
                                <?= strtoupper(substr($p['sender_name'], 0, 1)) ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-primary truncate font-mono"><?= htmlspecialchars($p['resi']) ?></p>
                                <p class="text-xs text-slate-500 truncate"><?= htmlspecialchars($p['sender_name']) ?> &rarr; <?= htmlspecialchars($p['receiver_name']) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 flex-shrink-0 ml-4">
                            <?php
                                $sc = 'bg-slate-100 text-slate-600';
                                if ($p['status'] === 'SELESAI') $sc = 'bg-emerald-100 text-emerald-700';
                                elseif (in_array($p['status'], ['TRANSIT','PICKUP','DELIVERY'])) $sc = 'bg-orange-100 text-orange-700';
                                elseif ($p['status'] === 'RETUR') $sc = 'bg-red-100 text-red-700';
                                elseif ($p['status'] === 'PENDING') $sc = 'bg-slate-100 text-slate-600';
                            ?>
                            <span class="px-2 py-0.5 rounded text-xs font-bold <?= $sc ?>"><?= $p['status'] ?></span>
                            <span class="text-xs text-slate-400"><?= date('d/m', strtotime($p['created_at'])) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const lineCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul"],
                datasets: [{
                    label: "Total Pengiriman",
                    tension: 0.4,
                    backgroundColor: "rgba(78,115,223,0.1)",
                    borderColor: "rgba(78,115,223,1)",
                    pointRadius: 4,
                    pointBackgroundColor: "rgba(78,115,223,1)",
                    pointBorderColor: "#fff",
                    borderWidth: 2.5,
                    data: [820, 1240, 980, 1780, 1450, 2100, 1870],
                    fill: true
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: '#f1f1f1', borderDash: [3] }, beginAtZero: true }
                }
            }
        });

        const dCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(dCtx, {
            type: 'doughnut',
            data: {
                labels: ["Selesai","Proses","Pending","Retur"],
                datasets: [{
                    data: [
                        <?= is_numeric($paketSelesai) ? $paketSelesai : 0 ?>,
                        <?= is_numeric($paketTransit) ? $paketTransit : 0 ?>,
                        <?= is_numeric($paketPending) ? $paketPending : 0 ?>,
                        1
                    ],
                    backgroundColor: ['#1cc88a','#f6c23e','#4e73df','#e74a3b'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '72%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 14, font: { size: 11 } } } }
            }
        });
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
