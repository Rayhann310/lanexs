<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="serviceManager()" x-init="init()">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Master Layanan</h2>
            <p class="text-slate-500 mt-1">Kelola layanan pengiriman (Reguler, Express, Kargo, dll)</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" @click="openCreateModal()" class="bg-primary hover:bg-secondary text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center text-sm">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Layanan
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

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50/50 text-slate-500 text-left text-xs font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Kode</th>
                    <th class="px-6 py-3">Nama Layanan</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3">Est. Hari</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($services)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <i class="bi bi-inbox text-3xl mb-2 block"></i> Belum ada data layanan. Silakan tambahkan.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach($services as $i => $svc): ?>
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-3 text-slate-400"><?= $i + 1 ?></td>
                    <td class="px-6 py-3 font-bold text-primary"><?= htmlspecialchars($svc['code']) ?></td>
                    <td class="px-6 py-3 font-medium"><?= htmlspecialchars($svc['name']) ?></td>
                    <td class="px-6 py-3 text-slate-500"><?= htmlspecialchars($svc['description'] ?? '-') ?></td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg font-semibold text-xs"><?= $svc['estimated_days'] ?> hari</span>
                    </td>
                    <td class="px-6 py-3">
                        <?php if ($svc['is_active']): ?>
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Aktif</span>
                        <?php else: ?>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-3 text-right flex items-center justify-end gap-2">
                        <button onclick="window.serviceManager.openEditModal(<?= htmlspecialchars(json_encode($svc)) ?>)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 transition" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button onclick="window.serviceManager.deleteService(<?= $svc['id'] ?>, '<?= htmlspecialchars($svc['name']) ?>')" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Create/Edit -->
    <div x-show="modalOpen" style="display:none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
        <div @click.away="modalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800" x-text="modalTitle"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg text-xl"></i></button>
            </div>
            <form :action="formAction" method="POST" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kode Layanan <span class="text-red-500">*</span></label>
                        <input type="text" name="code" x-model="form.code" required placeholder="REG, EXP, dll" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Est. Hari</label>
                        <input type="number" name="estimated_days" x-model="form.estimated_days" min="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Layanan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Reguler, Express, Kargo..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" x-model="form.description" rows="2" placeholder="Keterangan singkat layanan..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all"></textarea>
                </div>
                <div x-show="isEdit">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                        <span class="text-sm font-medium text-slate-700">Layanan Aktif</span>
                    </label>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="modalOpen = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" x-text="submitLabel" class="bg-primary hover:bg-secondary text-white px-8 py-2.5 rounded-xl font-medium transition shadow-sm"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteServiceForm" method="POST" style="display:none;"></form>
</div>

<script>
function serviceManager() {
    return {
        modalOpen: false,
        isEdit: false,
        modalTitle: '',
        formAction: '',
        submitLabel: 'Simpan',
        form: { code: '', name: '', description: '', estimated_days: 1, is_active: true },

        init() {
            window.serviceManager = this;
        },

        openCreateModal() {
            this.isEdit = false;
            this.modalTitle = 'Tambah Layanan Baru';
            this.formAction = '<?= BASE_URL ?>/services';
            this.submitLabel = 'Simpan';
            this.form = { code: '', name: '', description: '', estimated_days: 1, is_active: true };
            this.modalOpen = true;
        },

        openEditModal(data) {
            this.isEdit = true;
            this.modalTitle = 'Edit Layanan';
            this.formAction = '<?= BASE_URL ?>/services/update/' + data.id;
            this.submitLabel = 'Simpan Perubahan';
            this.form = {
                code: data.code,
                name: data.name,
                description: data.description || '',
                estimated_days: data.estimated_days,
                is_active: data.is_active == 1,
            };
            this.modalOpen = true;
        },

        deleteService(id, name) {
            Swal.fire({
                title: 'Hapus Layanan?',
                html: `Layanan <b>${name}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteServiceForm');
                    form.action = '<?= BASE_URL ?>/services/delete/' + id;
                    form.submit();
                }
            });
        }
    }
}
</script>
<?php \App\Helpers\View::endSection(); ?>
