<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Master Data: Cabang</h2>
            <p class="text-slate-500 mt-1">Daftar kantor cabang & titik hub logistik</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openImportModal()" class="bg-white border border-slate-200 hover:border-emerald-500 hover:text-emerald-600 text-slate-600 px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center">
                <i class="bi bi-file-earmark-excel mr-2 text-emerald-500"></i> Import
            </button>
            <a href="<?= BASE_URL ?>/branches/export" class="bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-600 px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center">
                <i class="bi bi-download mr-2"></i> Export
            </a>
            <button onclick="openBranchModal()" class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Cabang
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center text-xl">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Cabang</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= number_format($totalBranches ?? 0) ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="bi bi-diagram-2"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Pusat Sortir (HUB)</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= number_format($totalHubs ?? 0) ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Agen Mitra</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= number_format($totalAgents ?? 0) ?></h3>
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
            <i class="bi bi-exclamation-circle-fill mr-3"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="branchesTable" class="w-full whitespace-nowrap">
                <thead class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Nama Cabang</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Kota</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($branches as $b): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4 font-bold text-primary"><?= htmlspecialchars($b['code'] ?? '') ?></td>
                                <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($b['name'] ?? '') ?></td>
                                <td class="px-6 py-4">
                                    <?php if(($b['type'] ?? '') == 'HQ'): ?>
                                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">HQ</span>
                                    <?php elseif(($b['type'] ?? '') == 'HUB'): ?>
                                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">HUB</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-full">AGEN</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600"><?= htmlspecialchars($b['city'] ?? '') ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($b['phone'] ?? '') ?></td>
                                <td class="px-6 py-4 flex items-center justify-end space-x-2">
                                    <button onclick="editBranch(<?= htmlspecialchars(json_encode($b)) ?>)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-warning hover:bg-warning/10 transition" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button onclick="deleteBranch(<?= $b['id'] ?>, '<?= htmlspecialchars($b['name']) ?>')" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add/Edit Branch -->
