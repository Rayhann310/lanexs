<?php \App\Helpers\View::extends('app'); ?>
<?php \App\Helpers\View::section('content'); ?>

<div class="px-8 py-8">
    <div class="mb-6 flex items-center space-x-4">
        <a href="<?= BASE_URL ?>/settings/pages" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Edit: <?= htmlspecialchars($page['title']) ?></h2>
            <p class="text-slate-400 text-sm mt-0.5 font-mono">/page/<?= htmlspecialchars($page['slug']) ?></p>
        </div>
        <div class="ml-auto">
            <a href="<?= BASE_URL ?>/page/<?= $page['slug'] ?>" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition-colors flex items-center">
                <i class="bi bi-box-arrow-up-right mr-2"></i> Lihat Halaman
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-center">
            <i class="bi bi-check-circle-fill mr-3"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-center">
            <i class="bi bi-x-circle-fill mr-3"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/settings/pages/update/<?= $page['id'] ?>" method="POST">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Halaman</label>
                <input type="text" name="title" value="<?= htmlspecialchars($page['title']) ?>"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all font-medium text-slate-800"
                    required>
            </div>

            <div class="p-6">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Konten Halaman</label>
                <!-- Quill Editor Toolbar -->
                <div id="quill-toolbar" class="border border-slate-200 rounded-t-xl bg-slate-50 px-2 py-1">
                    <span class="ql-formats">
                        <select class="ql-header"><option selected></option><option value="1"></option><option value="2"></option><option value="3"></option></select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-bold"></button>
                        <button class="ql-italic"></button>
                        <button class="ql-underline"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-list" value="ordered"></button>
                        <button class="ql-list" value="bullet"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-link"></button>
                        <button class="ql-blockquote"></button>
                        <button class="ql-code-block"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-clean"></button>
                    </span>
                </div>
                <!-- Quill Editor Body -->
                <div id="quill-editor" class="border border-t-0 border-slate-200 rounded-b-xl min-h-[420px] text-base text-slate-700 bg-white"><?= $page['content'] ?></div>
                <!-- Hidden input for form submission -->
                <input type="hidden" name="content" id="quill-content">
            </div>

            <div class="px-6 pb-6 flex items-center space-x-4">
                <button type="submit" id="save-btn" class="bg-primary hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-md transition-all active:scale-95 flex items-center">
                    <i class="bi bi-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="<?= BASE_URL ?>/settings/pages" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Batal</a>
            </div>
        </div>
    </form>
</div>

<?php \App\Helpers\View::endSection(); ?>
<?php \App\Helpers\View::section('scripts'); ?>
<!-- Quill.js -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    const quill = new Quill('#quill-editor', {
        modules: { toolbar: '#quill-toolbar' },
        theme: 'snow'
    });

    // Sync quill HTML to hidden input on form submit
    document.getElementById('save-btn').closest('form').addEventListener('submit', function() {
        document.getElementById('quill-content').value = quill.getSemanticHTML();
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
