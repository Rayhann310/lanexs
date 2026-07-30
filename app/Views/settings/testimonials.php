<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="testimonialManager()">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Testimoni Pelanggan</h2>
            <p class="text-slate-500 mt-1">Kelola ulasan, logo klien, dan penilaian pelanggan Anda.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-start shadow-sm">
            <i class="bi bi-check-circle-fill mr-3 mt-0.5"></i>
            <div>
                <div class="font-bold"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-start shadow-sm">
            <i class="bi bi-x-circle-fill mr-3 mt-0.5"></i>
            <div>
                <div class="font-bold"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Tambah Testimoni</h3>
                    <form action="<?= BASE_URL ?>/settings/testimonials" method="POST" enctype="multipart/form-data">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Tampilan</label>
                                <select name="display_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none" required>
                                    <option value="text">Teks Saja (Default)</option>
                                    <option value="logo">Logo Saja</option>
                                    <option value="both">Logo & Teks</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama / Perusahaan</label>
                                <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required placeholder="Budi Santoso">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan (Opsional)</label>
                                <input type="text" name="position" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="CEO PT Maju">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Logo / Avatar (Opsional)</label>
                                <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Isi Ulasan</label>
                                <textarea name="content" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="Sangat puas dengan pelayanan..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Bintang (Rating 1-5)</label>
                                <select name="rating" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                                    <option value="5">5 Bintang (Sangat Puas)</option>
                                    <option value="4">4 Bintang (Puas)</option>
                                    <option value="3">3 Bintang (Cukup)</option>
                                    <option value="2">2 Bintang (Kurang)</option>
                                    <option value="1">1 Bintang (Buruk)</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition-all active:scale-95">
                                Tambah Testimoni
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Tipe & Logo</th>
                            <th class="px-6 py-4 font-semibold">Detail</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($testimonials)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                <i class="bi bi-chat-left-text text-3xl mb-2 block"></i>
                                Belum ada testimoni
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($testimonials as $item): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2">
                                        <span class="inline-block px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase tracking-wider max-w-fit">
                                            <?= htmlspecialchars($item['display_type'] ?? 'text') ?>
                                        </span>
                                        <?php if (!empty($item['logo'])): ?>
                                            <img src="<?= BASE_URL . $item['logo'] ?>" class="h-10 w-auto object-contain bg-white rounded border border-slate-200 p-1">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 font-bold flex items-center justify-center text-sm border border-slate-300">
                                                <?= htmlspecialchars($item['avatar_initials']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($item['name']) ?></p>
                                    <?php if (!empty($item['position'])): ?>
                                        <p class="text-xs text-slate-500"><?= htmlspecialchars($item['position']) ?></p>
                                    <?php endif; ?>
                                    <div class="flex text-amber-400 text-xs mt-0.5 mb-2">
                                        <?php for ($i=0; $i < ($item['rating']?:5); $i++) echo '<i class="bi bi-star-fill"></i>'; ?>
                                    </div>
                                    <?php if (!empty($item['content'])): ?>
                                        <p class="text-sm text-slate-600 italic line-clamp-2">"<?= htmlspecialchars($item['content']) ?>"</p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click='openEditModal(<?= json_encode($item) ?>)' class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors inline-flex items-center justify-center mr-1">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?= BASE_URL ?>/settings/testimonials/delete/<?= $item['id'] ?>" method="POST" class="inline-block" onsubmit="return confirm('Hapus testimoni ini?');">
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div x-show="editModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModal = false"></div>
        <div x-show="editModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-800">Edit Testimoni</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form :action="editFormAction" method="POST" enctype="multipart/form-data" id="editForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Tampilan</label>
                            <select name="display_type" x-model="editData.display_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none" required>
                                <option value="text">Teks Saja</option>
                                <option value="logo">Logo Saja</option>
                                <option value="both">Logo & Teks</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama / Perusahaan</label>
                            <input type="text" name="name" x-model="editData.name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan (Opsional)</label>
                            <input type="text" name="position" x-model="editData.position" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Logo / Avatar (Ganti Baru)</label>
                            <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                            <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah logo.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Isi Ulasan</label>
                            <textarea name="content" x-model="editData.content" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Bintang (Rating 1-5)</label>
                            <select name="rating" x-model="editData.rating" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                                <option value="5">5 Bintang</option>
                                <option value="4">4 Bintang</option>
                                <option value="3">3 Bintang</option>
                                <option value="2">2 Bintang</option>
                                <option value="1">1 Bintang</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="p-6 border-t border-slate-100 flex justify-end space-x-3 bg-slate-50/50">
                <button type="button" @click="editModal = false" class="px-4 py-2 text-slate-600 font-medium hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                <button type="submit" form="editForm" class="px-6 py-2 bg-primary hover:bg-secondary text-white font-bold rounded-xl shadow-md transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('testimonialManager', () => ({
        editModal: false,
        editFormAction: '',
        editData: {
            id: '',
            name: '',
            position: '',
            content: '',
            rating: 5,
            display_type: 'text'
        },
        openEditModal(item) {
            this.editData = {
                id: item.id,
                name: item.name,
                position: item.position || '',
                content: item.content || '',
                rating: item.rating || 5,
                display_type: item.display_type || 'text'
            };
            this.editFormAction = '<?= BASE_URL ?>/settings/testimonials/update/' + item.id;
            this.editModal = true;
        }
    }));
});
</script>
<?php \App\Helpers\View::endSection(); ?>
