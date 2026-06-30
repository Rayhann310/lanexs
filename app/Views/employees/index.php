<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="importMixin()">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Data Karyawan</h2>
            <p class="text-slate-500 mt-1">Kelola akun pengguna, karyawan, dan hak akses sistem</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/employees/export" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-excel mr-2"></i> Export .xlsx
            </a>
            <a href="<?= BASE_URL ?>/employees/template" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-down mr-2"></i> Template
            </a>
            <button @click="importModal = true" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-file-earmark-arrow-up mr-2"></i> Import
            </button>
            <button onclick="showModal('employeeModal')" class="bg-primary hover:bg-secondary text-white px-4 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/20 transition flex items-center text-sm">
                <i class="bi bi-person-plus-fill mr-2"></i> Tambah Karyawan
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

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="employeesTable" class="w-full whitespace-nowrap">
                <thead class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">Lokasi (Cabang)</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($employees as $emp): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold mr-3">
                                        <?= strtoupper(substr($emp['fullname'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-800"><?= htmlspecialchars($emp['fullname']) ?></div>
                                        <div class="text-xs text-slate-500 mt-0.5">@<?= htmlspecialchars($emp['username']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm"><?= htmlspecialchars($emp['phone'] ?? '-') ?></div>
                                <div class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($emp['email'] ?? '-') ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <?= htmlspecialchars($emp['role_name'] ?? 'Tidak Diketahui') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <?= htmlspecialchars($emp['branch_name'] ?? 'Pusat/HQ') ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($emp['is_active']): ?>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700"><i class="bi bi-circle-fill text-[8px] mr-1"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600"><i class="bi bi-circle-fill text-[8px] mr-1"></i> Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 flex items-center justify-end space-x-2">
                                <button onclick='editEmployee(<?= json_encode($emp) ?>)' class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-500/10 transition" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <?php if ($emp['id'] != $_SESSION['user_id']): ?>
                                <button onclick="deleteEmployee(<?= $emp['id'] ?>, '<?= htmlspecialchars($emp['fullname']) ?>')" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- Modal Karyawan -->
<div id="employeeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center pt-10 pb-10 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 transform scale-95 transition-transform duration-300 relative my-auto">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800" id="modalTitle">Tambah Karyawan Baru</h3>
            <button onclick="hideModal('employeeModal')" class="text-slate-400 hover:text-slate-600 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <form id="employeeForm" action="<?= BASE_URL ?>/employees" method="POST" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username (Readonly on edit, or not? Let's make it readonly on Edit by changing its container) -->
                <div id="usernameContainer">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="username" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" id="passwordLabel">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="Minimal 6 karakter">
                </div>

                <div class="md:col-span-2 border-t border-slate-100 pt-4"></div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="fullname" id="fullname" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">No. HP / WhatsApp</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan (Role) <span class="text-red-500">*</span></label>
                    <select name="role_id" id="role_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        <option value="">-- Pilih Jabatan --</option>
                        <?php foreach($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Penempatan Cabang</label>
                    <select name="branch_id" id="branch_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        <option value="">-- Pusat (HQ) / Tidak Terikat --</option>
                        <?php foreach($branches as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status Akun</label>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="is_active" id="status_active" value="1" checked class="w-4 h-4 text-primary bg-slate-100 border-slate-300 focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-slate-700">Aktif</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="is_active" id="status_inactive" value="0" class="w-4 h-4 text-primary bg-slate-100 border-slate-300 focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-slate-700">Nonaktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="hideModal('employeeModal')" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" id="submitBtn" class="bg-primary hover:bg-secondary text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center">
                    <i class="bi bi-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" class="hidden"></form>

<?php
    $moduleName = 'Karyawan';
    $importPreviewUrl = BASE_URL . '/employees/import-preview';
    $importProcessUrl = BASE_URL . '/employees/import-process';
    $templateUrl = BASE_URL . '/employees/template';
    include __DIR__ . '/../partials/import_modal.php';
?>

<!-- Preview Modal -->
<div x-show="previewModal" style="display:none" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
    <div x-show="previewModal" x-transition class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl mx-4 flex flex-col" style="max-height:90vh">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Preview Import Karyawan</h3>
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
                        <th class="px-3 py-3 text-left">Username</th>
                        <th class="px-3 py-3 text-left">Nama Lengkap</th>
                        <th class="px-3 py-3 text-left">Email / Telp</th>
                        <th class="px-3 py-3 text-center">Role ID</th>
                        <th class="px-3 py-3 text-center rounded-tr-lg">Branch ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, idx) in previewRows" :key="idx">
                        <tr :class="row._valid ? 'hover:bg-slate-50' : 'bg-red-50 hover:bg-red-100'">
                            <td class="px-3 py-2.5 font-mono text-slate-400" x-text="idx+1"></td>
                            <td class="px-3 py-2.5">
                                <span x-show="row._valid" class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold text-[10px]">✓ VALID</span>
                                <span x-show="!row._valid" class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold text-[10px]" :title="row._duplicate ? 'Username sudah dipakai' : 'Data tidak lengkap'">✗ ERROR</span>
                            </td>
                            <td class="px-3 py-2.5 font-medium" :class="row._duplicate ? 'text-red-600' : 'text-slate-700'" x-text="'@' + row.username"></td>
                            <td class="px-3 py-2.5 text-slate-700" x-text="row.fullname"></td>
                            <td class="px-3 py-2.5 text-slate-600"><div x-text="row.email"></div><div x-text="row.phone"></div></td>
                            <td class="px-3 py-2.5 text-center font-mono" x-text="row.role_id"></td>
                            <td class="px-3 py-2.5 text-center font-mono" x-text="row.branch_id || '-'"></td>
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
                <button @click="processImport('<?= BASE_URL ?>/employees/import-process', 'karyawan')"
                        :disabled="previewRows.filter(r=>r._valid).length===0 || processLoading"
                        class="px-6 py-2.5 rounded-xl text-white bg-primary hover:bg-secondary text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <svg x-show="processLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <i x-show="!processLoading" class="bi bi-cloud-upload mr-2"></i>
                    <span x-text="processLoading ? 'Menyimpan...' : 'Proses Import (' + previewRows.filter(r=>r._valid).length + ' Akun)'"></span>
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
    $(document).ready(function() {
        $('#employeesTable').DataTable({
            "pageLength": 10,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center p-4 border-b border-slate-100"lf>rt<"flex flex-col md:flex-row justify-between items-center p-4"ip>',
        });
    });

    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        const inner = modal.children[0];
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            inner.classList.remove('scale-95');
            inner.classList.add('scale-100');
        }, 10);
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        const inner = modal.children[0];
        modal.classList.add('opacity-0');
        inner.classList.remove('scale-100');
        inner.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            if (modalId === 'employeeModal') document.getElementById('employeeForm').reset();
        }, 300);
    }

    function editEmployee(data) {
        document.getElementById('modalTitle').textContent = 'Edit Data Karyawan';
        document.getElementById('employeeForm').action = '<?= BASE_URL ?>/employees/update/' + data.id;
        
        document.getElementById('usernameContainer').style.display = 'none'; // Sembunyikan field username
        document.getElementById('username').required = false;
        
        document.getElementById('passwordLabel').innerHTML = 'Password Baru <span class="text-xs text-slate-500 font-normal ml-1">(Kosongkan jika tidak ingin mengubah)</span>';
        document.getElementById('password').required = false;
        
        document.getElementById('fullname').value = data.fullname;
        document.getElementById('phone').value = data.phone;
        document.getElementById('email').value = data.email;
        document.getElementById('role_id').value = data.role_id;
        document.getElementById('branch_id').value = data.branch_id || '';
        
        if (data.is_active == 1) {
            document.getElementById('status_active').checked = true;
        } else {
            document.getElementById('status_inactive').checked = true;
        }

        document.getElementById('submitBtn').innerHTML = '<i class="bi bi-save mr-2"></i> Simpan Perubahan';
        
        showModal('employeeModal');
    }

    document.querySelector('button[onclick="showModal(\'employeeModal\')"]').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Tambah Karyawan Baru';
        document.getElementById('employeeForm').action = '<?= BASE_URL ?>/employees';
        document.getElementById('usernameContainer').style.display = 'block';
        document.getElementById('username').required = true;
        document.getElementById('passwordLabel').innerHTML = 'Password <span class="text-red-500">*</span>';
        document.getElementById('password').required = true;
        document.getElementById('submitBtn').innerHTML = '<i class="bi bi-save mr-2"></i> Simpan';
    });

    function deleteEmployee(id, name) {
        Swal.fire({
            title: 'Hapus Karyawan?',
            html: `Anda yakin ingin menghapus <b>${name}</b>?<br>Ini hanya akan menonaktifkan akun secara sistem (Soft Delete).`,
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
                form.action = '<?= BASE_URL ?>/employees/delete/' + id;
                form.submit();
            }
        });
    }
</script>
<?php \App\Helpers\View::endSection(); ?>
