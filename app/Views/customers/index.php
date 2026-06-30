<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="customerManager()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Master Klien B2B</h2>
            <p class="text-slate-500 mt-1">Kelola pelanggan korporat beserta akun portalnya</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/customers/export" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-excel mr-2"></i> Export .xlsx
            </a>
            <a href="<?= BASE_URL ?>/customers/template" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-down mr-2"></i> Template
            </a>
            <button @click="importModal = true" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-up mr-2"></i> Import
            </button>
            <button @click="addModal = true" class="bg-primary hover:bg-secondary text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Klien
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-center shadow-sm">
            <i class="bi bi-check-circle-fill mr-3"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto p-2">
            <table id="customersTable" class="w-full whitespace-nowrap text-sm">
                <thead class="bg-slate-50/50 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-xl">Nama Perusahaan</th>
                        <th class="px-6 py-4">PIC / Kontak</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Limit Kredit</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($customers as $c): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800"><?= htmlspecialchars($c['company_name']) ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium"><?= htmlspecialchars($c['pic_name']) ?></div>
                                <div class="text-xs text-slate-400"><?= htmlspecialchars($c['phone']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($c['email'] ?? '-') ?></td>
                            <td class="px-6 py-4 font-bold text-indigo-600">Rp <?= number_format($c['credit_limit'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold <?= $c['status'] == 'ACTIVE' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' ?>">
                                    <?= $c['status'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-end">
                                <button @click="deleteItem(<?= $c['id'] ?>)" class="text-red-500 hover:bg-red-50 p-2 rounded transition">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Client Modal -->
    <div x-show="addModal" style="display: none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div @click.away="addModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold">Tambah Klien Korporat</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="<?= BASE_URL ?>/customers" method="POST">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1">Nama Perusahaan</label>
                        <input type="text" name="company_name" required class="w-full border border-slate-200 px-4 py-2 rounded-xl bg-slate-50" placeholder="PT / CV ...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama PIC</label>
                        <input type="text" name="pic_name" required class="w-full border border-slate-200 px-4 py-2 rounded-xl bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">No. HP</label>
                        <input type="text" name="phone" required class="w-full border border-slate-200 px-4 py-2 rounded-xl bg-slate-50">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" class="w-full border border-slate-200 px-4 py-2 rounded-xl bg-slate-50">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1">Alamat</label>
                        <textarea name="address" rows="2" class="w-full border border-slate-200 px-4 py-2 rounded-xl bg-slate-50"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1">Limit Kredit (Rp)</label>
                        <input type="number" name="credit_limit" value="0" class="w-full border border-slate-200 px-4 py-2 rounded-xl bg-slate-50">
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border rounded-xl">Batal</button>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteCustomerForm" method="POST" class="hidden"></form>

<?php
    $moduleName = 'Klien B2B';
    $importPreviewUrl = BASE_URL . '/customers/import-preview';
    $importProcessUrl = BASE_URL . '/customers/import-process';
    $templateUrl = BASE_URL . '/customers/template';
    include __DIR__ . '/../partials/import_modal.php';
?>

<!-- Preview Modal -->
<div x-show="previewModal" style="display:none" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
    <div x-show="previewModal" x-transition class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl mx-4 flex flex-col" style="max-height:90vh">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Preview Import Klien B2B</h3>
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
                        <th class="px-3 py-3 text-left">Nama Perusahaan</th>
                        <th class="px-3 py-3 text-left">PIC</th>
                        <th class="px-3 py-3 text-left">Kontak</th>
                        <th class="px-3 py-3 text-right">Limit Kredit</th>
                        <th class="px-3 py-3 text-center rounded-tr-lg">Alamat</th>
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
                            <td class="px-3 py-2.5 font-bold text-slate-800" x-text="row.company_name"></td>
                            <td class="px-3 py-2.5 text-slate-700" x-text="row.pic_name"></td>
                            <td class="px-3 py-2.5 text-slate-600"><div x-text="row.email"></div><div x-text="row.phone"></div></td>
                            <td class="px-3 py-2.5 text-right font-mono text-indigo-600 font-semibold" x-text="'Rp ' + Number(row.credit_limit || 0).toLocaleString('id-ID')"></td>
                            <td class="px-3 py-2.5 text-slate-500 max-w-[200px] truncate" x-text="row.address"></td>
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
                <button @click="processImport('<?= BASE_URL ?>/customers/import-process', 'klien')"
                        :disabled="previewRows.filter(r=>r._valid).length===0 || processLoading"
                        class="px-6 py-2.5 rounded-xl text-white bg-primary hover:bg-secondary text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <svg x-show="processLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <i x-show="!processLoading" class="bi bi-cloud-upload mr-2"></i>
                    <span x-text="processLoading ? 'Menyimpan...' : 'Proses Import (' + previewRows.filter(r=>r._valid).length + ' Klien)'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

</div>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script src="<?= BASE_URL ?>/js/import-mixin.js"></script>
<script>
    function customerManager() {
        return {
            ...importMixin(),
            addModal: false,
            deleteItem(id) {
                Swal.fire({ title: 'Hapus Klien?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus' })
                    .then(r => {
                        if (r.isConfirmed) {
                            const f = document.getElementById('deleteCustomerForm');
                            f.action = `<?= BASE_URL ?>/customers/delete/${id}`;
                            f.submit();
                        }
                    });
            }
        }
    }

    $(document).ready(function() {
        $('#customersTable').DataTable({
            "pageLength": 10,
            "language": { 
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
                "search": "", 
                "searchPlaceholder": "Cari klien..." 
            },
            "dom": '<"flex justify-between items-center py-3 border-b"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center p-4"ip>'
        });
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
