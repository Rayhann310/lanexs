<?php
// struktur-organisasi.php
$navbarWhite = false;
$meta = ['icon' => 'bi-diagram-3', 'label' => 'Profil Perusahaan', 'color' => 'from-emerald-600 to-emerald-800'];

$settingModel = new \App\Models\Setting();
$hero_bg = $settingModel->get('page_struktur_organisasi_img', 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop');
$org_chart_style = $settingModel->get('org_chart_style', 'model_1');
$org_chart_data_json = $settingModel->get('org_chart_data', '[{"id":1,"title":"Logistics Director","parent_id":null}]');
$org_nodes = json_decode($org_chart_data_json, true) ?: [];

$org_team_data_json = $settingModel->get('org_team_data', '[]');
$org_team = json_decode($org_team_data_json, true) ?: [];

// Helper function to build tree HTML
if (!function_exists('buildOrgTree')) {
    function buildOrgTree($parentId, $nodes) {
        $children = array_filter($nodes, function($n) use ($parentId) {
            return $n['parent_id'] == $parentId;
        });
        
        if (empty($children)) return '';
        
        $html = '<ul>';
        foreach ($children as $child) {
            $html .= '<li>
                <div class="node-card">
                    <span class="node-title">' . htmlspecialchars($child['title']) . '</span>
                </div>' . buildOrgTree($child['id'], $nodes) . '
            </li>';
        }
        $html .= '</ul>';
        return $html;
    }
}

ob_start();
?>
<style>
/* CSS Tree Logic */
.org-tree * { margin: 0; padding: 0; box-sizing: border-box; }
.org-tree { display: flex; justify-content: center; width: max-content; margin: 0 auto; padding: 20px;}
.org-tree ul { padding-top: 20px; position: relative; transition: all 0.5s; display: flex; justify-content: center; }
.org-tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 20px 5px 0 5px; transition: all 0.5s; }
.org-tree li::before, .org-tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #94a3b8; width: 50%; height: 20px; }
.org-tree li::after { right: auto; left: 50%; border-left: 2px solid #94a3b8; }
.org-tree li:only-child::after, .org-tree li:only-child::before { display: none; }
.org-tree li:only-child { padding-top: 0; }
.org-tree li:first-child::before, .org-tree li:last-child::after { border: 0 none; }
.org-tree li:last-child::before { border-right: 2px solid #94a3b8; border-radius: 0 5px 0 0; }
.org-tree li:first-child::after { border-radius: 5px 0 0 0; }
.org-tree ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #94a3b8; width: 0; height: 20px; }
.org-tree .node-card { display: inline-block; padding: 16px 28px; text-decoration: none; transition: all 0.3s; min-width: 180px; }
.org-tree .node-title { font-size: 0.95rem; font-weight: 600; display: block; opacity: 0.95; letter-spacing: 0.02em;}

/* Styles */
/* Model 1: Corporate Blue */
.org-tree.model_1 .node-card { background: #0f172a; color: #fff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 2px solid #1e293b; }
.org-tree.model_1 .node-title { color: #f8fafc; }
/* Model 2: Modern Card */
.org-tree.model_2 .node-card { background: #fff; color: #334155; border-radius: 12px; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; border-top: 4px solid #3b82f6; }
.org-tree.model_2 .node-title { color: #475569; font-weight: 700;}
/* Model 3: Minimalist */
.org-tree.model_3 .node-card { background: transparent; color: #334155; border: 2px solid #94a3b8; border-radius: 0; }
.org-tree.model_3 .node-title { color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;}
.dark .org-tree.model_3 .node-title { color: #f8fafc; }

/* Responsive Mobile Tree (Vertical) applied to ALL screens to prevent horizontal scroll */
.org-tree { width: 100%; justify-content: flex-start; padding: 10px; overflow: hidden; }
.org-tree ul { display: block; padding-top: 0; }
.org-tree ul ul { margin-left: 20px; padding-left: 0; margin-top: 15px; }
.org-tree li { float: none; text-align: left; padding: 15px 0 0 24px; position: relative; }

/* Hide all desktop horizontal lines */
.org-tree li::before, .org-tree li::after, .org-tree ul ul::before { display: none !important; border: none !important; }

/* Draw L-shape connectors for children */
.org-tree ul ul > li::before {
    content: ''; position: absolute; top: -15px; left: 0;
    width: 24px; height: 50px;
    border-left: 2px solid #94a3b8 !important;
    border-bottom: 2px solid #94a3b8 !important;
    display: block !important;
    border-radius: 0 0 0 8px;
}

/* Vertical continuation line for siblings */
.org-tree ul ul > li::after {
    content: ''; position: absolute; top: 35px; left: 0;
    bottom: -15px;
    border-left: 2px solid #94a3b8 !important;
    display: block !important;
}
/* Hide downward line for the last child */
.org-tree ul ul > li:last-child::after { display: none !important; }

.org-tree .node-card { width: 100%; max-width: 320px; min-width: 0; display: block; padding: 14px 20px; font-size: 1rem; }

/* Remove padding for root element so it's flush left */
.org-tree > ul > li { padding-left: 0; }
</style>

<!-- Page Hero Banner -->
<section class="pt-32 pb-24 bg-white dark:bg-slate-900 relative overflow-hidden transition-colors border-b border-slate-200 dark:border-slate-800">
    <img src="<?= htmlspecialchars($hero_bg) ?>" class="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-20 mix-blend-overlay">
    <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent dark:from-slate-900 dark:via-slate-900/80 transition-colors"></div>
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Breadcrumb -->
        <nav class="flex items-center justify-center space-x-2 text-slate-500 dark:text-white/60 text-sm mb-8 font-medium" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-primary dark:hover:text-white transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-800 dark:text-white font-bold"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div data-aos="fade-up" class="max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center space-x-2 bg-primary/10 border border-primary/20 rounded-full px-4 py-1.5 mb-6">
                <i class="bi <?= $meta['icon'] ?> text-primary"></i>
                <span class="text-primary font-semibold text-sm tracking-widest uppercase"><?= htmlspecialchars($meta['label']) ?></span>
            </div>
            <h1 class="text-3xl md:text-5xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-4 transition-colors">
                <?= htmlspecialchars($page['title']) ?>
            </h1>
            <p class="text-base md:text-lg text-slate-600 dark:text-slate-300 font-light transition-colors">Pondasi kuat di balik layanan logistik prima kami.</p>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="py-20 bg-slate-50 dark:bg-slate-900 transition-colors relative">
    <div class="absolute inset-0 opacity-40 dark:opacity-20 pointer-events-none" style="background-image: radial-gradient(var(--tw-gradient-stops));"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="max-w-5xl mx-auto">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-primary/5 dark:shadow-none border border-white/50 dark:border-slate-700/50 p-8 md:p-12 transition-all hover:shadow-2xl hover:-translate-y-1 duration-500 relative overflow-hidden group" data-aos="fade-up">
                <!-- Decorative element -->
                <div class="absolute -top-32 -right-32 w-64 h-64 bg-primary/20 rounded-full blur-3xl pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-secondary/20 rounded-full blur-3xl pointer-events-none group-hover:scale-150 transition-transform duration-700 delay-100"></div>
                
                <div class="relative z-10 text-left pb-8">
                    <?php if (!empty($org_nodes)): ?>
                        <div class="org-tree <?= htmlspecialchars($org_chart_style) ?> w-full md:w-2/3 mx-auto">
                            <ul>
                                <?php 
                                // Find roots
                                $roots = array_filter($org_nodes, function($n) use ($org_nodes) {
                                    $hasParent = false;
                                    foreach($org_nodes as $p) {
                                        if($p['id'] == $n['parent_id']) $hasParent = true;
                                    }
                                    return empty($n['parent_id']) || !$hasParent;
                                });
                                
                                foreach($roots as $root): ?>
                                    <li>
                                        <div class="node-card">
                                            <span class="node-title"><?= htmlspecialchars($root['title']) ?></span>
                                        </div>
                                        <?= buildOrgTree($root['id'], $org_nodes) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16 px-4 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl mb-8">
                            <i class="bi bi-diagram-3 text-5xl text-slate-300 dark:text-slate-600 mb-4 block"></i>
                            <h3 class="text-lg font-bold text-slate-700 dark:text-slate-300 mb-2">Bagan Struktur Belum Tersedia</h3>
                            <p class="text-slate-500 dark:text-slate-400">Silakan buat struktur organisasi melalui halaman admin.</p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($page['content'])): ?>
                        <div class="prose-content dark:text-slate-300 text-left mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                            <?= $page['content'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Admin Quick Edit Bar -->
            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <div class="mt-8 flex justify-center">
                <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-6 py-3 bg-slate-800 hover:bg-slate-900 dark:bg-primary dark:hover:bg-primaryHover text-white text-sm font-bold rounded-xl shadow-md transition-all hover:scale-105">
                    <i class="bi bi-pencil-fill mr-2"></i> Pengaturan Struktur
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- Our Team Section -->
<?php if (!empty($org_team)): ?>
<section class="py-24 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 transition-colors">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 dark:text-white mb-4">Our Team</h2>
            <div class="w-20 h-1 bg-primary mx-auto rounded-full mb-6"></div>
            <p class="text-slate-600 dark:text-slate-400">Orang-orang hebat di balik operasional logistik kami.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($org_team as $member): 
                $photo = $settingModel->get('org_team_photo_' . $member['id'], 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=0D8ABC&color=fff&size=256');
            ?>
            <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 dark:border-slate-700 transform hover:-translate-y-2" data-aos="fade-up">
                <div class="aspect-w-1 aspect-h-1 w-full bg-slate-100 dark:bg-slate-700 overflow-hidden relative">
                    <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($member['name']) ?>" class="w-full h-64 object-cover object-top group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1"><?= htmlspecialchars($member['name']) ?></h3>
                    <p class="text-sm font-medium text-primary"><?= htmlspecialchars($member['title']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
