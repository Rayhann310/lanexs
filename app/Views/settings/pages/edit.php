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

    <form action="<?= BASE_URL ?>/settings/pages/update/<?= $page['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Halaman</label>
                <input type="text" name="title" value="<?= htmlspecialchars($page['title']) ?>"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all font-medium text-slate-800"
                    required>
            </div>

            <?php if (!in_array($page['slug'], ['visi-misi', 'struktur-organisasi'])): ?>
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
                <input type="hidden" name="content" id="quill-content" value="<?= htmlspecialchars($page['content']) ?>">
            </div>
            <?php else: ?>
                <!-- Preserve existing content so it doesn't get wiped -->
                <input type="hidden" name="content" id="quill-content" value="<?= htmlspecialchars($page['content']) ?>">
            <?php endif; ?>

            <?php if ($page['slug'] === 'sejarah-perusahaan'): ?>
                <?php
                $settingModel = new \App\Models\Setting();
                $hero_bg = $settingModel->get('sejarah_hero_bg', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop');
                $cta_bg = $settingModel->get('sejarah_cta_bg', 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?q=80&w=2075&auto=format&fit=crop');
                $stat_branches = $settingModel->get('sejarah_stat_branches', '150+');
                $stat_packages = $settingModel->get('sejarah_stat_packages', '5M+');
                $stat_cities = $settingModel->get('sejarah_stat_cities', '38');
                $m1_year = $settingModel->get('sejarah_m1_year', '2025');
                $m1_title = $settingModel->get('sejarah_m1_title', 'Peresmian LANEXS');
                $m1_desc = $settingModel->get('sejarah_m1_desc', 'Didirikan di Bekasi dengan visi menjadi pilar logistik Indonesia.');
                $m2_year = $settingModel->get('sejarah_m2_year', '2026');
                $m2_title = $settingModel->get('sejarah_m2_title', 'Ekspansi Darat & Laut');
                $m2_desc = $settingModel->get('sejarah_m2_desc', 'Membuka rute ke seluruh pulau Jawa dan Sumatera.');
                $m3_year = $settingModel->get('sejarah_m3_year', '2028');
                $m3_title = $settingModel->get('sejarah_m3_title', 'Jaringan Nasional');
                $m3_desc = $settingModel->get('sejarah_m3_desc', 'Menjangkau 38 Provinsi dengan teknologi pelacakan real-time mutakhir.');
                ?>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="bi bi-sliders mr-2 text-primary"></i> Pengaturan Khusus (Sejarah Perusahaan)
                    </h3>
                    
                    <h4 class="font-semibold text-slate-700 mb-3 border-b pb-2">Gambar Background</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Banner Utama (URL/Upload)</label>
                            <?php if ($hero_bg): ?>
                                <img src="<?= htmlspecialchars($hero_bg) ?>" class="h-24 w-full object-cover rounded-lg mb-2 shadow-sm border border-slate-200">
                            <?php endif; ?>
                            <input type="file" name="sejarah_hero_bg" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah gambar saat ini.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Banner CTA Bawah (URL/Upload)</label>
                            <?php if ($cta_bg): ?>
                                <img src="<?= htmlspecialchars($cta_bg) ?>" class="h-24 w-full object-cover rounded-lg mb-2 shadow-sm border border-slate-200">
                            <?php endif; ?>
                            <input type="file" name="sejarah_cta_bg" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah gambar saat ini.</p>
                        </div>
                    </div>

                    <h4 class="font-semibold text-slate-700 mb-3 border-b pb-2">Statistik Pencapaian</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Total Cabang</label>
                            <input type="text" name="sejarah_stat_branches" value="<?= htmlspecialchars($stat_branches) ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Paket Terkirim</label>
                            <input type="text" name="sejarah_stat_packages" value="<?= htmlspecialchars($stat_packages) ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Provinsi Jangkauan</label>
                            <input type="text" name="sejarah_stat_cities" value="<?= htmlspecialchars($stat_cities) ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg outline-none focus:border-primary">
                        </div>
                    </div>

                    <h4 class="font-semibold text-slate-700 mb-3 border-b pb-2">Tonggak Sejarah (Timeline)</h4>
                    <div class="space-y-6">
                        <!-- M1 -->
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Tahun ke-1</label>
                                    <input type="text" name="sejarah_m1_year" value="<?= htmlspecialchars($m1_year) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div class="md:col-span-10">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Judul Momen</label>
                                    <input type="text" name="sejarah_m1_title" value="<?= htmlspecialchars($m1_title) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mb-2">
                                    
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Deskripsi Momen</label>
                                    <textarea name="sejarah_m1_desc" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"><?= htmlspecialchars($m1_desc) ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- M2 -->
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Tahun ke-2</label>
                                    <input type="text" name="sejarah_m2_year" value="<?= htmlspecialchars($m2_year) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div class="md:col-span-10">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Judul Momen</label>
                                    <input type="text" name="sejarah_m2_title" value="<?= htmlspecialchars($m2_title) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mb-2">
                                    
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Deskripsi Momen</label>
                                    <textarea name="sejarah_m2_desc" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"><?= htmlspecialchars($m2_desc) ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- M3 -->
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Tahun ke-3</label>
                                    <input type="text" name="sejarah_m3_year" value="<?= htmlspecialchars($m3_year) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div class="md:col-span-10">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Judul Momen</label>
                                    <input type="text" name="sejarah_m3_title" value="<?= htmlspecialchars($m3_title) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mb-2">
                                    
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Deskripsi Momen</label>
                                    <textarea name="sejarah_m3_desc" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"><?= htmlspecialchars($m3_desc) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($page['slug'] === 'visi-misi'): ?>
                <?php
                $settingModel = new \App\Models\Setting();
                $hero_bg = $settingModel->get('page_visi_misi_img', 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop');
                
                // Fallback text if null
                $def_visi = 'Menjadi mitra ekspedisi andalan masyarakat Indonesia dengan menghadirkan layanan logistik yang andal, inovatif, dan berdaya saing tinggi.';
                $def_m1 = 'Mendukung pemerataan ekonomi nasional melalui jangkauan yang luas;';
                $def_m2 = 'Menjaga hubungan baik, profesional dan responsif dengan pelanggan;';
                $def_m3 = 'Mengembangkan teknologi modern untuk mempermudah pelacakan dan pengelolaan pengiriman barang;';
                $def_m4 = 'Memberikan pelayanan pengiriman yang cepat, aman, dan terpercaya ke seluruh penjuru Nusantara;';

                $visi_text = $settingModel->get('vm_visi', $def_visi);
                $m1 = $settingModel->get('vm_m1', $def_m1);
                $m2 = $settingModel->get('vm_m2', $def_m2);
                $m3 = $settingModel->get('vm_m3', $def_m3);
                $m4 = $settingModel->get('vm_m4', $def_m4);
                ?>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="bi bi-sliders mr-2 text-primary"></i> Pengaturan Khusus (Visi & Misi)
                    </h3>
                    
                    <h4 class="font-semibold text-slate-700 mb-3 border-b pb-2">Gambar Background Header</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <?php if ($hero_bg): ?>
                                <img src="<?= htmlspecialchars($hero_bg) ?>" class="h-24 w-full object-cover rounded-lg mb-2 shadow-sm border border-slate-200">
                            <?php endif; ?>
                            <input type="file" name="page_visi_misi_img" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah gambar saat ini.</p>
                        </div>
                    </div>

                    <h4 class="font-semibold text-slate-700 mb-3 border-b pb-2">Teks Visi Utama</h4>
                    <div class="mb-8">
                        <textarea name="vm_visi" rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-slate-700"><?= htmlspecialchars($visi_text) ?></textarea>
                    </div>

                    <h4 class="font-semibold text-slate-700 mb-3 border-b pb-2">Poin-poin Misi</h4>
                    <div class="space-y-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Misi 1</label>
                            <input type="text" name="vm_m1" value="<?= htmlspecialchars($m1) ?>" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none focus:border-primary text-slate-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Misi 2</label>
                            <input type="text" name="vm_m2" value="<?= htmlspecialchars($m2) ?>" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none focus:border-primary text-slate-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Misi 3</label>
                            <input type="text" name="vm_m3" value="<?= htmlspecialchars($m3) ?>" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none focus:border-primary text-slate-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Misi 4</label>
                            <input type="text" name="vm_m4" value="<?= htmlspecialchars($m4) ?>" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none focus:border-primary text-slate-700">
                        </div>
                    </div>
                </div>
            <?php elseif ($page['slug'] === 'struktur-organisasi'): ?>
                <?php
                $settingModel = new \App\Models\Setting();
                $hero_bg = $settingModel->get('page_struktur_organisasi_img', 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop');
                $org_chart_style = $settingModel->get('org_chart_style', 'model_1');
                $org_chart_data = $settingModel->get('org_chart_data', '[{"id":1,"name":"John Doe","title":"Logistics Director","parent_id":null}]');
                ?>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="bi bi-diagram-3 mr-2 text-primary"></i> Pengaturan Struktur Organisasi
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">Gambar Background Header</label>
                            <?php if ($hero_bg): ?>
                                <img src="<?= htmlspecialchars($hero_bg) ?>" class="h-24 w-full md:w-1/2 object-cover rounded-lg mb-2 shadow-sm border border-slate-200">
                            <?php endif; ?>
                            <input type="file" name="page_struktur_organisasi_img" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        </div>
                    </div>

                    <!-- Dynamic Org Chart Builder -->
                    <div class="border border-slate-200 bg-white rounded-xl overflow-hidden shadow-sm">
                        <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                            <h4 class="font-bold text-slate-700">Builder Bagan Struktur</h4>
                            <select name="org_chart_style" id="orgChartStyle" class="text-sm px-3 py-1.5 border border-slate-300 rounded-lg outline-none focus:border-primary">
                                <option value="model_1" <?= $org_chart_style === 'model_1' ? 'selected' : '' ?>>Model 1 (Corporate Blue)</option>
                                <option value="model_2" <?= $org_chart_style === 'model_2' ? 'selected' : '' ?>>Model 2 (Modern Card)</option>
                                <option value="model_3" <?= $org_chart_style === 'model_3' ? 'selected' : '' ?>>Model 3 (Minimalist Minimal)</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-200">
                            <!-- Node Editor List -->
                            <div class="p-4 lg:col-span-1 bg-slate-50/30 max-h-[500px] overflow-y-auto">
                                <div class="flex justify-between items-center mb-4">
                                    <h5 class="text-sm font-semibold text-slate-600">Daftar Jabatan</h5>
                                    <button type="button" id="btnAddNode" class="px-3 py-1 bg-primary text-white text-xs font-semibold rounded hover:bg-primaryHover transition-colors"><i class="bi bi-plus-lg"></i> Tambah</button>
                                </div>
                                <div id="nodeList" class="space-y-3">
                                    <!-- Nodes will be injected here via JS -->
                                </div>
                            </div>

                            <!-- Preview Area -->
                            <div class="p-6 lg:col-span-2 overflow-x-auto min-h-[400px] bg-[#f8fafc] flex justify-center">
                                <div id="orgChartPreview" class="org-tree w-full">
                                    <!-- Tree will be rendered here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="org_chart_data" id="orgChartData" value="<?= htmlspecialchars($org_chart_data) ?>">
                </div>
                
                <?php $org_team_data = $settingModel->get('org_team_data', '[]'); ?>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50 mt-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="bi bi-people mr-2 text-primary"></i> Pengaturan Anggota Tim (Our Team)
                    </h3>
                    
                    <div class="border border-slate-200 bg-white rounded-xl overflow-hidden shadow-sm">
                        <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                            <h4 class="font-bold text-slate-700">Daftar Anggota</h4>
                            <button type="button" id="btnAddTeam" class="px-3 py-1.5 bg-primary text-white text-xs font-semibold rounded-lg hover:bg-primaryHover transition-colors"><i class="bi bi-person-plus"></i> Tambah Anggota</button>
                        </div>
                        
                        <div class="p-6">
                            <div id="teamList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Team members injected via JS -->
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="org_team_data" id="orgTeamData" value="<?= htmlspecialchars($org_team_data) ?>">
                </div>

<style>
/* CSS Tree Logic for Preview */
.org-tree * { margin: 0; padding: 0; box-sizing: border-box; }
.org-tree { display: flex; justify-content: center; width: max-content; margin: 0 auto; padding: 20px;}
.org-tree ul { padding-top: 20px; position: relative; transition: all 0.5s; display: flex; justify-content: center; }
.org-tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 20px 5px 0 5px; transition: all 0.5s; }
.org-tree li::before, .org-tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #cbd5e1; width: 50%; height: 20px; }
.org-tree li::after { right: auto; left: 50%; border-left: 2px solid #cbd5e1; }
.org-tree li:only-child::after, .org-tree li:only-child::before { display: none; }
.org-tree li:only-child { padding-top: 0; }
.org-tree li:first-child::before, .org-tree li:last-child::after { border: 0 none; }
.org-tree li:last-child::before { border-right: 2px solid #cbd5e1; border-radius: 0 5px 0 0; }
.org-tree li:first-child::after { border-radius: 5px 0 0 0; }
.org-tree ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #cbd5e1; width: 0; height: 20px; }
.org-tree .node-card { display: inline-block; padding: 12px 20px; text-decoration: none; transition: all 0.3s; min-width: 140px; }
.org-tree .node-name { font-weight: 700; font-size: 0.95rem; display: block; margin-bottom: 2px; }
.org-tree .node-title { font-size: 0.8rem; display: block; opacity: 0.9;}

/* Styles */
/* Model 1: Corporate Blue */
.org-tree.model_1 .node-card { background: #0f172a; color: #fff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 2px solid #1e293b; }
.org-tree.model_1 .node-title { color: #94a3b8; }
/* Model 2: Modern Card */
.org-tree.model_2 .node-card { background: #fff; color: #334155; border-radius: 12px; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; border-top: 4px solid #3b82f6; }
.org-tree.model_2 .node-title { color: #64748b; font-weight: 500;}
/* Model 3: Minimalist */
.org-tree.model_3 .node-card { background: transparent; color: #334155; border: 1px solid #cbd5e1; border-radius: 0; }
.org-tree.model_3 .node-name { color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em;}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const rawData = document.getElementById('orgChartData').value;
    let nodes = [];
    try { nodes = JSON.parse(rawData); } catch(e) { nodes = []; }
    if(!Array.isArray(nodes) || nodes.length === 0) {
        nodes = [{id: 1, name: "Nama", title: "Jabatan", parent_id: null}];
    }

    const nodeListEl = document.getElementById('nodeList');
    const previewEl = document.getElementById('orgChartPreview');
    const dataInput = document.getElementById('orgChartData');
    const styleSelect = document.getElementById('orgChartStyle');

    function renderNodeList() {
        nodeListEl.innerHTML = '';
        nodes.forEach(node => {
            const div = document.createElement('div');
            div.className = 'bg-white p-3 border border-slate-200 rounded-lg shadow-sm text-sm relative group';
            
            // Delete button (prevent deleting the root node if it's the only one)
            let deleteBtn = '';
            if(nodes.length > 1) {
                deleteBtn = `<button type="button" class="absolute top-2 right-2 text-red-400 hover:text-red-600 hidden group-hover:block" onclick="deleteNode(${node.id})"><i class="bi bi-trash"></i></button>`;
            }

            // Options for parent
            let parentOpts = `<option value="">-- Tidak ada (Root) --</option>`;
            nodes.forEach(n => {
                if(n.id !== node.id) {
                    parentOpts += `<option value="${n.id}" ${node.parent_id == n.id ? 'selected' : ''}>${n.name} (${n.title})</option>`;
                }
            });

            div.innerHTML = `
                ${deleteBtn}
                <div class="mb-2">
                    <label class="block text-xs text-slate-500 mb-1">Jabatan</label>
                    <input type="text" class="w-full border-b border-slate-300 outline-none px-1 py-0.5 focus:border-primary" value="${node.title}" onchange="updateNode(${node.id}, 'title', this.value)">
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">Atasan</label>
                    <select class="w-full border-b border-slate-300 outline-none px-1 py-0.5 focus:border-primary" onchange="updateNode(${node.id}, 'parent_id', this.value)">
                        ${parentOpts}
                    </select>
                </div>
            `;
            nodeListEl.appendChild(div);
        });
    }

    window.updateNode = function(id, field, value) {
        const node = nodes.find(n => n.id == id);
        if(node) {
            node[field] = field === 'parent_id' ? (value ? parseInt(value) : null) : value;
            saveAndRender();
        }
    };

    window.deleteNode = function(id) {
        if(confirm('Hapus posisi ini? Posisi di bawahnya (jika ada) akan kehilangan atasan.')) {
            nodes = nodes.filter(n => n.id != id);
            // Reset parent_id for orphaned children
            nodes.forEach(n => { if(n.parent_id == id) n.parent_id = null; });
            saveAndRender();
        }
    };

    document.getElementById('btnAddNode').addEventListener('click', () => {
        const newId = nodes.length > 0 ? Math.max(...nodes.map(n => n.id)) + 1 : 1;
        // Default parent to the first node
        const defaultParent = nodes.length > 0 ? nodes[0].id : null;
        nodes.push({id: newId, title: "Jabatan Baru", parent_id: defaultParent});
        saveAndRender();
    });

    styleSelect.addEventListener('change', () => {
        saveAndRender();
    });

    function buildTreeHTML(parentId) {
        const children = nodes.filter(n => n.parent_id == parentId);
        if(children.length === 0) return '';
        let html = '<ul>';
        children.forEach(child => {
            html += `<li>
                <div class="node-card">
                    <span class="node-title">${child.title}</span>
                </div>
                ${buildTreeHTML(child.id)}
            </li>`;
        });
        html += '</ul>';
        return html;
    }

    function saveAndRender() {
        dataInput.value = JSON.stringify(nodes);
        
        // Find roots (nodes with no valid parent)
        const roots = nodes.filter(n => !n.parent_id || !nodes.find(p => p.id == n.parent_id));
        
        let previewHTML = '';
        if(roots.length > 0) {
            previewHTML = '<ul>';
            roots.forEach(root => {
                previewHTML += `<li>
                    <div class="node-card">
                        <span class="node-title">${root.title}</span>
                    </div>
                    ${buildTreeHTML(root.id)}
                </li>`;
            });
            previewHTML += '</ul>';
        } else {
            previewHTML = '<div class="text-slate-400 mt-10">Tidak ada data.</div>';
        }

        previewEl.innerHTML = previewHTML;
        previewEl.className = 'org-tree w-full ' + styleSelect.value;
        
        // Only re-render node list to update parent dropdowns if needed, but it causes input blur.
        // For simplicity, we re-render everything when a node is added/deleted or parent changed.
        // We handle input changes via onchange which fires on blur.
        renderNodeList();
    }

    // Initial render
    saveAndRender();

    // ==========================================
    // TEAM BUILDER LOGIC
    // ==========================================
    const rawTeamData = document.getElementById('orgTeamData').value;
    let team = [];
    try { team = JSON.parse(rawTeamData); } catch(e) { team = []; }

    const teamListEl = document.getElementById('teamList');
    const teamDataInput = document.getElementById('orgTeamData');

    function renderTeamList() {
        teamListEl.innerHTML = '';
        if(team.length === 0) {
            teamListEl.innerHTML = '<div class="col-span-full text-center py-8 text-slate-400">Belum ada anggota tim. Klik Tambah Anggota.</div>';
        }
        team.forEach(member => {
            const div = document.createElement('div');
            div.className = 'bg-slate-50 p-4 border border-slate-200 rounded-xl relative group';
            
            div.innerHTML = `
                <button type="button" class="absolute -top-2 -right-2 bg-red-100 text-red-500 hover:bg-red-500 hover:text-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm transition-colors" onclick="deleteTeam(${member.id})"><i class="bi bi-x-lg text-sm"></i></button>
                
                <div class="mb-3 text-center">
                    <label class="block text-xs font-medium text-slate-500 mb-2">Foto Profil (Opsional)</label>
                    <input type="file" name="org_team_photo_${member.id}" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-200 hover:file:bg-slate-300 transition-colors">
                    <p class="text-[10px] text-slate-400 mt-1">Upload gambar baru untuk menimpa foto lama jika ada.</p>
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Nama Lengkap</label>
                    <input type="text" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm focus:border-primary outline-none" value="${member.name}" onchange="updateTeam(${member.id}, 'name', this.value)" placeholder="Misal: Budi Santoso">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Jabatan</label>
                    <input type="text" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm focus:border-primary outline-none" value="${member.title}" onchange="updateTeam(${member.id}, 'title', this.value)" placeholder="Misal: Manager Operasional">
                </div>
            `;
            teamListEl.appendChild(div);
        });
    }

    window.updateTeam = function(id, field, value) {
        const member = team.find(m => m.id == id);
        if(member) {
            member[field] = value;
            teamDataInput.value = JSON.stringify(team);
        }
    };

    window.deleteTeam = function(id) {
        if(confirm('Hapus anggota tim ini?')) {
            team = team.filter(m => m.id != id);
            teamDataInput.value = JSON.stringify(team);
            renderTeamList();
        }
    };

    document.getElementById('btnAddTeam').addEventListener('click', () => {
        const newId = team.length > 0 ? Math.max(...team.map(m => m.id)) + 1 : 1;
        team.push({id: newId, name: "", title: ""});
        teamDataInput.value = JSON.stringify(team);
        renderTeamList();
    });

    // Initial render team
    renderTeamList();
});
</script>
            <?php elseif (in_array($page['slug'], ['layanan-pengiriman', 'layanan-pengemasan', 'layanan-tracking', 'experience'])): ?>
                <?php
                $settingModel = new \App\Models\Setting();
                $prefix = 'page_' . str_replace('-', '_', $page['slug']);
                
                $defaultImg = 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop';
                $defaultSubtitle = 'Layanan Kami';
                $defaultTitle = 'Kualitas Terbaik';
                
                if ($page['slug'] == 'layanan-pengiriman') { $defaultImg = 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop'; $defaultSubtitle = 'Jangkauan seluruh nusantara'; $defaultTitle = 'Cepat & Aman'; }
                if ($page['slug'] == 'layanan-pengemasan') { $defaultImg = 'https://images.unsplash.com/photo-1577705998148-6da4f3963bc8?q=80&w=2070&auto=format&fit=crop'; $defaultSubtitle = 'Perlindungan maksimal'; $defaultTitle = 'Kemasan Kuat'; }
                if ($page['slug'] == 'layanan-tracking') { $defaultImg = 'https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=1470&auto=format&fit=crop'; $defaultSubtitle = 'Informasi 24/7'; $defaultTitle = 'Pantau Real-time'; }
                if ($page['slug'] == 'experience') { $defaultImg = 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070&auto=format&fit=crop'; $defaultSubtitle = 'Portofolio kami'; $defaultTitle = 'Pengalaman Teruji'; }
                if ($page['slug'] == 'visi-misi') { $defaultImg = 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop'; }
                if ($page['slug'] == 'struktur-organisasi') { $defaultImg = 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop'; }
                
                $img = $settingModel->get($prefix . '_img', $defaultImg);
                $subtitle = $settingModel->get($prefix . '_subtitle', $defaultSubtitle);
                $title = $settingModel->get($prefix . '_title', $defaultTitle);
                
                $isHeroOnly = in_array($page['slug'], ['visi-misi', 'struktur-organisasi']);
                ?>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="bi bi-image mr-2 text-primary"></i> Pengaturan Grafis Halaman
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">
                                <?= $isHeroOnly ? 'Gambar Background Header (Upload)' : 'Gambar Layout Samping (Upload)' ?>
                            </label>
                            <?php if ($img): ?>
                                <img src="<?= htmlspecialchars($img) ?>" class="h-24 w-full object-cover rounded-lg mb-2 shadow-sm border border-slate-200">
                            <?php endif; ?>
                            <input type="file" name="<?= $prefix ?>_img" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <p class="text-xs text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                        </div>
                    </div>
                    <?php if (!$isHeroOnly): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Judul Gambar</label>
                            <input type="text" name="<?= $prefix ?>_title" value="<?= htmlspecialchars($title) ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Sub-judul Gambar</label>
                            <input type="text" name="<?= $prefix ?>_subtitle" value="<?= htmlspecialchars($subtitle) ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg outline-none focus:border-primary">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="px-6 pb-6 pt-4 flex items-center space-x-4 border-t border-slate-100">
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
