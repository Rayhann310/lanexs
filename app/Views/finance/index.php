<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="{ activeTab: 'cashflow' }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Keuangan & Kas</h2>
            <p class="text-slate-500 mt-1">Kelola arus kas cabang dan setoran COD</p>
        </div>
        
        <!-- Balance Card & Actions -->
        <div class="flex items-center space-x-4">
            <a href="<?= BASE_URL ?>/finance/export" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-2xl font-medium shadow-sm transition flex items-center h-full">
                <i class="bi bi-file-earmark-excel mr-2"></i> Export Excel
            </a>
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-4 text-white shadow-lg shadow-emerald-500/30 flex items-center min-w-[250px]">
                <div class="p-3 bg-white/20 rounded-xl mr-4">
                    <i class="bi bi-wallet2 text-2xl"></i>
                </div>
                <div>
                    <p class="text-emerald-50 text-sm font-medium">Saldo Kas Aktif</p>
                    <h3 class="text-2xl font-bold">Rp <?= number_format((float)($balance ?? 0), 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-center shadow-sm">
            <i class="bi bi-check-circle-fill mr-3"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-center shadow-sm">
            <i class="bi bi-exclamation-triangle-fill mr-3"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <div class="flex space-x-1 border-b border-slate-200 mb-6">
        <button @click="activeTab = 'cashflow'" 
                :class="activeTab === 'cashflow' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors focus:outline-none flex items-center">
            <i class="bi bi-arrow-left-right mr-2"></i> Mutasi Kas
        </button>
        <button @click="activeTab = 'cod'" 
                :class="activeTab === 'cod' ? 'border-orange-500 text-orange-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors focus:outline-none flex items-center">
            <i class="bi bi-box-seam mr-2"></i> Setoran COD
            <?php if(count($codPackages) > 0): ?>
                <span class="ml-2 bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full"><?= count($codPackages) ?></span>
            <?php endif; ?>
        </button>
    </div>

    <!-- Tab Content: Cashflow -->
    <div x-show="activeTab === 'cashflow'" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto p-2">
                <table id="transactionsTable" class="w-full whitespace-nowrap text-sm">
                    <thead class="bg-slate-50/50 text-slate-500 text-left font-semibold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-xl">Tanggal</th>
                            <?php if($_SESSION['role_id'] == 1): ?><th class="px-6 py-4">Cabang</th><?php endif; ?>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4">Referensi</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-right rounded-tr-xl">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($transactions as $t): ?>
                            <tr class="hover:bg-slate-50/80 transition group">
                                <td class="px-6 py-4 text-xs font-mono text-slate-500"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                                <?php if($_SESSION['role_id'] == 1): ?><td class="px-6 py-4 font-bold text-slate-800"><?= htmlspecialchars($t['branch_name']) ?></td><?php endif; ?>
                                <td class="px-6 py-4">
                                    <?php if($t['type'] == 'INCOME'): ?>
                                        <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold"><i class="bi bi-arrow-down-left mr-1"></i> MASUK</span>
                                    <?php else: ?>
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold"><i class="bi bi-arrow-up-right mr-1"></i> KELUAR</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-500 font-bold bg-slate-100 px-2 py-1 rounded"><?= $t['reference_type'] ?></span>
                                    <?php if($t['reference_id']): ?>
                                        <br><span class="text-xs font-mono text-indigo-500 mt-1 block"><?= htmlspecialchars($t['reference_id']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate" title="<?= htmlspecialchars($t['description']) ?>">
                                    <?= htmlspecialchars($t['description']) ?>
                                </td>
                                <td class="px-6 py-4 text-right font-bold <?= $t['type'] == 'INCOME' ? 'text-emerald-600' : 'text-red-600' ?>">
                                    <?= $t['type'] == 'INCOME' ? '+' : '-' ?> Rp <?= number_format($t['amount'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Content: COD Settlement -->
    <div x-show="activeTab === 'cod'" style="display: none;" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-orange-50/50">
                <p class="text-sm text-orange-700"><i class="bi bi-info-circle-fill mr-1"></i> Menampilkan paket COD yang sudah berstatus <strong>DELIVERED</strong> (Diterima oleh pelanggan) namun uangnya belum disetorkan oleh kurir ke kas Cabang.</p>
            </div>
            <div class="overflow-x-auto p-2">
                <table id="codTable" class="w-full whitespace-nowrap text-sm">
                    <thead class="bg-slate-50/50 text-slate-500 text-left font-semibold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-xl">Resi</th>
                            <th class="px-6 py-4">Penerima</th>
                            <th class="px-6 py-4">Nilai COD</th>
                            <th class="px-6 py-4 text-right rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($codPackages as $p): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-mono font-bold text-indigo-700"><?= htmlspecialchars($p['resi']) ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800"><?= htmlspecialchars($p['receiver_name']) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($p['dest']) ?></div>
                                </td>
                                <td class="px-6 py-4 font-bold text-orange-600 text-lg">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4 flex justify-end space-x-2">
                                    <form action="<?= BASE_URL ?>/finance/cod/settle" method="POST">
                                        <input type="hidden" name="package_id" value="<?= $p['id'] ?>">
                                        <button type="button" onclick="confirmSettle(this.form)" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                                            <i class="bi bi-wallet-fill mr-1"></i> Tarik Setoran
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script>
    function confirmSettle(form) {
        Swal.fire({
            title: 'Konfirmasi Setoran COD',
            text: "Apakah Kurir sudah menyerahkan uang tunai ini ke kasir?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            confirmButtonText: 'Ya, Uang Diterima',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    $(document).ready(function() {
        $('#transactionsTable').DataTable({
            "pageLength": 15,
            "order": [[0, "desc"]],
            "language": { "search": "", "searchPlaceholder": "Cari transaksi..." },
            "dom": '<"flex justify-between items-center py-3 border-b"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center p-4"ip>'
        });
        
        $('#codTable').DataTable({
            "pageLength": 10,
            "language": { "search": "", "searchPlaceholder": "Cari resi COD..." },
            "dom": '<"flex justify-between items-center py-3 border-b"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center p-4"ip>'
        });
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