<div id="branchModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-2xl transform scale-95 transition-transform duration-300 mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="modalTitle" class="text-xl font-bold text-slate-800">Tambah Cabang</h3>
            <button onclick="closeBranchModal()" class="text-slate-400 hover:text-slate-700"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="branchForm" action="<?= BASE_URL ?>/branches" method="POST">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Cabang</label>
                        <input type="text" id="form_code" name="code" placeholder="Misal: JKT" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition uppercase text-slate-800" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tipe Cabang</label>
                        <select id="form_type" name="type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition text-slate-800" required>
                            <option value="AGEN">AGEN (Sub Cabang)</option>
                            <option value="HUB">HUB (Pusat Sortir Kota)</option>
                            <option value="HQ">HQ (Headquarters)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Cabang / Agen</label>
                    <input type="text" id="form_name" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition text-slate-800" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kota</label>
                        <input type="text" id="form_city" name="city" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition text-slate-800" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">No. Telepon</label>
                        <input type="text" id="form_phone" name="phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition text-slate-800" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                    <textarea id="form_address" name="address" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition text-slate-800" required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end space-x-3 bg-slate-50/50">
                <button type="button" onclick="closeBranchModal()" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center">
                    <i class="bi bi-check2 mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-sm transform scale-95 transition-transform duration-300 mx-4">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Cabang?</h3>
            <p class="text-slate-500 mb-6">Apakah Anda yakin ingin menghapus <strong id="deleteNameText" class="text-slate-700"></strong>? Data yang dihapus tidak bisa dikembalikan.</p>
            <form id="deleteForm" method="POST" action="">
                <div class="flex space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl font-medium text-white bg-red-500 hover:bg-red-600 transition">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div id="importModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-4xl transform scale-95 transition-transform duration-300 mx-4 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0">
            <h3 class="text-xl font-bold text-slate-800">Import Cabang (Excel)</h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-slate-700"><i class="bi bi-x-lg"></i></button>
        </div>
        
        <div class="p-6 flex-1 overflow-y-auto">
            <!-- Step 1: Upload -->
            <div id="importUploadStep">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-slate-600">Unggah file Excel (<code>.xlsx</code>) yang berisi data cabang.</p>
                    <a href="<?= BASE_URL ?>/branches/template" class="text-sm font-medium text-primary hover:underline flex items-center">
                        <i class="bi bi-download mr-1"></i> Download Template
                    </a>
                </div>
                
                <form id="importPreviewForm" onsubmit="submitPreview(event)">
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:bg-slate-50 transition cursor-pointer mb-6 relative">
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="document.getElementById('fileNameLabel').textContent = this.files[0].name">
                        <i class="bi bi-file-earmark-spreadsheet text-4xl text-emerald-400 mb-2"></i>
                        <h4 class="font-bold text-slate-700 mb-1">Pilih atau Tarik file Excel ke sini</h4>
                        <p id="fileNameLabel" class="text-sm text-slate-500">Maksimal ukuran file 2MB</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center">
                            <i class="bi bi-eye mr-2"></i> Preview Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Preview -->
            <div id="importPreviewStep" class="hidden">
                <div class="bg-blue-50 text-blue-700 p-4 rounded-xl border border-blue-100 mb-4 flex items-center">
                    <i class="bi bi-info-circle-fill mr-3"></i>
                    <span>Terdapat <strong id="previewCountText">0</strong> data cabang yang siap diimpor. Silakan periksa kembali sebelum menyimpan.</span>
                </div>
                
                <div class="border border-slate-200 rounded-xl overflow-hidden mb-6">
                    <div class="overflow-x-auto max-h-64">
                        <table class="w-full whitespace-nowrap">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 text-left text-xs font-semibold sticky top-0">
                                <tr>
                                    <th class="px-4 py-3">Kode</th>
                                    <th class="px-4 py-3">Nama Cabang</th>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3">Kota</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody" class="divide-y divide-slate-100 text-sm text-slate-700">
                                <!-- JS Injected -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/branches/import" method="POST" class="flex justify-end space-x-3">
                    <button type="button" onclick="resetImportModal()" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Kembali</button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center">
                        <i class="bi bi-cloud-arrow-up mr-2"></i> Proses Import
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- JavaScript block -->
    <script>
        $(document).ready(function() {
            $('#branchesTable').DataTable({
                "pageLength": 10,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "dom": '<"flex flex-col md:flex-row justify-between items-center p-4 border-b border-slate-100"lf>rt<"flex flex-col md:flex-row justify-between items-center p-4"ip>',
            });
        });
        // Toggle Modal Functions
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        const inner = modal.children[0];
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            inner.classList.remove('scale-95');
        }, 10);
    }
    
    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        const inner = modal.children[0];
        modal.classList.add('opacity-0');
        inner.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Branch Form Modal
    function openBranchModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Cabang';
        document.getElementById('branchForm').action = '<?= BASE_URL ?>/branches';
        document.getElementById('branchForm').reset();
        showModal('branchModal');
    }

    function editBranch(data) {
        document.getElementById('modalTitle').textContent = 'Edit Cabang';
        document.getElementById('branchForm').action = '<?= BASE_URL ?>/branches/update/' + data.id;
        
        document.getElementById('form_code').value = data.code;
        document.getElementById('form_type').value = data.type;
        document.getElementById('form_name').value = data.name;
        document.getElementById('form_city').value = data.city;
        document.getElementById('form_phone').value = data.phone;
        document.getElementById('form_address').value = data.address;
        
        showModal('branchModal');
    }

    function closeBranchModal() {
        hideModal('branchModal');
    }

    // Delete Modal
    function deleteBranch(id, name) {
        document.getElementById('deleteNameText').textContent = name;
        document.getElementById('deleteForm').action = '<?= BASE_URL ?>/branches/delete/' + id;
        showModal('deleteModal');
    }

    function closeDeleteModal() {
        hideModal('deleteModal');
    }

    // Import Modal
    function openImportModal() {
        resetImportModal();
        showModal('importModal');
    }

    function closeImportModal() {
        hideModal('importModal');
    }
    
    function resetImportModal() {
        document.getElementById('importUploadStep').classList.remove('hidden');
        document.getElementById('importPreviewStep').classList.add('hidden');
        document.getElementById('importPreviewForm').reset();
        document.getElementById('fileNameLabel').textContent = 'Maksimal ukuran file 2MB';
    }

    async function submitPreview(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-2"></i> Loading...';
        submitBtn.disabled = true;

        try {
            const res = await fetch('<?= BASE_URL ?>/branches/import-preview', {
                method: 'POST',
                body: formData
            });
            const result = await res.json();
            
            if (result.status === 'success') {
                const tbody = document.getElementById('previewTableBody');
                tbody.innerHTML = '';
                
                result.data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-3 font-semibold text-primary">${row.code}</td>
                        <td class="px-4 py-3">${row.name}</td>
                        <td class="px-4 py-3">${row.type}</td>
                        <td class="px-4 py-3">${row.city}</td>
                    `;
                    tbody.appendChild(tr);
                });
                
                document.getElementById('previewCountText').textContent = result.data.length;
                document.getElementById('importUploadStep').classList.add('hidden');
                document.getElementById('importPreviewStep').classList.remove('hidden');
            } else {
                alert('Error: ' + result.message);
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi saat memproses file.');
            console.error(err);
        } finally {
            submitBtn.innerHTML = '<i class="bi bi-eye mr-2"></i> Preview Data';
            submitBtn.disabled = false;
        }
    }
</script>
<?php \App\Helpers\View::endSection(); ?>
