<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="tariffManager()" x-init="init()">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Tarif</h2>
            <p class="text-slate-500 mt-1">Kelola harga pengiriman antar cabang atau antar kota</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/tariffs/export" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-excel mr-2"></i> Export .xlsx
            </a>
            <a href="<?= BASE_URL ?>/tariffs/template" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-down mr-2"></i> Template
            </a>
            <button @click="importModal = true" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-up mr-2"></i> Import
            </button>
            <button @click="openModal('CITY')" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-plus-lg mr-2"></i> Tarif Kota
            </button>
            <button @click="openModal('BRANCH')" class="bg-primary hover:bg-secondary text-white px-4 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/20 transition flex items-center text-sm">
                <i class="bi bi-plus-lg mr-2"></i> Tarif Cabang
            </button>
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

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto p-2">
            <table id="tariffsTable" class="w-full whitespace-nowrap">
                <thead class="bg-slate-50/50 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-xl">Tipe</th>
                        <th class="px-6 py-4">Asal</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Harga / Kg</th>
                        <th class="px-6 py-4">Estimasi Waktu</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($tariffs as $t): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <?php if($t['type'] === 'CITY'): ?>
                                    <span class="bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-bold">KOTA</span>
                                <?php else: ?>
                                    <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold">CABANG</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($t['origin_name']) ?></td>
                            <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($t['dest_name']) ?></td>
                            <td class="px-6 py-4 font-bold text-emerald-600">
                                Rp <?= number_format($t['price_per_kg'], 0, ',', '.') ?> <span class="text-xs font-normal text-slate-400">/ Kg</span><br>
                                Rp <?= number_format($t['price_per_koli'] ?? 0, 0, ',', '.') ?> <span class="text-xs font-normal text-slate-400">/ Koli</span><br>
                                Rp <?= number_format($t['price_per_volume'] ?? 0, 0, ',', '.') ?> <span class="text-xs font-normal text-slate-400">/ m³</span>
                            </td>
                            <td class="px-6 py-4"><?= htmlspecialchars($t['estimated_days']) ?> Hari</td>
                            <td class="px-6 py-4">
                                <?php if($t['is_active']): ?>
                                    <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg text-xs font-bold">Aktif</span>
                                <?php else: ?>
                                    <span class="bg-slate-100 text-slate-500 px-2.5 py-1 rounded-lg text-xs font-bold">Non-Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 flex items-center justify-end space-x-2">
                                <button @click='openEditModal(<?= htmlspecialchars(json_encode($t)) ?>)' class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-500/10 transition" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button @click="deleteTariff(<?= $t['id'] ?>)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="modalOpen" 
         style="display: none;"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center"
         x-transition.opacity>
        <div @click.away="modalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800" x-text="modalTitle"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form :action="formAction" method="POST" class="p-6">
                <input type="hidden" name="type" x-model="formData.type">
                
                <template x-if="formData.type === 'BRANCH'">
                    <div class="space-y-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Asal</label>
                            <select name="origin_branch_id" x-model="formData.origin_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                <option value="">-- Pilih Cabang --</option>
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Tujuan</label>
                            <select name="destination_branch_id" x-model="formData.destination_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                <option value="">-- Pilih Cabang --</option>
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </template>
                
                <template x-if="formData.type === 'CITY'">
                    <div class="space-y-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kota Asal</label>
                            <input type="text" name="origin_city" x-model="formData.origin_city" required placeholder="Contoh: Jakarta" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kota Tujuan</label>
                            <input type="text" name="destination_city" x-model="formData.destination_city" required placeholder="Contoh: Bandung" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                    </div>
                </template>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Harga per Kg (Rp)</label>
                        <input type="number" name="price_per_kg" x-model="formData.price_per_kg" min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Harga per Koli (Rp)</label>
                        <input type="number" name="price_per_koli" x-model="formData.price_per_koli" min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Harga per Volume (Rp/m³)</label>
                        <input type="number" name="price_per_volume" x-model="formData.price_per_volume" min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estimasi (Hari)</label>
                        <input type="number" name="estimated_days" x-model="formData.estimated_days" required min="1" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Aktif</label>
                    <select name="is_active" x-model="formData.is_active" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="modalOpen = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" x-text="submitLabel" class="bg-primary hover:bg-secondary text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Form for SweetAlert Delete -->
<form id="deleteForm" method="POST" class="hidden"></form>

