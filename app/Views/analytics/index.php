<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('head'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('content'); ?>

<?php
// Prepare PHP data for JS
$revDays    = array_column($revTrend, 'day');
$revAmounts = array_column($revTrend, 'revenue');
$revPkgs    = array_column($revTrend, 'pkgs');

$statusLabels = array_column($byStatus, 'status');
$statusTotals = array_column($byStatus, 'total');

$branchNames   = array_column($topBranches, 'name');
$branchVolumes = array_column($topBranches, 'total');
$branchRevenue = array_column($topBranches, 'revenue');

$payLabels  = array_column($payMethods, 'payment_type');
$payAmounts = array_column($payMethods, 'amount');

$growthPct = $monthly['last_month'] > 0
    ? round((($monthly['this_month'] - $monthly['last_month']) / $monthly['last_month']) * 100, 1)
    : 100;
$growthPkgPct = $monthly['last_month_pkgs'] > 0
    ? round((($monthly['this_month_pkgs'] - $monthly['last_month_pkgs']) / $monthly['last_month_pkgs']) * 100, 1)
    : 100;

$deliveryRate = $kpi['total_packages'] > 0
    ? round(($kpi['delivered'] / $kpi['total_packages']) * 100, 1)
    : 0;
?>

<div class="px-8 py-8 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Analytics</h2>
            <p class="text-slate-500 mt-1">Laporan performa & kinerja operasional secara menyeluruh</p>
        </div>
        <div class="flex items-center space-x-2 text-sm text-slate-500 bg-slate-100 px-4 py-2 rounded-xl">
            <i class="bi bi-clock text-primary"></i>
            <span>Data real-time: <?= date('d M Y, H:i') ?> WIB</span>
        </div>
    </div>

    <!-- Month-over-month growth banner -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-br from-primary to-secondary rounded-2xl p-5 text-white shadow-lg shadow-primary/30">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-70 font-medium">Pendapatan Bulan Ini</p>
                    <h3 class="text-3xl font-bold mt-1">Rp <?= number_format($monthly['this_month'], 0, ',', '.') ?></h3>
                    <p class="text-sm opacity-70 mt-1"><?= $monthly['this_month_pkgs'] ?> paket dikirim</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $growthPct >= 0 ? 'bg-emerald-400/30 text-emerald-100' : 'bg-red-400/30 text-red-100' ?>">
                        <i class="bi bi-arrow-<?= $growthPct >= 0 ? 'up' : 'down' ?>-short mr-1"></i>
                        <?= abs($growthPct) ?>% vs bulan lalu
                    </span>
                    <p class="text-xs opacity-60 mt-2">Bulan lalu: Rp <?= number_format($monthly['last_month'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/30">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-70 font-medium">Paket Bulan Ini</p>
                    <h3 class="text-3xl font-bold mt-1"><?= number_format($monthly['this_month_pkgs']) ?></h3>
                    <p class="text-sm opacity-70 mt-1">Delivery rate: <strong><?= $deliveryRate ?>%</strong></p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $growthPkgPct >= 0 ? 'bg-emerald-400/30 text-emerald-100' : 'bg-red-400/30 text-red-100' ?>">
                        <i class="bi bi-arrow-<?= $growthPkgPct >= 0 ? 'up' : 'down' ?>-short mr-1"></i>
                        <?= abs($growthPkgPct) ?>% vs bulan lalu
                    </span>
                    <p class="text-xs opacity-60 mt-2">Bulan lalu: <?= number_format($monthly['last_month_pkgs']) ?> paket</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards (4 in a row) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $cards = [
            ['icon' => 'box-seam',      'color' => 'blue',    'label' => 'Total Paket',     'value' => number_format((float)($kpi['total_packages'] ?? 0))],
            ['icon' => 'check-circle',  'color' => 'emerald', 'label' => 'Terkirim',         'value' => number_format((float)($kpi['delivered'] ?? 0))],
            ['icon' => 'arrow-repeat',  'color' => 'orange',  'label' => 'Return/Gagal',     'value' => number_format((float)($kpi['retur'] ?? 0))],
            ['icon' => 'currency-dollar','color'=> 'violet',  'label' => 'Total Tagihan Blm Lunas', 'value' => 'Rp ' . number_format((float)($kpi['unpaid_revenue'] ?? 0), 0, ',', '.')],
        ];
        foreach ($cards as $c):
        ?>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-<?= $c['color'] ?>-50 rounded-xl flex items-center justify-center mb-3">
                <i class="bi bi-<?= $c['icon'] ?> text-<?= $c['color'] ?>-500 text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?= $c['value'] ?></p>
            <p class="text-sm text-slate-500 mt-0.5"><?= $c['label'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Charts Row 1: Revenue Trend + Delivery Rate Gauge -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Revenue Trend (large) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-slate-800">Tren Pendapatan</h3>
                    <p class="text-xs text-slate-400">30 hari terakhir</p>
                </div>
                <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Rp <?= number_format(array_sum($revAmounts), 0, ',', '.') ?></span>
            </div>
            <canvas id="revenueTrendChart" height="100"></canvas>
        </div>

        <!-- Package Status Donut -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800">Status Paket</h3>
                <p class="text-xs text-slate-400">Distribusi saat ini</p>
            </div>
            <canvas id="statusDonutChart" height="180"></canvas>
            <div class="mt-4 space-y-2">
                <?php foreach ($byStatus as $s): ?>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-600"><?= $s['status'] ?></span>
                    <span class="font-bold text-slate-800"><?= $s['total'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Charts Row 2: Branch Performance + Payment Split -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top Branches Bar Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800">Top 5 Cabang (Volume)</h3>
                <p class="text-xs text-slate-400">Berdasarkan jumlah paket dikirim</p>
            </div>
            <canvas id="branchBarChart" height="200"></canvas>
        </div>

        <!-- Payment Method Doughnut -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800">Metode Pembayaran</h3>
                <p class="text-xs text-slate-400">Distribusi nominal per metode</p>
            </div>
            <canvas id="paymentDonutChart" height="200"></canvas>
            <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs">
                <?php foreach ($payMethods as $pm): ?>
                <div class="bg-slate-50 rounded-xl p-2">
                    <div class="font-bold text-slate-800"><?= $pm['payment_type'] ?></div>
                    <div class="text-slate-500">Rp <?= number_format($pm['amount'], 0, ',', '.') ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Branch Performance Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-slate-800">Performa Cabang</h3>
                <p class="text-xs text-slate-400">Ranking berdasarkan volume pengiriman</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/50 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Cabang</th>
                        <th class="px-6 py-4">Volume Paket</th>
                        <th class="px-6 py-4">Total Pendapatan</th>
                        <th class="px-6 py-4">Avg/Paket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($topBranches as $i => $br): ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4">
                            <?php if ($i === 0): ?>
                                <span class="w-6 h-6 bg-yellow-400 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <?php elseif ($i === 1): ?>
                                <span class="w-6 h-6 bg-slate-300 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <?php elseif ($i === 2): ?>
                                <span class="w-6 h-6 bg-amber-600 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <?php else: ?>
                                <span class="text-slate-400 font-bold px-1"><?= $i + 1 ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800"><?= htmlspecialchars($br['name']) ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <span class="font-bold"><?= $br['total'] ?></span>
                                <?php $pct = $kpi['total_packages'] > 0 ? round(($br['total'] / $kpi['total_packages']) * 100) : 0 ?>
                                <div class="flex-1 bg-slate-100 rounded-full h-1.5 max-w-[80px]">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                                </div>
                                <span class="text-xs text-slate-400"><?= $pct ?>%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-600">Rp <?= number_format($br['revenue'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-slate-600">
                            Rp <?= $br['total'] > 0 ? number_format($br['revenue'] / $br['total'], 0, ',', '.') : 0 ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script>
    // ── Color Palette ─────────────────────────────────────────────────────────
    const palette = ['#4e73df','#1cc88a','#f6c23e','#e74a3b','#858796','#36b9cc','#fd7e14','#6f42c1'];

    // ── Revenue Trend Line Chart ──────────────────────────────────────────────
    new Chart(document.getElementById('revenueTrendChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($revDays) ?>,
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($revAmounts) ?>,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78,115,223,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4e73df',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    yAxisID: 'yRevenue'
                },
                {
                    label: 'Jumlah Paket',
                    data: <?= json_encode($revPkgs) ?>,
                    borderColor: '#1cc88a',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    borderDash: [5,5],
                    pointRadius: 2,
                    yAxisID: 'yPkgs'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                yRevenue: {
                    type: 'linear', position: 'left',
                    ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K', font: { size: 11 } },
                    grid: { color: '#f1f1f1' }
                },
                yPkgs: { type: 'linear', position: 'right', grid: { display: false }, ticks: { font: { size: 11 } } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });

    // ── Status Donut ─────────────────────────────────────────────────────────
    new Chart(document.getElementById('statusDonutChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($statusLabels) ?>,
            datasets: [{ data: <?= json_encode($statusTotals) ?>, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
    });

    // ── Branch Bar Chart ──────────────────────────────────────────────────────
    new Chart(document.getElementById('branchBarChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($branchNames) ?>,
            datasets: [
                {
                    label: 'Jumlah Paket',
                    data: <?= json_encode($branchVolumes) ?>,
                    backgroundColor: 'rgba(78,115,223,0.8)',
                    borderRadius: 8
                },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { font: { size: 11 } }, grid: { display: false } },
                y: { ticks: { font: { size: 11 } }, grid: { color: '#f1f1f1' } }
            }
        }
    });

    // ── Payment Donut ─────────────────────────────────────────────────────────
    new Chart(document.getElementById('paymentDonutChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($payLabels) ?>,
            datasets: [{ data: <?= json_encode($payAmounts) ?>, backgroundColor: ['#4e73df','#1cc88a','#f6c23e'], borderWidth: 2, borderColor: '#fff' }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 12 } } } }, cutout: '60%' }
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
