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
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="hero-image-slots">
                            <?php 
                                $currentImages = $settings['landing_hero_images'] ?? []; 
                                for($i = 0; $i < 7; $i++): 
                                    $imgUrl = isset($currentImages[$i]) ? BASE_URL . $currentImages[$i] : '';
                                    $hasImg = !empty($imgUrl);
                            ?>
                                <div class="hero-slot relative group rounded-xl overflow-hidden border-2 <?= $hasImg ? 'border-primary' : 'border-dashed border-slate-300 bg-slate-50' ?> aspect-video flex items-center justify-center cursor-pointer transition-all hover:border-primary/50" onclick="document.getElementById('hero_input_<?= $i ?>').click()">
                                    
                                    <!-- Preview Image -->
                                    <img src="<?= $imgUrl ?>" id="preview_<?= $i ?>" class="w-full h-full object-cover <?= $hasImg ? '' : 'hidden' ?>">
                                    
                                    <!-- Placeholder (if empty) -->
                                    <div id="placeholder_<?= $i ?>" class="flex flex-col items-center text-slate-400 <?= $hasImg ? 'hidden' : '' ?>">
                                        <i class="bi bi-image text-2xl mb-1"></i>
                                        <span class="text-xs font-semibold">Slot <?= $i + 1 ?></span>
                                    </div>
                                    
                                    <!-- Overlay for Edit/Delete -->
                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity gap-2 <?= $hasImg ? '' : 'hidden' ?>" id="overlay_<?= $i ?>">
                                        <button type="button" class="bg-white/20 hover:bg-white/40 text-white rounded p-1.5 backdrop-blur-sm" title="Tukar/Ganti">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <label class="bg-red-500/80 hover:bg-red-600 text-white rounded p-1.5 backdrop-blur-sm cursor-pointer" onclick="event.stopPropagation();" title="Hapus">
                                            <input type="checkbox" name="delete_images[]" value="<?= $i ?>" class="hidden delete-checkbox">
                                            <i class="bi bi-trash"></i>
                                        </label>
                                    </div>

                                    <!-- Hidden File Input -->
                                    <input type="file" name="hero_images[<?= $i ?>]" id="hero_input_<?= $i ?>" accept="image/png, image/jpeg" class="hidden image-input" data-index="<?= $i ?>" onchange="previewImage(this, <?= $i ?>)">
                                </div>
                            <?php endfor; ?>
                        </div>
                        <p class="text-xs text-slate-500 mt-2"><i class="bi bi-info-circle mr-1"></i> Klik slot untuk upload/ganti gambar (disarankan 1920x1080). Untuk menghapus, klik ikon tong sampah (gambar akan dihapus saat form disimpan). Gambar yang dihapus akan ditandai opacity merah sebelum disimpan.</p>
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

<script>
// Image Preview Logic
function previewImage(input, index) {
    const preview = document.getElementById('preview_' + index);
    const placeholder = document.getElementById('placeholder_' + index);
    const overlay = document.getElementById('overlay_' + index);
    const slot = preview.closest('.hero-slot');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            overlay.classList.remove('hidden');
            slot.classList.remove('border-dashed', 'border-slate-300', 'bg-slate-50');
            slot.classList.add('border-primary');
            
            // Uncheck delete if a new file is chosen
            const delCheck = overlay.querySelector('.delete-checkbox');
            if(delCheck) {
                delCheck.checked = false;
                slot.classList.remove('opacity-50', 'ring-2', 'ring-red-500');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Handle visual feedback for delete checkboxes
document.querySelectorAll('.delete-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const slot = this.closest('.hero-slot');
        if(this.checked) {
            slot.classList.add('opacity-50', 'ring-2', 'ring-red-500');
        } else {
            slot.classList.remove('opacity-50', 'ring-2', 'ring-red-500');
        }
    });
});
</script>
<?php \App\Helpers\View::endSection(); ?>