<!-- Import Modal -->
<div x-show="importModal" style="display:none" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
    <div @click.away="importModal = false" x-show="importModal" x-transition class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Import Data Tarif</h3>
                <p class="text-sm text-slate-500 mt-0.5">Upload file Excel (.xlsx) atau CSV untuk import massal</p>
            </div>
            <button @click="importModal = false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg text-xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <p class="text-sm font-semibold text-amber-800 mb-2"><i class="bi bi-info-circle mr-1"></i> Cara Import:</p>
                <ol class="text-xs text-amber-700 space-y-1 list-decimal ml-4">
                    <li>Unduh <strong>Template Excel</strong> untuk melihat format yang benar</li>
                    <li>Lihat sheet <em>Daftar Cabang (ID)</em> untuk ID yang valid</li>
                    <li>Upload file .xlsx atau .csv di sini</li>
                    <li>Review Preview, lalu klik <strong>Proses Import</strong></li>
                </ol>
            </div>
            <label class="block border-2 border-dashed border-slate-300 hover:border-primary rounded-xl p-8 text-center cursor-pointer transition-colors group"
                   :class="importFile ? 'border-emerald-400 bg-emerald-50' : ''">
                <input type="file" accept=".xlsx,.csv,.txt" class="hidden" @change="handleFileSelect($event)">
                <i class="bi text-4xl mb-3 block transition-colors"
                   :class="importFile ? 'bi-file-earmark-check text-emerald-500' : 'bi-file-earmark-excel text-slate-400 group-hover:text-primary'"></i>
                <p class="text-sm font-medium" :class="importFile ? 'text-emerald-700' : 'text-slate-600'">
                    <span x-text="importFile ? importFile.name : 'Klik atau seret file .xlsx / .csv ke sini'"></span>
                </p>
                <p class="text-xs text-slate-400 mt-1" x-show="!importFile">Mendukung .xlsx dan .csv</p>
            </label>
            <div x-show="importErrors.length > 0" class="bg-red-50 border border-red-200 rounded-xl p-3 max-h-28 overflow-y-auto">
                <template x-for="err in importErrors"><p class="text-xs text-red-600" x-text="err"></p></template>
            </div>
        </div>
        <div class="p-6 border-t border-slate-100 flex justify-between items-center">
            <a href="<?= BASE_URL ?>/tariffs/template" class="text-sm text-primary hover:underline flex items-center font-medium">
                <i class="bi bi-file-earmark-excel mr-1"></i> Unduh Template .xlsx
            </a>
            <div class="flex space-x-3">
                <button @click="importModal = false" class="px-4 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 text-sm font-medium">Batal</button>
                <button @click="previewImport('<?= BASE_URL ?>/tariffs/import-preview')"
                        :disabled="!importFile || importLoading"
                        class="px-5 py-2 rounded-xl text-white bg-amber-500 hover:bg-amber-600 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <svg x-show="importLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="importLoading ? 'Memproses...' : 'Preview Data'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div x-show="previewModal" style="display:none" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
    <div x-show="previewModal" x-transition class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl mx-4 flex flex-col" style="max-height:90vh">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Preview Import Tarif</h3>
                <p class="text-sm text-slate-500 mt-0.5">
                    <span class="text-emerald-600 font-semibold" x-text="previewRows.filter(r=>r._valid).length"></span> valid,
                    <span class="text-red-500 font-semibold" x-text="previewRows.filter(r=>!r._valid).length"></span> bermasalah
                    dari total <span class="font-semibold" x-text="previewRows.length"></span> baris
                </p>
            </div>
            <button @click="previewModal=false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg text-xl"></i></button>
        </div>
        <div class="overflow-auto flex-1 p-4">
            <table class="w-full text-xs whitespace-nowrap border-collapse">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-slate-800 text-white">
                        <th class="px-3 py-3 text-left rounded-tl-lg">#</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3 text-left">Tipe</th>
                        <th class="px-3 py-3 text-left">Asal</th>
                        <th class="px-3 py-3 text-left">Tujuan</th>
                        <th class="px-3 py-3 text-right">Harga/Kg</th>
                        <th class="px-3 py-3 text-right">Harga/Koli</th>
                        <th class="px-3 py-3 text-right">Harga/Vol</th>
                        <th class="px-3 py-3 text-center">Est. Hari</th>
                        <th class="px-3 py-3 text-center rounded-tr-lg">Aktif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, idx) in previewRows" :key="idx">
                        <tr :class="row._valid ? 'hover:bg-slate-50' : 'bg-red-50 hover:bg-red-100'">
                            <td class="px-3 py-2.5 font-mono text-slate-400" x-text="idx+1"></td>
                            <td class="px-3 py-2.5">
                                <span x-show="row._valid" class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold text-[10px]">✓ VALID</span>
                                <span x-show="!row._valid" class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold text-[10px]">✗ ERROR</span>
                            </td>
                            <td class="px-3 py-2.5"><span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded font-bold text-[10px]" x-text="row.type"></span></td>
                            <td class="px-3 py-2.5 text-slate-700" x-text="row.origin_display"></td>
                            <td class="px-3 py-2.5 text-slate-700" x-text="row.dest_display"></td>
                            <td class="px-3 py-2.5 text-right font-mono font-semibold" x-text="'Rp ' + Number(row.price_per_kg||0).toLocaleString('id-ID')"></td>
                            <td class="px-3 py-2.5 text-right font-mono font-semibold" x-text="'Rp ' + Number(row.price_per_koli||0).toLocaleString('id-ID')"></td>
                            <td class="px-3 py-2.5 text-right font-mono font-semibold" x-text="'Rp ' + Number(row.price_per_volume||0).toLocaleString('id-ID')"></td>
                            <td class="px-3 py-2.5 text-center" x-text="row.estimated_days + ' hari'"></td>
                            <td class="px-3 py-2.5 text-center">
                                <span :class="row.is_active == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'" class="px-2 py-0.5 rounded text-[10px] font-bold" x-text="row.is_active == 1 ? 'Ya' : 'Tidak'"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100 shrink-0 flex justify-between items-center bg-slate-50 rounded-b-2xl">
            <p class="text-sm text-slate-500"><i class="bi bi-info-circle mr-1"></i> Hanya baris <span class="font-semibold text-emerald-600">VALID</span> yang akan disimpan.</p>
            <div class="flex space-x-3">
                <button @click="previewModal=false; importModal=true" class="px-4 py-2.5 rounded-xl text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 text-sm font-medium">
                    <i class="bi bi-arrow-left mr-1"></i> Kembali
                </button>
                <button @click="processImport('<?= BASE_URL ?>/tariffs/import-process', 'tarif')"
                        :disabled="previewRows.filter(r=>r._valid).length===0 || processLoading"
                        class="px-6 py-2.5 rounded-xl text-white bg-primary hover:bg-secondary text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <svg x-show="processLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <i x-show="!processLoading" class="bi bi-cloud-upload mr-2"></i>
                    <span x-text="processLoading ? 'Menyimpan...' : 'Proses Import (' + previewRows.filter(r=>r._valid).length + ' Tarif)'"></span>
                </button>
            </div>
        </div>
    </div>
