<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Hero & Kontak</h2>
        <p class="text-slate-500 mt-1">Konfigurasi teks, gambar slide, dan informasi kontak website.</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-start shadow-sm max-w-4xl">
            <i class="bi bi-check-circle-fill mr-3 mt-0.5"></i>
            <div>
                <div class="font-bold"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-center shadow-sm max-w-4xl">
            <i class="bi bi-exclamation-triangle-fill mr-3"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/settings/landing" method="POST" enctype="multipart/form-data" class="space-y-8 max-w-4xl">
        <!-- Section 1 (Hero) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                        <i class="bi bi-layout-text-window-reverse"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Section 1 (Hero & Slider)</h3>
                </div>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Utama (Title)</label>
                        <input type="text" name="landing_hero_title" value="<?= htmlspecialchars($settings['landing_hero_title'] ?? '') ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Subjudul (Subtitle)</label>
                        <textarea name="landing_hero_subtitle" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required><?= htmlspecialchars($settings['landing_hero_subtitle'] ?? '') ?></textarea>
                    </div>

                    <div class="border-t border-slate-100 pt-5 mt-5">
                        <label class="block text-sm font-medium text-slate-700 mb-3">Gambar Slider (Maks 7 Gambar)</label>
                        
                        <?php if(!empty($settings['landing_hero_images'])): ?>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <?php foreach($settings['landing_hero_images'] as $img): ?>
                                    <div class="relative group rounded-xl overflow-hidden border border-slate-200">
                                        <img src="<?= BASE_URL . $img ?>" class="w-full h-24 object-cover">
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <label class="text-white text-xs font-bold cursor-pointer flex items-center space-x-1">
                                                <input type="checkbox" name="delete_images[]" value="<?= htmlspecialchars($img) ?>" class="rounded text-red-500">
                                                <span>Hapus</span>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="bi bi-cloud-arrow-up text-2xl text-slate-400 mb-2"></i>
                                    <p class="mb-1 text-sm text-slate-500"><span class="font-semibold">Klik untuk upload</span></p>
                                    <p class="text-xs text-slate-400">PNG, JPG (Disarankan 1920x1080)</p>
                                </div>
                                <input type="file" name="hero_images[]" accept="image/png, image/jpeg" multiple class="hidden" />
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Kontak -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Informasi Kontak & Peta</h3>
                </div>
                
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="text" name="landing_contact_email" value="<?= htmlspecialchars($settings['landing_contact_email'] ?? '') ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Telepon (Gunakan <br> jika lebih dari 1)</label>
                            <input type="text" name="landing_contact_phone" value="<?= htmlspecialchars($settings['landing_contact_phone'] ?? '') ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat (Gunakan <br> untuk baris baru)</label>
                        <textarea name="landing_contact_address" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required><?= htmlspecialchars($settings['landing_contact_address'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Google Map (URL Iframe 'src')</label>
                        <textarea name="landing_contact_map" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="https://www.google.com/maps/embed?pb=..." required><?= htmlspecialchars($settings['landing_contact_map'] ?? '') ?></textarea>
                        <p class="text-xs text-slate-400 mt-2">Ambil dari Google Maps -> Bagikan -> Sematkan Peta -> Salin URL di dalam src="...".</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 transition-all active:scale-95">
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
<?php \App\Helpers\View::endSection(); ?>
