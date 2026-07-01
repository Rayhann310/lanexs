<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="packageManager()" x-init="init()">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Paket</h2>
            <p class="text-slate-500 mt-1">Kelola data resi, status pengiriman, dan riwayat paket</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php if ($_SESSION['role_id'] != 2): ?>
            <!-- Export Excel -->
            <a href="<?= BASE_URL ?>/packages/export" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-excel mr-2"></i> Export
            </a>
            <!-- Download Template -->
            <a href="<?= BASE_URL ?>/packages/template" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-down mr-2"></i> Template
            </a>
            <!-- Import Excel -->
            <button @click="importModal = true" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-up mr-2"></i> Import
            </button>
            <!-- Buat Resi Masal -->
            <button type="button" @click="openMassModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-layers mr-2"></i> Buat Resi Masal
            </button>
            <!-- Buat Resi -->
            <button @click="openCreateModal()" class="bg-primary hover:bg-secondary text-white px-4 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/20 transition flex items-center text-sm">
                <i class="bi bi-plus-lg mr-2"></i> Buat Resi Baru
            </button>
            <?php endif; ?>
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

    <!-- Filters & Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <i class="bi bi-funnel text-slate-400"></i>
                <span class="text-sm font-medium text-slate-600 whitespace-nowrap">Filter Status:</span>
                <select id="statusFilter" class="w-full md:w-48 px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
                    <option value="">Semua Status</option>
                    <option value="PENDING">PENDING</option>
                    <option value="PICKUP">PICKUP</option>
                    <option value="GUDANG_ASAL">GUDANG ASAL</option>
                    <option value="TRANSIT">TRANSIT</option>
                    <option value="GUDANG_TUJUAN">GUDANG TUJUAN</option>
                    <option value="DELIVERY">DELIVERY</option>
                    <option value="SELESAI">SELESAI</option>
                    <option value="RETUR">RETUR</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto p-2">
            <table id="packagesTable" class="w-full whitespace-nowrap">
                <thead class="bg-slate-50/50 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-xl">No. Resi</th>
                        <th class="px-6 py-4">Pengirim</th>
                        <th class="px-6 py-4">Penerima</th>
                        <th class="px-6 py-4">Rute</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <!-- Data will be loaded by DataTables AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Create / Edit Package -->
    <div x-show="packageModal" 
         style="display: none;"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center overflow-y-auto pt-10 pb-10"
         x-transition.opacity>
        <div @click.away="packageModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 relative my-auto"
             x-show="packageModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800" x-text="modalTitle"></h3>
                <button @click="packageModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form :action="formAction" method="POST" class="p-6">
                <input type="hidden" name="id" x-model="formData.id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Data Pengirim -->
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                            <h4 class="font-bold text-slate-700 flex items-center">
                                <i class="bi bi-person-up mr-2 text-primary"></i> Data Pengirim
                            </h4>
                            <?php if ($_SESSION['role_id'] != 5): ?>
                            <!-- B2B Auto-fill -->
                            <select x-model="formData.customer_id" class="text-xs border border-slate-200 rounded-lg px-2 py-1 bg-slate-50 max-w-[150px]">
                                <option value="">-- Pilih Klien B2B --</option>
                                <?php foreach($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['company_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Pengirim</label>
                                <input type="text" name="sender_name" x-model="formData.sender_name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">No. WhatsApp / HP</label>
                                <input type="text" name="sender_phone" x-model="formData.sender_phone" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea name="sender_address" x-model="formData.sender_address" rows="3" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Penerima -->
                    <div>
                        <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center">
                            <i class="bi bi-person-down mr-2 text-emerald-500"></i> Data Penerima
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Penerima</label>
                                <input type="text" name="receiver_name" x-model="formData.receiver_name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">No. WhatsApp / HP</label>
                                <input type="text" name="receiver_phone" x-model="formData.receiver_phone" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea name="receiver_address" x-model="formData.receiver_address" rows="3" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center">
                        <i class="bi bi-box-seam mr-2 text-orange-500"></i> Detail Pengiriman & Pembayaran
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Asal</label>
                            <select name="origin_branch_id" x-model="formData.origin_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                <option value="">-- Pilih Cabang Asal --</option>
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['code']) ?> - <?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Tujuan</label>
                            <select name="destination_branch_id" x-model="formData.destination_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                <option value="">-- Pilih Cabang Tujuan --</option>
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['code']) ?> - <?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Added Payment Info -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Metode Pembayaran</label>
                            <select name="payment_type" x-model="formData.payment_type" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                <option value="CASH">CASH (Tunai)</option>
                                <option value="TRANSFER">TRANSFER (Bank/E-Wallet)</option>
                                <option value="INVOICE">INVOICE (Tagihan B2B)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status Pembayaran</label>
                            <select name="payment_status" x-model="formData.payment_status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                <option value="PAID">LUNAS (Paid)</option>
                                <option value="UNPAID">BELUM LUNAS (Unpaid)</option>
                                <option value="COD">COD (Bayar di Tujuan)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Berat (Kg)</label>
                            <input type="number" step="0.1" name="weight" x-model="formData.weight" min="0.1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Barang</label>
                            <input type="text" name="item_type" x-model="formData.item_type" placeholder="Umum, Elektronik, dll" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                        
                        <div class="md:col-span-2 grid grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Koli</label>
                                <input type="number" name="koli" x-model="formData.koli" min="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Panjang (cm)</label>
                                <input type="number" step="0.1" name="length" x-model="formData.length" min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Lebar (cm)</label>
                                <input type="number" step="0.1" name="width" x-model="formData.width" min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tinggi (cm)</label>
                                <input type="number" step="0.1" name="height" x-model="formData.height" min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Harga / Biaya (Rp)</label>
                            <input type="number" name="price" x-model="formData.price" readonly required class="w-full px-4 py-2 bg-slate-100 text-slate-500 font-bold border border-slate-200 rounded-xl outline-none cursor-not-allowed">
                            <p class="text-[10px] text-slate-400 mt-1" x-show="tariffInfo" x-text="tariffInfo"></p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" @click="packageModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" x-text="submitLabel" class="bg-primary hover:bg-secondary text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update Status -->
    <div x-show="statusModal" 
         style="display: none;"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center"
         x-transition.opacity>
        <div @click.away="statusModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4"
             x-show="statusModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Update Status Paket</h3>
                <button @click="statusModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form :action="statusFormAction" method="POST" class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-slate-500 mb-1">Nomor Resi</p>
                    <p class="text-lg font-bold text-primary" x-text="currentResi"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Baru</label>
                    <select name="status" x-model="statusData.status" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        <option value="PENDING">PENDING</option>
                        <option value="PICKUP">PICKUP (Dijemput Kurir)</option>
                        <option value="GUDANG_ASAL">GUDANG ASAL (Di Gudang Asal)</option>
                        <option value="TRANSIT">TRANSIT (Sedang Transit)</option>
                        <option value="GUDANG_TUJUAN">GUDANG TUJUAN (Di Gudang Tujuan)</option>
                        <option value="DELIVERY">DELIVERY (Dalam Pengiriman Kurir)</option>
                        <option value="SELESAI">SELESAI (Telah Diterima)</option>
                        <option value="RETUR">RETUR (Dikembalikan)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Cabang (Opsional)</label>
                    <select name="branch_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        <option value="">-- Tidak Terkait Cabang --</option>
                        <?php foreach($branches as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi / Keterangan</label>
                    <textarea name="description" rows="3" required placeholder="Contoh: Paket telah tiba di HUB Jakarta" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="statusModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center">
                        <i class="bi bi-save mr-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php
    $moduleName = 'Paket';
    $importPreviewUrl = BASE_URL . '/packages/import-preview';
    $importProcessUrl = BASE_URL . '/packages/import-process';
    $templateUrl = BASE_URL . '/packages/template';
    include __DIR__ . '/../partials/import_modal.php';
?>

<!-- ========== PREVIEW MODAL ========== -->
<div x-show="previewModal" style="display:none" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
    <div x-show="previewModal" x-transition class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl mx-4 flex flex-col" style="max-height: 90vh;">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Preview Data Import Paket</h3>
                <p class="text-sm text-slate-500 mt-0.5">
                    <span class="text-emerald-600 font-semibold" x-text="previewRows.filter(r => r._valid).length"></span> data valid,
                    <span class="text-red-500 font-semibold" x-text="previewRows.filter(r => !r._valid).length"></span> data bermasalah
                    (dari total <span class="font-semibold" x-text="previewRows.length"></span> baris)
                </p>
            </div>
            <button @click="previewModal = false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg text-xl"></i></button>
        </div>

        <!-- Scrollable Table -->
        <div class="overflow-auto flex-1 p-4">
            <table class="w-full text-xs whitespace-nowrap border-collapse">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-slate-800 text-white">
                        <th class="px-3 py-3 text-left rounded-tl-lg">#</th>
                        <th class="px-3 py-3 text-left">Status</th>
                        <th class="px-3 py-3 text-left">Pengirim</th>
                        <th class="px-3 py-3 text-left">Telp Pengirim</th>
                        <th class="px-3 py-3 text-left">Penerima</th>
                        <th class="px-3 py-3 text-left">Telp Penerima</th>
                        <th class="px-3 py-3 text-left">Alamat Penerima</th>
                        <th class="px-3 py-3 text-left">Cabang Asal</th>
                        <th class="px-3 py-3 text-left">Cabang Tujuan</th>
                        <th class="px-3 py-3 text-right">Berat</th>
                        <th class="px-3 py-3 text-right">Harga</th>
                        <th class="px-3 py-3 text-left">Pembayaran</th>
                        <th class="px-3 py-3 text-left rounded-tr-lg">Status Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, idx) in previewRows" :key="idx">
                        <tr :class="row._valid ? 'hover:bg-slate-50' : 'bg-red-50 hover:bg-red-100'">
                            <td class="px-3 py-2.5 font-mono text-slate-400" x-text="idx + 1"></td>
                            <td class="px-3 py-2.5">
                                <span x-show="row._valid" class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold text-[10px]">✓ VALID</span>
                                <span x-show="!row._valid" class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold text-[10px]">✗ ERROR</span>
                            </td>
                            <td class="px-3 py-2.5 font-medium text-slate-800" x-text="row.sender_name"></td>
                            <td class="px-3 py-2.5 font-mono text-slate-500" x-text="row.sender_phone"></td>
                            <td class="px-3 py-2.5 font-medium text-slate-800" x-text="row.receiver_name"></td>
                            <td class="px-3 py-2.5 font-mono text-slate-500" x-text="row.receiver_phone"></td>
                            <td class="px-3 py-2.5 text-slate-600 max-w-xs truncate" x-text="row.receiver_address"></td>
                            <td class="px-3 py-2.5" :class="row._valid ? 'text-slate-700' : 'text-red-600 font-bold'" x-text="row.origin_branch_name"></td>
                            <td class="px-3 py-2.5" :class="row._valid ? 'text-slate-700' : 'text-red-600 font-bold'" x-text="row.dest_branch_name"></td>
                            <td class="px-3 py-2.5 text-right font-mono" x-text="row.weight + ' kg'"></td>
                            <td class="px-3 py-2.5 text-right font-mono font-semibold text-slate-800" x-text="'Rp ' + Number(row.price).toLocaleString('id-ID')"></td>
                            <td class="px-3 py-2.5">
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded font-bold text-[10px]" x-text="row.payment_type"></span>
                            </td>
                            <td class="px-3 py-2.5">
                                <span :class="row.payment_status === 'PAID' ? 'bg-emerald-100 text-emerald-700' : row.payment_status === 'COD' ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-600'"
                                      class="px-2 py-0.5 rounded font-bold text-[10px]" x-text="row.payment_status"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-100 shrink-0 flex justify-between items-center bg-slate-50 rounded-b-2xl">
            <div class="text-sm text-slate-500">
                <i class="bi bi-info-circle mr-1"></i>
                Hanya baris <span class="font-semibold text-emerald-600">VALID</span> yang akan diproses. Baris bermasalah akan dilewati.
            </div>
            <div class="flex space-x-3">
                <button @click="previewModal = false; importModal = true" class="px-4 py-2.5 rounded-xl text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 text-sm font-medium">
                    <i class="bi bi-arrow-left mr-1"></i> Kembali
                </button>
                <button @click="processImport('<?= BASE_URL ?>/packages/import-process', 'paket')"
                        :disabled="previewRows.filter(r => r._valid).length === 0 || processLoading"
                        class="px-6 py-2.5 rounded-xl text-white bg-primary hover:bg-secondary text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <svg x-show="processLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <i x-show="!processLoading" class="bi bi-cloud-upload mr-2"></i>
                    <span x-text="processLoading ? 'Menyimpan...' : 'Proses Import (' + previewRows.filter(r => r._valid).length + ' Paket)'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Buat Resi Masal -->
    <div x-show="massModal" 
         style="display: none;"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center overflow-y-auto pt-10 pb-10"
         x-transition.opacity>
        <div @click.away="massModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl mx-4 relative my-auto">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Buat Resi Masal</h3>
                <button @click="massModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <form :action="'<?= BASE_URL ?>/packages/mass'" method="POST" id="massForm">
                    <input type="hidden" name="payload" :value="JSON.stringify(massPackages)">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700">
                                <th class="p-2 border border-slate-200">#</th>
                                <th class="p-2 border border-slate-200 w-48">Pengirim</th>
                                <th class="p-2 border border-slate-200 w-48">Penerima</th>
                                <th class="p-2 border border-slate-200">Rute</th>
                                <th class="p-2 border border-slate-200">Info Barang</th>
                                <th class="p-2 border border-slate-200">Biaya</th>
                                <th class="p-2 border border-slate-200 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(pkg, idx) in massPackages" :key="idx">
                                <tr class="hover:bg-slate-50">
                                    <td class="p-2 border border-slate-200 text-center" x-text="idx + 1"></td>
                                    
                                    <td class="p-2 border border-slate-200 space-y-2">
                                        <input type="text" x-model="pkg.sender_name" placeholder="Nama" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        <input type="text" x-model="pkg.sender_phone" placeholder="No Telp" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        <textarea x-model="pkg.sender_address" placeholder="Alamat" rows="2" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                                    </td>
                                    
                                    <td class="p-2 border border-slate-200 space-y-2">
                                        <input type="text" x-model="pkg.receiver_name" placeholder="Nama" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        <input type="text" x-model="pkg.receiver_phone" placeholder="No Telp" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        <textarea x-model="pkg.receiver_address" placeholder="Alamat" rows="2" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                                    </td>
                                    
                                    <td class="p-2 border border-slate-200 space-y-2">
                                        <select x-model="pkg.origin_branch_id" @change="calculateMassPrice(idx)" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                            <option value="">-- Asal --</option>
                                            <?php foreach($branches as $b): ?>
                                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <select x-model="pkg.destination_branch_id" @change="calculateMassPrice(idx)" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                            <option value="">-- Tujuan --</option>
                                            <?php foreach($branches as $b): ?>
                                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    
                                    <td class="p-2 border border-slate-200 space-y-2">
                                        <div class="flex space-x-2">
                                            <input type="text" x-model="pkg.item_type" placeholder="Jenis" class="w-1/2 text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                            <input type="number" x-model="pkg.koli" placeholder="Koli" @change="calculateMassPrice(idx)" class="w-1/2 text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        </div>
                                        <div class="flex space-x-2">
                                            <input type="number" step="0.1" x-model="pkg.weight" placeholder="Kg" @change="calculateMassPrice(idx)" class="w-1/2 text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                            <input type="number" step="0.1" x-model="pkg.length" placeholder="P(cm)" @change="calculateMassPrice(idx)" class="w-1/2 text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        </div>
                                        <div class="flex space-x-2">
                                            <input type="number" step="0.1" x-model="pkg.width" placeholder="L(cm)" @change="calculateMassPrice(idx)" class="w-1/2 text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                            <input type="number" step="0.1" x-model="pkg.height" placeholder="T(cm)" @change="calculateMassPrice(idx)" class="w-1/2 text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        </div>
                                    </td>
                                    
                                    <td class="p-2 border border-slate-200 space-y-2">
                                        <select x-model="pkg.payment_type" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                            <option value="CASH">CASH</option>
                                            <option value="TRANSFER">TRANSFER</option>
                                            <option value="INVOICE">INVOICE</option>
                                        </select>
                                        <select x-model="pkg.payment_status" class="w-full text-xs px-2 py-1 border border-slate-300 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                            <option value="PAID">PAID</option>
                                            <option value="UNPAID">UNPAID</option>
                                            <option value="COD">COD</option>
                                        </select>
                                        <div class="text-right font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded" x-text="'Rp ' + Number(pkg.price||0).toLocaleString('id-ID')"></div>
                                    </td>
                                    
                                    <td class="p-2 border border-slate-200 text-center align-middle">
                                        <button type="button" @click="removeMassPackage(idx)" class="text-red-500 hover:text-red-700 p-2 rounded hover:bg-red-50 transition" title="Hapus Baris">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <div class="mt-4 flex justify-between items-center">
                        <button type="button" @click="addMassPackage()" class="text-primary hover:text-secondary font-medium text-sm flex items-center bg-primary/10 px-4 py-2 rounded-xl transition">
                            <i class="bi bi-plus-lg mr-2"></i> Tambah Baris Paket
                        </button>
                        
                        <div class="flex space-x-3">
                            <button type="button" @click="massModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                            <button type="button" @click="document.getElementById('massForm').submit()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center">
                                <i class="bi bi-save mr-2"></i> Simpan Semua Paket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Hidden Form for SweetAlert Delete -->
