<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Mitra / Klien</h2>
            <p class="text-slate-500 mt-1">Kelola logo perusahaan klien atau mitra bisnis Anda.</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Tambah Mitra</h3>
                    <form action="<?= BASE_URL ?>/settings/partners" method="POST" enctype="multipart/form-data">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Perusahaan</label>
                                <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required placeholder="PT Contoh Sukses">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Logo Perusahaan</label>
                                <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                                <p class="text-xs text-slate-400 mt-1">Biarkan kosong jika hanya ingin menampilkan teks nama.</p>
                            </div>

                            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition-all active:scale-95">
                                Tambah Mitra
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Nama / Perusahaan</th>
                                <th class="px-6 py-4 font-semibold text-center">Logo</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (empty($partners)): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                    <i class="bi bi-inbox text-3xl mb-2 block"></i>
                                    Belum ada data mitra
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($partners as $partner): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800"><?= htmlspecialchars($partner['name']) ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if (!empty($partner['logo_path']) && file_exists(BASE_PATH . '/public' . $partner['logo_path'])): ?>
                                            <img src="<?= BASE_URL . $partner['logo_path'] ?>" class="h-10 mx-auto object-contain">
                                        <?php else: ?>
                                            <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">TEKS SAJA</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="<?= BASE_URL ?>/settings/partners/delete/<?= $partner['id'] ?>" method="POST" class="inline-block" onsubmit="return confirm('Hapus mitra ini?');">
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

    </div>
</div>
<?php \App\Helpers\View::endSection(); ?>
