<?php App\Helpers\View::extends('app'); ?>

<?php App\Helpers\View::section('content'); ?>
<div class="px-4 md:px-8 py-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Buku Keuangan</h2>
            <p class="text-slate-500 mt-1">Pencatatan pemasukan & pengeluaran pengiriman barang</p>
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="bg-primary hover:bg-primaryHover text-white px-5 py-3 rounded-2xl font-semibold shadow-sm transition flex items-center gap-2 shrink-0">
            <i class="bi bi-plus-lg"></i> Tambah Entri
        </button>
    </div>

    <!-- Alerts -->
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

    <!-- Summary Cards -->
    <?php if (!empty($rows)): ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Total Invoice</p>
            <p class="text-xl font-black text-slate-800">Rp <?= number_format($totals['tagihan_invoice'], 0, ',', '.') ?></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Total Harga Pokok</p>
            <p class="text-xl font-black text-red-600">Rp <?= number_format($totals['harga_pokok'], 0, ',', '.') ?></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Profit Sblm Pajak</p>
            <p class="text-xl font-black text-amber-600">Rp <?= number_format($totals['profit_sblm'], 0, ',', '.') ?></p>
        </div>
        <div class="bg-gradient-to-r from-primary to-teal-500 rounded-2xl shadow-md p-5 text-white">
            <p class="text-xs font-semibold uppercase tracking-wider mb-1 opacity-80">Profit Stlh Pajak</p>
            <p class="text-xl font-black">Rp <?= number_format($totals['profit_stlh'], 0, ',', '.') ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="bukuKeuanganTable" class="w-full whitespace-nowrap text-xs">
                <thead>
                    <tr>
                        <!-- Group 1: Identitas -->
                        <th rowspan="2" class="px-3 py-3 bg-slate-700 text-white font-bold text-center border border-slate-600">No.</th>
                        <th rowspan="2" class="px-3 py-3 bg-slate-700 text-white font-bold text-center border border-slate-600">Tanggal</th>
                        <th rowspan="2" class="px-3 py-3 bg-slate-700 text-white font-bold text-center border border-slate-600">No. Invoice</th>
                        <th rowspan="2" class="px-3 py-3 bg-slate-700 text-white font-bold text-center border border-slate-600">Customer</th>
                        <th rowspan="2" class="px-3 py-3 bg-slate-700 text-white font-bold text-center border border-slate-600">Tujuan</th>
                        <th rowspan="2" class="px-3 py-3 bg-blue-700 text-white font-bold text-center border border-blue-600">Tagihan Invoice</th>
                        <th rowspan="2" class="px-3 py-3 bg-slate-700 text-white font-bold text-center border border-slate-600">Vendor</th>
                        <th rowspan="2" class="px-3 py-3 bg-slate-700 text-white font-bold text-center border border-slate-600">Operasional</th>

                        <!-- Group: Cadangan -->
                        <th colspan="1" class="px-3 py-2 bg-orange-600 text-white font-bold text-center border border-orange-500 text-[10px]">Cadangan Biaya OPR</th>

                        <!-- Group: PPH -->
                        <th colspan="2" class="px-3 py-2 bg-purple-700 text-white font-bold text-center border border-purple-600 text-[10px]">PPH 23%</th>

                        <!-- Group: PPN -->
                        <th colspan="2" class="px-3 py-2 bg-indigo-700 text-white font-bold text-center border border-indigo-600 text-[10px]">PPN</th>

                        <!-- Harga Pokok -->
                        <th rowspan="2" class="px-3 py-3 bg-red-700 text-white font-bold text-center border border-red-600">Harga Pokok</th>

                        <!-- Profit -->
                        <th rowspan="2" class="px-3 py-3 bg-amber-600 text-white font-bold text-center border border-amber-500">Profit Sblm Pajak</th>

                        <!-- PPh Badan -->
                        <th rowspan="2" class="px-3 py-3 bg-rose-700 text-white font-bold text-center border border-rose-600 text-[10px]">PPh Badan 22%</th>

                        <!-- Profit Stlh Pajak -->
                        <th rowspan="2" class="px-3 py-3 bg-emerald-700 text-white font-bold text-center border border-emerald-600">Profit Stlh Pajak</th>

                        <!-- Pembagian -->
                        <th colspan="4" class="px-3 py-2 bg-teal-700 text-white font-bold text-center border border-teal-600 text-[10px]">Pembagian Profit Stlh Pajak</th>

                        <!-- Aksi -->
                        <th rowspan="2" class="px-3 py-3 bg-slate-800 text-white font-bold text-center border border-slate-700">Aksi</th>
                    </tr>
                    <tr>
                        <!-- Cadangan sub -->
                        <th class="px-3 py-2 bg-orange-50 text-orange-700 font-bold text-center border border-orange-200 text-[10px]">1%</th>

                        <!-- PPH sub -->
                        <th class="px-3 py-2 bg-purple-50 text-purple-700 font-bold text-center border border-purple-200 text-[10px]">2% (Ada/Tidak)</th>
                        <th class="px-3 py-2 bg-purple-50 text-purple-700 font-bold text-center border border-purple-200 text-[10px]">Nilai PPH</th>

                        <!-- PPN sub -->
                        <th class="px-3 py-2 bg-indigo-50 text-indigo-700 font-bold text-center border border-indigo-200 text-[10px]">1.1% (Ada/Tidak)</th>
                        <th class="px-3 py-2 bg-indigo-50 text-indigo-700 font-bold text-center border border-indigo-200 text-[10px]">Nilai PPN</th>

                        <!-- Pembagian sub -->
                        <th class="px-3 py-2 bg-teal-50 text-teal-700 font-bold text-center border border-teal-200 text-[10px]">Komisaris 50%</th>
                        <th class="px-3 py-2 bg-teal-50 text-teal-700 font-bold text-center border border-teal-200 text-[10px]">Direktur 20%</th>
                        <th class="px-3 py-2 bg-teal-50 text-teal-700 font-bold text-center border border-teal-200 text-[10px]">Marketing 20%</th>
                        <th class="px-3 py-2 bg-teal-50 text-teal-700 font-bold text-center border border-teal-200 text-[10px]">Profit 10%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="24" class="text-center py-16 text-slate-400">
                                <i class="bi bi-inbox text-4xl block mb-3"></i>
                                Belum ada data. Klik "Tambah Entri" untuk mulai mencatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($rows as $i => $r): ?>
                    <?php $pst = $r['profit_stlh']; ?>
                    <tr class="hover:bg-slate-50/80 transition text-center">
                        <td class="px-3 py-2.5 text-slate-500 font-mono"><?= $i + 1 ?></td>
                        <td class="px-3 py-2.5 font-mono text-slate-500"><?= date('d/m/Y', strtotime($r['tanggal'])) ?></td>
                        <td class="px-3 py-2.5 font-bold text-primary"><?= htmlspecialchars($r['no_invoice']) ?></td>
                        <td class="px-3 py-2.5 text-left font-medium"><?= htmlspecialchars($r['customer']) ?></td>
                        <td class="px-3 py-2.5 text-left"><?= htmlspecialchars($r['tujuan']) ?></td>
                        <td class="px-3 py-2.5 font-bold text-blue-700 bg-blue-50">Rp <?= number_format($r['tagihan_invoice'], 0, ',', '.') ?></td>
                        <td class="px-3 py-2.5">Rp <?= number_format($r['vendor'], 0, ',', '.') ?></td>
                        <td class="px-3 py-2.5">Rp <?= number_format($r['operasional'], 0, ',', '.') ?></td>

                        <!-- Cadangan -->
                        <td class="px-3 py-2.5 bg-orange-50 text-orange-700 font-semibold">Rp <?= number_format($r['cadangan'], 0, ',', '.') ?></td>

                        <!-- PPH -->
                        <td class="px-3 py-2.5 bg-purple-50">
                            <?php if ($r['is_pph']): ?>
                                <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-[10px] font-bold">Ada</span>
                            <?php else: ?>
                                <span class="bg-slate-100 text-slate-400 px-2 py-0.5 rounded text-[10px]">Tidak</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-2.5 bg-purple-50 text-purple-700 font-semibold">Rp <?= number_format($r['pph_val'], 0, ',', '.') ?></td>

                        <!-- PPN -->
                        <td class="px-3 py-2.5 bg-indigo-50">
                            <?php if ($r['is_ppn']): ?>
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold">Ada</span>
                            <?php else: ?>
                                <span class="bg-slate-100 text-slate-400 px-2 py-0.5 rounded text-[10px]">Tidak</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-2.5 bg-indigo-50 text-indigo-700 font-semibold">Rp <?= number_format($r['ppn_val'], 0, ',', '.') ?></td>

                        <!-- Harga Pokok -->
                        <td class="px-3 py-2.5 bg-red-50 text-red-700 font-bold">Rp <?= number_format($r['harga_pokok'], 0, ',', '.') ?></td>

                        <!-- Profit Sblm -->
                        <td class="px-3 py-2.5 bg-amber-50 text-amber-700 font-bold">Rp <?= number_format($r['profit_sblm'], 0, ',', '.') ?></td>

                        <!-- PPh Badan -->
                        <td class="px-3 py-2.5 bg-rose-50 text-rose-700">Rp <?= number_format($r['pph_badan'], 0, ',', '.') ?></td>

                        <!-- Profit Stlh Pajak -->
                        <td class="px-3 py-2.5 bg-emerald-50 text-emerald-700 font-black">Rp <?= number_format($r['profit_stlh'], 0, ',', '.') ?></td>

                        <!-- Pembagian -->
                        <td class="px-3 py-2.5 bg-teal-50 text-teal-700">Rp <?= number_format($r['komisaris'], 0, ',', '.') ?></td>
                        <td class="px-3 py-2.5 bg-teal-50 text-teal-700">Rp <?= number_format($r['direktur'], 0, ',', '.') ?></td>
                        <td class="px-3 py-2.5 bg-teal-50 text-teal-700">Rp <?= number_format($r['marketing'], 0, ',', '.') ?></td>
                        <td class="px-3 py-2.5 bg-teal-50 text-teal-700 font-bold">Rp <?= number_format($r['profit'], 0, ',', '.') ?></td>

                        <!-- Aksi -->
                        <td class="px-3 py-2.5">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick='openEdit(<?= json_encode($r) ?>)'
                                        class="bg-amber-500 hover:bg-amber-600 text-white p-1.5 rounded-lg transition text-xs" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="<?= BASE_URL ?>/buku-keuangan/delete/<?= $r['id'] ?>" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-lg transition text-xs" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php if (!empty($rows)): ?>
                <tfoot>
                    <tr class="bg-slate-100 font-bold text-center text-xs">
                        <td colspan="5" class="px-3 py-3 text-left text-slate-700">TOTAL</td>
                        <td class="px-3 py-3 text-blue-800">Rp <?= number_format($totals['tagihan_invoice'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3">Rp <?= number_format($totals['vendor'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3">Rp <?= number_format($totals['operasional'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-orange-700">Rp <?= number_format($totals['cadangan'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3"></td>
                        <td class="px-3 py-3 text-purple-700">Rp <?= number_format($totals['pph_val'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3"></td>
                        <td class="px-3 py-3 text-indigo-700">Rp <?= number_format($totals['ppn_val'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-red-700">Rp <?= number_format($totals['harga_pokok'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-amber-700">Rp <?= number_format($totals['profit_sblm'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-rose-700">Rp <?= number_format($totals['pph_badan'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-emerald-800">Rp <?= number_format($totals['profit_stlh'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-teal-700">Rp <?= number_format($totals['komisaris'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-teal-700">Rp <?= number_format($totals['direktur'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-teal-700">Rp <?= number_format($totals['marketing'], 0, ',', '.') ?></td>
                        <td class="px-3 py-3 text-teal-800">Rp <?= number_format($totals['profit'], 0, ',', '.') ?></td>
                        <td></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<!-- ===================== MODAL TAMBAH ===================== -->
<div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-800"><i class="bi bi-plus-circle-fill text-primary mr-2"></i>Tambah Entri Baru</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-2xl leading-none">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/buku-keuangan" method="POST" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Invoice <span class="text-red-500">*</span></label>
                <input type="text" name="no_invoice" required placeholder="INV-XXXX" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Customer <span class="text-red-500">*</span></label>
                <input type="text" name="customer" required placeholder="Nama customer..." class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tujuan</label>
                <input type="text" name="tujuan" placeholder="Kota tujuan..." class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tagihan Invoice (Rp) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="tagihan_invoice" required placeholder="0" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Vendor (Rp)</label>
                <input type="number" step="0.01" name="vendor" placeholder="0" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Operasional (Rp)</label>
                <input type="number" step="0.01" name="operasional" placeholder="0" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div class="flex flex-col justify-end gap-3">
                <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl">
                    <label class="text-sm font-semibold text-slate-700 flex-1">PPH 23% (2%)</label>
                    <select name="is_pph" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="0">Tidak Ada</option>
                        <option value="1">Ada</option>
                    </select>
                </div>
                <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl">
                    <label class="text-sm font-semibold text-slate-700 flex-1">PPN (1.1%)</label>
                    <select name="is_ppn" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="0">Tidak Ada</option>
                        <option value="1">Ada</option>
                    </select>
                </div>
            </div>

            <div class="md:col-span-2 bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-700">
                <i class="bi bi-info-circle-fill mr-1"></i>
                Semua kolom kalkulasi (Harga Pokok, Profit, Pembagian, dll.) akan dihitung otomatis oleh sistem berdasarkan nilai di atas.
            </div>
            <div class="md:col-span-2 flex justify-end gap-3 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-semibold shadow-sm hover:bg-primaryHover transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL EDIT ===================== -->
<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-800"><i class="bi bi-pencil-fill text-amber-500 mr-2"></i>Edit Entri</h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-2xl leading-none">&times;</button>
        </div>
        <form id="formEdit" action="" method="POST" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            <input type="hidden" name="_editId" id="edit_id">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" id="edit_tanggal" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Invoice</label>
                <input type="text" name="no_invoice" id="edit_no_invoice" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Customer</label>
                <input type="text" name="customer" id="edit_customer" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tujuan</label>
                <input type="text" name="tujuan" id="edit_tujuan" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tagihan Invoice (Rp)</label>
                <input type="number" step="0.01" name="tagihan_invoice" id="edit_tagihan_invoice" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Vendor (Rp)</label>
                <input type="number" step="0.01" name="vendor" id="edit_vendor" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Operasional (Rp)</label>
                <input type="number" step="0.01" name="operasional" id="edit_operasional" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div class="flex flex-col justify-end gap-3">
                <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl">
                    <label class="text-sm font-semibold text-slate-700 flex-1">PPH 23% (2%)</label>
                    <select name="is_pph" id="edit_is_pph" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="0">Tidak Ada</option>
                        <option value="1">Ada</option>
                    </select>
                </div>
                <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl">
                    <label class="text-sm font-semibold text-slate-700 flex-1">PPN (1.1%)</label>
                    <select name="is_ppn" id="edit_is_ppn" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="0">Tidak Ada</option>
                        <option value="1">Ada</option>
                    </select>
                </div>
            </div>
            <div class="md:col-span-2 flex justify-end gap-3 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 text-white font-semibold shadow-sm hover:bg-amber-600 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php App\Helpers\View::endSection(); ?>

<?php App\Helpers\View::section('scripts'); ?>
<script>
function openEdit(row) {
    document.getElementById('edit_id').value = row.id;
    document.getElementById('edit_tanggal').value = row.tanggal;
    document.getElementById('edit_no_invoice').value = row.no_invoice;
    document.getElementById('edit_customer').value = row.customer;
    document.getElementById('edit_tujuan').value = row.tujuan;
    document.getElementById('edit_tagihan_invoice').value = row.tagihan_invoice;
    document.getElementById('edit_vendor').value = row.vendor;
    document.getElementById('edit_operasional').value = row.operasional;
    document.getElementById('edit_is_pph').value = row.is_pph ? '1' : '0';
    document.getElementById('edit_is_ppn').value = row.is_ppn ? '1' : '0';
    document.getElementById('formEdit').action = '<?= BASE_URL ?>/buku-keuangan/update/' + row.id;
    document.getElementById('modalEdit').classList.remove('hidden');
}

$(document).ready(function() {
    $('#bukuKeuanganTable').DataTable({
        "scrollX": true,
        "pageLength": 25,
        "order": [[1, "desc"]],
        "language": { "search": "", "searchPlaceholder": "Cari data..." },
        "dom": '<"flex justify-between items-center py-3 px-4 border-b"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center p-4"ip>',
    });
});
</script>
<?php App\Helpers\View::endSection(); ?>