<form id="deleteForm" method="POST" class="hidden"></form>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
    <script src="<?= BASE_URL ?>/js/import-mixin.js"></script>
<script>
    // Alpine.js component for Package Manager
    function packageManager() {
        return {
            ...importMixin(),
            packageModal: false,
            statusModal: false,
            modalTitle: 'Buat Resi Baru',
            formAction: '<?= BASE_URL ?>/packages',
            submitLabel: 'Simpan',
            statusFormAction: '',
            currentResi: '',
            tariffInfo: '',
            
            // Default form state
            defaultFormData: {
                id: '',
                customer_id: '',
                sender_name: '', sender_phone: '', sender_address: '',
                receiver_name: '', receiver_phone: '', receiver_address: '',
                origin_branch_id: '', destination_branch_id: '',
                item_type: 'UMUM', koli: '1', length: '0', width: '0', height: '0',
                weight: '1.0', price: '0',
                payment_type: 'CASH', payment_status: 'UNPAID'
            },
            formData: {},
            
            statusData: {
                status: 'PENDING'
            },
            
            massModal: false,
            massPackages: [],
            
            init() {
                window.alpinePackageManager = this;
                this.formData = { ...this.defaultFormData };
                
                // Watch for changes in pricing dependencies
                this.$watch('formData.origin_branch_id', () => this.calculatePrice());
                this.$watch('formData.destination_branch_id', () => this.calculatePrice());
                this.$watch('formData.weight', () => this.calculatePrice());
                this.$watch('formData.length', () => this.calculatePrice());
                this.$watch('formData.width', () => this.calculatePrice());
                this.$watch('formData.height', () => this.calculatePrice());
                this.$watch('formData.koli', () => this.calculatePrice());
                
                // Watch for B2B Customer auto-fill
                this.$watch('formData.customer_id', (newVal) => {
                    if (newVal) {
                        const customers = <?= json_encode($customers) ?>;
                        const customer = customers.find(c => c.id == newVal);
                        if (customer) {
                            this.formData.sender_name = customer.company_name;
                            this.formData.sender_phone = customer.phone || '';
                            this.formData.sender_address = customer.address || '';
                            // Optionally set payment type to INVOICE for B2B
                            this.formData.payment_type = 'INVOICE';
                            this.formData.payment_status = 'UNPAID';
                        }
                    } else {
                        this.formData.sender_name = '';
                        this.formData.sender_phone = '';
                        this.formData.sender_address = '';
                        this.formData.payment_type = 'CASH';
                    }
                });
            },
            
            calculatePrice() {
                if (this.formData.id !== '') return; // Don't recalculate if editing existing package
                if (!this.formData.origin_branch_id || !this.formData.destination_branch_id || !this.formData.weight) {
                    this.formData.price = '0';
                    this.tariffInfo = '';
                    return;
                }
                
                this.tariffInfo = 'Menghitung...';
                
                let volume = (this.formData.length * this.formData.width * this.formData.height) / 1000000;
                let koli = this.formData.koli || 1;
                
                fetch(`<?= BASE_URL ?>/api/tariffs/calculate?origin_branch_id=${this.formData.origin_branch_id}&destination_branch_id=${this.formData.destination_branch_id}&weight=${this.formData.weight}&volume=${volume}&koli=${koli}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            this.formData.price = data.data.total_price;
                            const details = data.data.calculated_details;
                            const method = details ? details.applied_method : data.data.type_used;
                            let methodLabel = method === 'WEIGHT' ? '🏋 Berat' : (method === 'VOLUME' ? '📦 Volume' : '🔢 Koli');
                            this.tariffInfo = `Tarif ${data.data.type_used} | Metode: ${methodLabel} | Rp ${(data.data.total_price||0).toLocaleString('id-ID')} | Est: ${data.data.estimated_days} Hari`;
                        } else {
                            this.formData.price = '0';
                            this.tariffInfo = data.message;
                        }
                    })
                    .catch(error => {
                        this.formData.price = '0';
                        this.tariffInfo = 'Gagal menghitung tarif';
                    });
            },
            
            // Mass Package Methods
            openMassModal() {
                console.log('Opening mass modal');
                try {
                    this.massPackages = [ { ...this.defaultFormData } ];
                    this.massModal = true;
                } catch (e) {
                    alert('Error opening modal: ' + e.message);
                }
            },
            addMassPackage() {
                // If there's previous package, copy origin and destination branch for convenience
                let prev = this.massPackages[this.massPackages.length - 1];
                let next = { ...this.defaultFormData };
                if (prev) {
                    next.origin_branch_id = prev.origin_branch_id;
                    next.destination_branch_id = prev.destination_branch_id;
                }
                this.massPackages.push(next);
            },
            removeMassPackage(index) {
                this.massPackages.splice(index, 1);
                if (this.massPackages.length === 0) {
                    this.addMassPackage();
                }
            },
            calculateMassPrice(index) {
                let pkg = this.massPackages[index];
                if (!pkg.origin_branch_id || !pkg.destination_branch_id || !pkg.weight) {
                    pkg.price = '0';
                    return;
                }
                
                let volume = (pkg.length * pkg.width * pkg.height) / 1000000;
                let koli = pkg.koli || 1;
                
                fetch(`<?= BASE_URL ?>/api/tariffs/calculate?origin_branch_id=${pkg.origin_branch_id}&destination_branch_id=${pkg.destination_branch_id}&weight=${pkg.weight}&volume=${volume}&koli=${koli}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            pkg.price = data.data.total_price;
                        } else {
                            pkg.price = '0';
                        }
                    })
                    .catch(error => {
                        pkg.price = '0';
                    });
            },
            
            openCreateModal() {
                this.modalTitle = 'Buat Resi Baru';
                this.formAction = '<?= BASE_URL ?>/packages';
                this.submitLabel = 'Simpan';
                this.formData = { ...this.defaultFormData };
                this.packageModal = true;
            },
            
            openEditModal(data) {
                this.modalTitle = 'Edit Data Paket';
                this.formAction = '<?= BASE_URL ?>/packages/update/' + data.id;
                this.submitLabel = 'Simpan Perubahan';
                this.formData = { ...data };
                this.packageModal = true;
            },
            
            openStatusModal(data) {
                this.statusFormAction = '<?= BASE_URL ?>/packages/update-status/' + data.id;
                this.currentResi = data.resi;
                this.statusData.status = data.status;
                this.statusModal = true;
            },
            
            deletePackage(id, resi) {
                Swal.fire({
                    title: 'Hapus Paket?',
                    html: `Anda yakin ingin menghapus paket dengan resi <b>${resi}</b>?<br>Data tidak dapat dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteForm');
                        form.action = '<?= BASE_URL ?>/packages/delete/' + id;
                        form.submit();
                    }
                });
            },

            // Removed old custom import methods, now using mixin
        }
    }

    $(document).ready(function() {
        // Tailwind styled DataTables initialization
        $('#packagesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= BASE_URL ?>/api/packages/datatable",
                "type": "GET",
                "data": function(d) {
                    d.status_filter = $('#statusFilter').val();
                }
            },
            "columns": [
                { 
                    "data": "resi", 
                    "className": "px-6 py-4 font-bold text-primary",
                    "render": function(data, type, row) {
                        return escapeHtml(data);
                    }
                },
                { 
                    "data": null, 
                    "className": "px-6 py-4",
                    "render": function(data, type, row) {
                        return `<div class="font-medium text-slate-800">${escapeHtml(row.sender_name)}</div>
                                <div class="text-sm text-slate-500 mt-0.5">${escapeHtml(row.sender_phone)}</div>`;
                    }
                },
                { 
                    "data": null, 
                    "className": "px-6 py-4",
                    "render": function(data, type, row) {
                        return `<div class="font-medium text-slate-800">${escapeHtml(row.receiver_name)}</div>
                                <div class="text-sm text-slate-500 mt-0.5">${escapeHtml(row.receiver_phone)}</div>`;
                    }
                },
                { 
                    "data": null, 
                    "className": "px-6 py-4",
                    "render": function(data, type, row) {
                        return `<div class="text-sm flex items-center space-x-2">
                                    <span class="font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">${escapeHtml(row.origin_branch_name || 'N/A')}</span>
                                    <i class="bi bi-arrow-right text-slate-400"></i>
                                    <span class="font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">${escapeHtml(row.dest_branch_name || 'N/A')}</span>
                                </div>`;
                    }
                },
                { 
                    "data": "status", 
                    "className": "px-6 py-4",
                    "render": function(data, type, row) {
                        let statusClass = 'bg-slate-100 text-slate-700';
                        if (data === 'PENDING') statusClass = 'bg-slate-100 text-slate-700';
                        else if (data === 'PICKUP') statusClass = 'bg-blue-100 text-blue-700';
                        else if (data === 'TRANSIT') statusClass = 'bg-orange-100 text-orange-700';
                        else if (data === 'DELIVERY') statusClass = 'bg-purple-100 text-purple-700';
                        else if (data === 'SELESAI') statusClass = 'bg-emerald-100 text-emerald-700';
                        else if (data === 'RETUR') statusClass = 'bg-red-100 text-red-700';
                        else statusClass = 'bg-cyan-100 text-cyan-700';
                        
                        return `<span class="px-3 py-1.5 text-xs font-bold rounded-full ${statusClass}">${escapeHtml(data)}</span>`;
                    }
                },
                { 
                    "data": null, 
                    "className": "px-6 py-4 flex items-center justify-end space-x-2",
                    "orderable": false,
                    "render": function(data, type, row, meta) {
                        const jsonRow = JSON.stringify(row).replace(/"/g, '&quot;');
                        let html = '';
                        
                        let roleId = meta.settings.json.role_id;
                        
                        // Cetak
                        html += `<a href="<?= BASE_URL ?>/packages/print/${row.id}" target="_blank" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-200 transition" title="Cetak Barcode">
                                    <i class="bi bi-printer"></i>
                                 </a>`;
                                 
                        if (roleId != 2) {
                            html += `<button onclick="window.alpinePackageManager.openEditModal(${jsonRow})" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-500/10 transition" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                     </button>`;
                        }
                        
                        
                        html += `<a href="<?= BASE_URL ?>/tracking?resi=${encodeURIComponent(row.resi)}" target="_blank" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-success hover:bg-success/10 transition" title="Tracking Live">
                                    <i class="bi bi-geo-alt"></i>
                                 </a>`;
                                 
                        html += `<button onclick="window.alpinePackageManager.openStatusModal(${jsonRow})" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-orange-500 hover:bg-orange-500/10 transition" title="Update Status & Tracking">
                                    <i class="bi bi-pin-map"></i>
                                 </button>`;
                                 
                        if (roleId != 2) {
                            html += `<button onclick="window.alpinePackageManager.deletePackage(${row.id}, '${row.resi}')" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                     </button>`;
                        }
                        
                        return html;
                    }
                }
            ],
            "pageLength": 10,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
                "search": "",
                "searchPlaceholder": "Cari resi/nama...",
                "lengthMenu": "Tampilkan _MENU_ data"
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center py-3 px-4 border-b border-slate-100"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center p-4"<"text-sm text-slate-500"i><"flex items-center space-x-1"p>>',
            "drawCallback": function() {
                $('.paginate_button').addClass('px-3 py-1 text-sm font-medium rounded-lg cursor-pointer transition');
            }
        });

        // Trigger filter reload
        $('#statusFilter').on('change', function() {
            $('#packagesTable').DataTable().ajax.reload();
        });
    });

    // Helper for HTML escaping
    function escapeHtml(text) {
        if (!text) return '';
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
    }
</script>



<?php \App\Helpers\View::endSection(); ?>