</div>



<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script src="<?= BASE_URL ?>/js/import-mixin.js"></script>
<script>
    function tariffManager() {
        return {
            ...importMixin(),
            modalOpen: false,
            modalTitle: '',
            formAction: '',
            submitLabel: 'Simpan',
            defaultFormData: {
                type: 'CITY',
                origin_branch_id: '',
                destination_branch_id: '',
                origin_city: '',
                destination_city: '',
                price_per_kg: '0',
                price_per_koli: '0',
                price_per_volume: '0',
                estimated_days: '1',
                is_active: '1'
            },
            formData: {},
            
            init() {
                this.formData = { ...this.defaultFormData };
            },
            
            openModal(type) {
                this.modalTitle = type === 'CITY' ? 'Tambah Tarif Kota' : 'Tambah Tarif Cabang';
                this.formAction = '<?= BASE_URL ?>/tariffs';
                this.submitLabel = 'Simpan';
                this.formData = { ...this.defaultFormData, type: type };
                this.modalOpen = true;
            },
            
            openEditModal(data) {
                this.modalTitle = data.type === 'CITY' ? 'Edit Tarif Kota' : 'Edit Tarif Cabang';
                this.formAction = '<?= BASE_URL ?>/tariffs/update/' + data.id;
                this.submitLabel = 'Simpan Perubahan';
                this.formData = { ...data };
                this.modalOpen = true;
            },
            
            deleteTariff(id) {
                Swal.fire({
                    title: 'Hapus Tarif?',
                    html: `Anda yakin ingin menghapus data tarif ini?`,
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
                        form.action = '<?= BASE_URL ?>/tariffs/delete/' + id;
                        form.submit();
                    }
                });
            }
        }
    }

    $(document).ready(function() {
        $('#tariffsTable').DataTable({
            "pageLength": 10,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
                "search": "",
                "searchPlaceholder": "Cari data...",
                "lengthMenu": "Tampilkan _MENU_ data"
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center py-3 px-4 border-b border-slate-100"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center p-4"<"text-sm text-slate-500"i><"flex items-center space-x-1"p>>',
            "drawCallback": function() {
                $('.paginate_button').addClass('px-3 py-1 text-sm font-medium rounded-lg cursor-pointer transition');
            }
        });
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
