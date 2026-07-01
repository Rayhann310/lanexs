<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Testimoni Pelanggan</h2>
            <p class="text-slate-500 mt-1">Kelola ulasan dan penilaian pelanggan Anda.</p>
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
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Tambah Testimoni</h3>
                    <form action="<?= BASE_URL ?>/settings/testimonials" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Pelanggan</label>
                                <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required placeholder="Budi Santoso">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan / Perusahaan (Opsional)</label>
                                <input type="text" name="position" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="CEO PT Maju">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Isi Ulasan</label>
                                <textarea name="content" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required placeholder="Sangat puas dengan pelayanan..."></textarea>
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
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Pelanggan</th>
                                <th class="px-6 py-4 font-semibold">Ulasan</th>
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
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 font-bold flex items-center justify-center text-sm border border-slate-300">
                                                <?= htmlspecialchars($item['avatar_initials']) ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($item['name']) ?></p>
                                                <?php if (!empty($item['position'])): ?>
                                                    <p class="text-xs text-slate-500"><?= htmlspecialchars($item['position']) ?></p>
                                                <?php endif; ?>
                                                <div class="flex text-amber-400 text-xs mt-0.5">
                                                    <?php for ($i=0; $i < $item['rating']; $i++) echo '<i class="bi bi-star-fill"></i>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600 italic">"<?= htmlspecialchars((strlen($item['content']) > 60) ? substr($item['content'],0,60).'...' : $item['content']) ?>"</p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
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

    </div>
</div>
<?php \App\Helpers\View::endSection(); ?>
