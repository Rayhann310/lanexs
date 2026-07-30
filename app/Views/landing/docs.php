<?php
$pageTitle = 'Dokumentasi Sistem - ' . APP_NAME . ' Logistics';
$pageDescription = 'Panduan komprehensif tentang cara kerja sistem logistik ' . APP_NAME . ', fitur yang tersedia, serta hak akses untuk setiap peran pengguna.';

ob_start();
?>

<!-- Header Section -->
<div class="pt-32 pb-16 bg-gradient-to-br from-slate-900 to-slate-800 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="px-4 py-1.5 rounded-full bg-primary/20 text-primary border border-primary/30 text-sm font-semibold tracking-wide uppercase mb-6 inline-block">
            Dokumentasi & Panduan
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6">
            Memahami Alur Kerja <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">LANEXS</span>
        </h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto">
            Panduan komprehensif tentang cara kerja sistem logistik LANEXS, fitur yang tersedia, serta hak akses untuk setiap peran pengguna.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
        
        <!-- Sidebar Navigation -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="sticky top-28 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4 uppercase tracking-wider text-sm">Daftar Isi</h3>
                <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                    <li><a href="#roles" class="hover:text-primary dark:hover:text-primary transition-colors block"><i class="bi bi-people mr-2"></i> Hak Akses & Peran</a></li>
                    <li><a href="#workflow" class="hover:text-primary dark:hover:text-primary transition-colors block"><i class="bi bi-diagram-3 mr-2"></i> Alur Pengiriman Paket</a></li>
                    <li><a href="#finance" class="hover:text-primary dark:hover:text-primary transition-colors block"><i class="bi bi-wallet2 mr-2"></i> Keuangan & Tagihan</a></li>
                    <li><a href="#features" class="hover:text-primary dark:hover:text-primary transition-colors block"><i class="bi bi-stars mr-2"></i> Fitur Unggulan (Excel)</a></li>
                    <li><a href="#mobile" class="hover:text-primary dark:hover:text-primary transition-colors block"><i class="bi bi-phone mr-2"></i> Penggunaan Kurir</a></li>
                </ul>
            </div>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-3 space-y-16">
            
            <!-- Hak Akses -->
            <section id="roles" class="scroll-mt-28">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center text-xl">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white transition-colors">Hak Akses (Role Base)</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-bold text-primary mb-2">1. Administrator</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 transition-colors">Memiliki akses penuh ke seluruh sistem tanpa batasan.</p>
                        <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 transition-colors">
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Manajemen Karyawan & User</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Pengaturan Sistem & Tarif</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Lihat Audit Trail (Log)</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Laporan Keuangan Global</li>
                        </ul>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-bold text-purple-600 dark:text-purple-400 mb-2">2. Owner (Pemilik)</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 transition-colors">Fokus pada pemantauan kinerja dan laporan (Read-only view).</p>
                        <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 transition-colors">
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Lihat Dashboard Statistik Global</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Laporan Keuangan & Karyawan</li>
                            <li><i class="bi bi-x-circle-fill text-red-400 mr-2"></i> Tidak bisa mengubah/menghapus data</li>
                        </ul>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-bold text-orange-500 dark:text-orange-400 mb-2">3. Admin Cabang</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 transition-colors">Mengelola operasional khusus untuk cabang yang ditugaskan kepadanya.</p>
                        <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 transition-colors">
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Buat Resi/Paket (Auto B2B)</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Buat Surat Jalan (Manifest)</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Update Status Paket</li>
                        </ul>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-bold text-emerald-500 mb-2">4. Kurir (Driver)</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 transition-colors">Menggunakan PWA/Mobile untuk operasional lapangan.</p>
                        <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 transition-colors">
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Lihat tugas pengiriman/pickup</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Update status (SELESAI/DELIVERY)</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Real-time sinkronisasi</li>
                        </ul>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow md:col-span-2">
                        <h3 class="text-lg font-bold text-indigo-500 dark:text-indigo-400 mb-2">5. Klien B2B (Corporate)</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 transition-colors">Portal khusus untuk perusahaan yang berlangganan layanan LANEXS.</p>
                        <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 transition-colors">
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Melacak seluruh paket yang dikirimkan oleh perusahaannya.</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Melihat tagihan (Invoice) yang belum dibayar.</li>
                            <li><i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i> Dashboard analisis pengiriman perusahaannya sendiri.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <hr class="border-slate-100 dark:border-slate-800">

            <!-- Alur Kerja -->
            <section id="workflow" class="scroll-mt-28">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-500 flex items-center justify-center text-xl">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white transition-colors">Alur Pengiriman Paket</h2>
                </div>
                
                <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-5 space-y-8 pb-4 transition-colors">
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-primary border-4 border-white dark:border-slate-900 transition-colors"></div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg mb-1 transition-colors">1. Pembuatan Resi (PENDING)</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed transition-colors">
                            Admin Cabang atau Administrator membuat resi di menu <b class="text-slate-700 dark:text-slate-300">Manajemen Paket</b>. Bisa memilih Klien B2B untuk auto-fill nama pengirim, atau mengisinya secara manual. Sistem akan mengkalkulasi harga otomatis berdasarkan <b class="text-slate-700 dark:text-slate-300">Manajemen Tarif</b>.
                        </p>
                    </div>
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 border-4 border-white dark:border-slate-900 transition-colors"></div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg mb-1 transition-colors">2. Manifest / Surat Jalan</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed transition-colors">
                            Paket-paket yang akan diberangkatkan dikelompokkan ke dalam satu <b class="text-slate-700 dark:text-slate-300">Surat Jalan (Manifest)</b> oleh Admin Cabang Asal. Kurir/Driver ditugaskan untuk membawa manifest tersebut. Status paket berubah menjadi <b class="text-slate-700 dark:text-slate-300">TRANSIT</b>.
                        </p>
                    </div>
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-warning border-4 border-white dark:border-slate-900 transition-colors"></div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg mb-1 transition-colors">3. Penerimaan di Cabang Tujuan</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed transition-colors">
                            Admin Cabang Tujuan melakukan "Receive" (Penerimaan) pada Surat Jalan tersebut. Status semua paket di dalamnya otomatis menjadi <b class="text-slate-700 dark:text-slate-300">GUDANG TUJUAN</b>.
                        </p>
                    </div>
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-purple-500 border-4 border-white dark:border-slate-900 transition-colors"></div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg mb-1 transition-colors">4. Delivery (Pengiriman ke Alamat)</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed transition-colors">
                            Kurir cabang tujuan mengupdate status paket menjadi <b class="text-slate-700 dark:text-slate-300">DELIVERY</b> saat akan diantarkan ke rumah pelanggan. Ini dapat dilakukan via Aplikasi Kurir.
                        </p>
                    </div>
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-emerald-500 border-4 border-white dark:border-slate-900 transition-colors"></div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg mb-1 transition-colors">5. Selesai Terkirim</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed transition-colors">
                            Setelah pelanggan menerima barang, Kurir atau Admin mengubah status menjadi <b class="text-slate-700 dark:text-slate-300">SELESAI</b>. Sistem secara otomatis mencatat jejak (Audit Trail/Tracking).
                        </p>
                    </div>
                </div>
            </section>
            
            <hr class="border-slate-100 dark:border-slate-800">

            <!-- Keuangan -->
            <section id="finance" class="scroll-mt-28">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white transition-colors">Modul Keuangan</h2>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-6 transition-colors">
                        Modul ini merekam seluruh arus kas otomatis dari pembuatan paket dan juga transaksi manual lainnya.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-colors">
                            <div class="font-bold text-slate-700 dark:text-slate-200 mb-2 transition-colors"><i class="bi bi-cash text-emerald-500 mr-2"></i> CASH (Tunai)</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">Uang diterima di tempat saat pembuatan resi. Menambah saldo kas tunai.</p>
                        </div>
                        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-colors">
                            <div class="font-bold text-slate-700 dark:text-slate-200 mb-2 transition-colors"><i class="bi bi-bank text-blue-500 mr-2"></i> TRANSFER</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">Uang masuk via bank/transfer digital. Menambah saldo kas digital.</p>
                        </div>
                        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-colors">
                            <div class="font-bold text-slate-700 dark:text-slate-200 mb-2 transition-colors"><i class="bi bi-receipt text-orange-500 mr-2"></i> INVOICE / COD</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">Piutang yang belum dibayar. Akan tercatat di menu Piutang / B2B Invoice.</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <hr class="border-slate-100 dark:border-slate-800">

            <!-- Fitur Unggulan -->
            <section id="features" class="scroll-mt-28">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center text-xl">
                        <i class="bi bi-file-earmark-excel"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white transition-colors">Fitur Excel Import / Export</h2>
                </div>
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10">
                        <i class="bi bi-file-earmark-excel" style="font-size: 15rem;"></i>
                    </div>
                    <div class="relative z-10 max-w-2xl">
                        <h3 class="text-xl font-bold mb-4">Pengelolaan Data Massal yang Sangat Cepat</h3>
                        <p class="text-slate-300 text-sm leading-relaxed mb-6">
                            LANEXS mendukung pengolahan data menggunakan Microsoft Excel <code>.xlsx</code> secara natif berkat integrasi PhpSpreadsheet. 
                            Anda tidak perlu pusing menginput data satu per satu.
                        </p>
                        <ul class="space-y-3 text-sm text-slate-200">
                            <li><i class="bi bi-check-circle text-emerald-400 mr-2"></i> <b>Download Template:</b> Tersedia format template baku yang siap diisi.</li>
                            <li><i class="bi bi-check-circle text-emerald-400 mr-2"></i> <b>Preview & Validasi:</b> Sebelum disimpan, sistem akan menampilkan preview dan mengecek kesalahan (error handling).</li>
                            <li><i class="bi bi-check-circle text-emerald-400 mr-2"></i> <b>Tersedia Di Mana Saja:</b> Modul Karyawan, Pelanggan, Tarif, hingga Pembuatan Paket Massal.</li>
                            <li><i class="bi bi-check-circle text-emerald-400 mr-2"></i> <b>Export Rapih:</b> Export data didesain agar rapih (auto-size column, formatting mata uang) saat dicetak atau di-forward ke manajemen.</li>
                        </ul>
                    </div>
                </div>
            </section>
            
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$navbarWhite = true; // Use white navbar since hero is dark
require __DIR__ . '/layout.php';
?>
