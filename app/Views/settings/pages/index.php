<?php \App\Helpers\View::extends('app'); ?>
<?php \App\Helpers\View::section('content'); ?>

<div class="px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Halaman Dinamis</h2>
        <p class="text-slate-500 mt-1">Edit konten halaman-halaman publik di Landing Page Anda.</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-center">
            <i class="bi bi-check-circle-fill mr-3"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php
        $icons = [
            'sejarah-perusahaan'  => ['icon' => 'bi-clock-history',       'color' => 'text-blue-500',    'bg' => 'bg-blue-50'],
            'visi-misi'           => ['icon' => 'bi-eye',                  'color' => 'text-purple-500',  'bg' => 'bg-purple-50'],
            'struktur-organisasi' => ['icon' => 'bi-diagram-3',            'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50'],
            'layanan-pengiriman'  => ['icon' => 'bi-truck',                'color' => 'text-amber-500',   'bg' => 'bg-amber-50'],
            'layanan-pengemasan'  => ['icon' => 'bi-box-seam',             'color' => 'text-orange-500',  'bg' => 'bg-orange-50'],
            'layanan-tracking'    => ['icon' => 'bi-geo-alt',              'color' => 'text-red-500',     'bg' => 'bg-red-50'],
            'experience'          => ['icon' => 'bi-award',                'color' => 'text-teal-500',    'bg' => 'bg-teal-50'],
            'kontak-kami'         => ['icon' => 'bi-telephone-inbound',    'color' => 'text-slate-500',   'bg' => 'bg-slate-100'],
        ];
        ?>
        <?php foreach ($pages as $page): ?>
        <?php $meta = $icons[$page['slug']] ?? ['icon' => 'bi-file-earmark-text', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100']; ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl <?= $meta['bg'] ?> flex items-center justify-center">
                    <i class="bi <?= $meta['icon'] ?> text-xl <?= $meta['color'] ?>"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-800"><?= htmlspecialchars($page['title']) ?></p>
                    <p class="text-xs text-slate-400 font-mono">/page/<?= htmlspecialchars($page['slug']) ?></p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="<?= BASE_URL ?>/page/<?= $page['slug'] ?>" target="_blank" class="w-9 h-9 rounded-lg bg-slate-50 text-slate-500 hover:bg-slate-100 transition-colors flex items-center justify-center" title="Lihat Halaman">
                    <i class="bi bi-box-arrow-up-right text-sm"></i>
                </a>
                <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="w-9 h-9 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors flex items-center justify-center" title="Edit">
                    <i class="bi bi-pencil-fill text-sm"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php \App\Helpers\View::endSection(); ?>
