<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Sistem</h2>
        <p class="text-slate-500 mt-1">Konfigurasi global aplikasi dan pemeliharaan sistem</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-start shadow-sm max-w-4xl">
            <i class="bi bi-check-circle-fill mr-3 mt-0.5"></i>
            <div>
                <div class="font-bold"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php if (isset($_SESSION['db_log'])): ?>
                    <ul class="text-xs mt-2 space-y-1 bg-emerald-100/50 p-3 rounded-lg overflow-x-auto max-h-40 overflow-y-auto font-mono">
                        <?php foreach($_SESSION['db_log'] as $log): ?>
                            <li><?= htmlspecialchars($log) ?></li>
                        <?php endforeach; unset($_SESSION['db_log']); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-center shadow-sm max-w-4xl">
            <i class="bi bi-exclamation-triangle-fill mr-3"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-4xl">
        <!-- Pengaturan Umum -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Pengaturan Umum</h3>
                </div>
                
                <form action="<?= BASE_URL ?>/settings/system" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aplikasi</label>
                            <input type="text" name="app_name" value="LANEXS - Logistik & Ekspedisi" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email Sistem (Sender)</label>
                            <input type="email" name="sys_email" value="no-reply@lanex.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-sm">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Migrasi Data Sireslan -->
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden mt-8">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="bi bi-database-up text-8xl text-emerald-500"></i>
                </div>
                
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-emerald-100 relative z-10">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Migrasi Data Sireslan (Legacy)</h3>
                </div>
                
                <div class="relative z-10">
                    <p class="text-sm text-slate-600 mb-6">
                        Unggah file <code class="bg-slate-100 text-slate-800 px-1 py-0.5 rounded">.sql</code> dari sistem Sireslan lama. Sistem akan memindahkan data ke Lanex secara aman tanpa merubah skema database saat ini.
                    </p>
                    
                    <form action="<?= BASE_URL ?>/settings/migrate-sireslan" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Anda yakin ingin menjalankan migrasi data ini? Proses ini mungkin membutuhkan waktu beberapa saat tergantung ukuran data.');">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload File SQL Sireslan</label>
                            <input type="file" name="sireslan_sql" accept=".sql" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center justify-center">
                            <i class="bi bi-cloud-arrow-up-fill mr-2"></i> Mulai Migrasi Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Maintenance & Self Healing -->
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="bi bi-database-exclamation text-8xl text-red-500"></i>
                </div>
                
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-red-100 relative z-10">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                        <i class="bi bi-tools"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Pemeliharaan & Self-Healing</h3>
                </div>
                
                <div class="relative z-10">
                    <p class="text-sm text-slate-600 mb-6">
                        Gunakan fitur ini jika terjadi kerusakan struktur database, tabel yang hilang, atau ketika Anda ingin memulihkan tabel sistem default (Self-Healing).
                    </p>
                    
                    <div class="bg-red-50 p-4 rounded-xl border border-red-100 mb-6">
                        <h4 class="text-xs font-bold text-red-700 uppercase tracking-wider mb-2">Peringatan:</h4>
                        <ul class="text-xs text-red-600 space-y-1 list-disc pl-4">
                            <li>Fitur ini akan mengeksekusi ulang seluruh skrip migrasi.</li>
                            <li>Tabel yang sudah ada tidak akan ditimpa atau dihapus (kecuali skrip mendefinisikannya).</li>
                            <li>Pastikan Anda telah melakukan *backup* database sebelumnya.</li>
                        </ul>
                    </div>

                    <form action="<?= BASE_URL ?>/settings/repair-db" method="POST" onsubmit="return confirm('Anda yakin ingin menjalankan proses perbaikan database (Self-Healing)?');">
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center justify-center">
                            <i class="bi bi-magic mr-2"></i> Jalankan Self-Healing Database
                        </button>
                    </form>

                    <div class="mt-8 border-t border-red-100 pt-6">
                        <h4 class="text-sm font-bold text-slate-800 mb-2">Generate Data Uji Coba (Dummy)</h4>
                        <p class="text-xs text-slate-500 mb-4">Buat 4 karyawan dengan semua tipe Role (Super, Owner, Manager, Admin) beserta 10 data paket dummy secara acak.</p>
                        <form action="<?= BASE_URL ?>/settings/generate-dummy" method="POST" onsubmit="return confirm('Generate data dummy?');">
                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center justify-center">
                                <i class="bi bi-database-add mr-2"></i> Buat Data Dummy
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 border-t border-red-100 pt-6">
                        <h4 class="text-sm font-bold text-slate-800 mb-2 text-red-600">Reset Setelan Pabrik</h4>
                        <p class="text-xs text-slate-500 mb-4">Hapus seluruh data operasional (paket, manifest, transaksi). Data master dan akun admin tetap aman.</p>
                        <form action="<?= BASE_URL ?>/settings/factory-reset" method="POST" onsubmit="return confirm('PERINGATAN KERAS! Seluruh data transaksi, paket, manifest, dan log audit akan dihapus secara permanen. Anda yakin ingin mereset sistem ke setelan pabrik?');">
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center justify-center">
                                <i class="bi bi-trash3-fill mr-2"></i> Reset Setelan Pabrik
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \App\Helpers\View::endSection(); ?>
