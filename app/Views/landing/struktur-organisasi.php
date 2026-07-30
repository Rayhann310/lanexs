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
/* ============================================================
   STRUKTUR ORGANISASI — PREMIUM REDESIGN
   ============================================================ */

/* --- HERO BANNER OVERRIDES --- */
.so-hero {
    position: relative;
    padding: 7rem 0 5rem;
    background: #ffffff;
    overflow: hidden;
}
.dark .so-hero { background: #0f172a; }
.so-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(0,0,0,0.06) 1px, transparent 1px);
    background-size: 32px 32px;
}
.dark .so-hero::before { background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px); }
.so-hero-glow {
    position: absolute;
    border-radius: 9999px;
    filter: blur(80px);
    opacity: 0.12;
    pointer-events: none;
}

/* --- CHART SECTION --- */
.so-chart-section {
    background: #f8fafc;
    padding: 5rem 0 6rem;
    position: relative;
    overflow: hidden;
}
.dark .so-chart-section { background: #0f172a; }
.so-chart-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(59,130,246,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59,130,246,0.06) 1px, transparent 1px);
    background-size: 48px 48px;
}
.so-chart-section::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #06b6d4, #8b5cf6, #3b82f6);
    background-size: 200% auto;
    animation: gradientShift 4s linear infinite;
}
@keyframes gradientShift {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

/* Chart scroll wrapper */
.chart-scroll-wrapper {
    overflow-x: auto;
    overflow-y: visible;
    padding: 2rem 1rem 3rem;
    cursor: grab;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(56,189,248,0.4) rgba(255,255,255,0.05);
}
.chart-scroll-wrapper::-webkit-scrollbar { height: 6px; }
.chart-scroll-wrapper::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 99px; }
.chart-scroll-wrapper::-webkit-scrollbar-thumb { background: rgba(59,130,246,0.4); border-radius: 99px; }
.chart-scroll-wrapper:active { cursor: grabbing; }

/* --- ORG TREE CSS (Horizontal, True Tree) --- */
.org-tree { 
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    width: max-content;
    min-width: 100%;
}
.org-tree ul { 
    padding-top: 28px; 
    position: relative; 
    display: flex; 
    justify-content: center; 
    gap: 16px;
}
.org-tree li { 
    text-align: center; 
    list-style-type: none; 
    position: relative; 
    padding: 28px 8px 0 8px; 
    transition: all 0.3s; 
}
/* Horizontal connector lines */
.org-tree li::before, .org-tree li::after { 
    content: ''; 
    position: absolute; 
    top: 0; right: 50%; 
    border-top: 2px solid #cbd5e1; 
    width: 50%; 
    height: 28px; 
}
.org-tree li::after { 
    right: auto; left: 50%; 
    border-left: 2px solid #cbd5e1; 
}
.org-tree li:only-child::after, .org-tree li:only-child::before { display: none; }
.org-tree li:only-child { padding-top: 0; }
.org-tree li:first-child::before, .org-tree li:last-child::after { border: 0 none; }
.org-tree li:last-child::before { border-right: 2px solid #cbd5e1; border-radius: 0 6px 0 0; }
.org-tree li:first-child::after { border-radius: 6px 0 0 0; }
.org-tree ul ul::before { 
    content: ''; 
    position: absolute; 
    top: 0; left: 50%; 
    border-left: 2px solid #cbd5e1; 
    width: 0; 
    height: 28px; 
}
.dark .org-tree li::before,.dark .org-tree li::after { border-color: rgba(56,189,248,0.3); }
.dark .org-tree li:last-child::before { border-color: rgba(56,189,248,0.3); }
.dark .org-tree ul ul::before { border-color: rgba(56,189,248,0.3); }

/* Node Card Base */
.org-tree .node-card { 
    display: inline-block; 
    padding: 14px 24px; 
    text-decoration: none; 
    transition: all 0.3s; 
    min-width: 160px;
    cursor: default;
}
.org-tree .node-title { 
    font-size: 0.9rem; 
    font-weight: 700; 
    display: block; 
    letter-spacing: 0.04em;
    line-height: 1.4;
}

/* Model 1: Corporate Navy */
.org-tree.model_1 .node-card { 
    background: #f8fafc; 
    color: #0f172a; 
    border-radius: 10px; 
    box-shadow: 0 2px 8px rgba(0,0,0,0.08), 0 0 0 1px #e2e8f0;
    border: 1px solid #e2e8f0;
}
.org-tree.model_1 .node-card:hover { 
    border-color: #3b82f6;
    box-shadow: 0 4px 20px rgba(59,130,246,0.15);
    transform: translateY(-3px);
}
.org-tree.model_1 .node-title { color: #1e293b; }
.dark .org-tree.model_1 .node-card { background: rgba(15,23,42,0.8); border-color: rgba(56,189,248,0.3); box-shadow: 0 0 0 1px rgba(56,189,248,0.3), 0 8px 24px rgba(0,0,0,0.4); }
.dark .org-tree.model_1 .node-title { color: #e0f2fe; }

/* Model 2: Blue Accent */
.org-tree.model_2 .node-card { 
    background: #ffffff; 
    color: #1e293b; 
    border-radius: 14px; 
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    border-top: 4px solid #3b82f6;
}
.org-tree.model_2 .node-card:hover { 
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(59,130,246,0.15);
}
.org-tree.model_2 .node-title { color: #334155; font-weight: 700;}
.dark .org-tree.model_2 .node-card { background: rgba(15,23,42,0.8); border-top-color: #3b82f6; border-color: rgba(99,179,237,0.2); }
.dark .org-tree.model_2 .node-title { color: #bfdbfe; }

/* Model 3: Minimal Line */
.org-tree.model_3 .node-card { 
    background: transparent; 
    color: #334155; 
    border: 2px solid #cbd5e1;
    border-radius: 6px;
}
.org-tree.model_3 .node-card:hover { 
    border-color: #3b82f6;
    background: #eff6ff;
}
.org-tree.model_3 .node-title { color: #1e293b; text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.8rem; }
.dark .org-tree.model_3 .node-card { border-color: rgba(251,191,36,0.5); }
.dark .org-tree.model_3 .node-card:hover { background: rgba(251,191,36,0.1); border-color: #fbbf24; }
.dark .org-tree.model_3 .node-title { color: #fde68a; }

/* Hint text animation */
.swipe-hint { animation: bounce-x 2s ease-in-out infinite; display: inline-flex; align-items: center; gap: 8px; }
@keyframes bounce-x { 0%,100%{transform:translateX(0)} 50%{transform:translateX(6px)} }
</style>

<!-- ================================================
     HERO SECTION (Light Immersive)
     ================================================ -->
<section class="so-hero">
    <!-- Glow orbs (visible on both light and dark) -->
    <div class="so-hero-glow" style="width:500px;height:500px;background:#3b82f6;top:-200px;right:-100px;"></div>
    <div class="so-hero-glow" style="width:400px;height:400px;background:#8b5cf6;bottom:-150px;left:-100px;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Breadcrumb -->
        <nav class="flex items-center justify-center space-x-2 text-slate-400 dark:text-white/40 text-sm mb-8 font-medium" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/" class="hover:text-primary dark:hover:text-white/80 transition-colors">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-700 dark:text-white/80 font-bold"><?= htmlspecialchars($page['title']) ?></span>
        </nav>

        <div data-aos="fade-up" class="max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center space-x-2 bg-primary/10 border border-primary/20 rounded-full px-4 py-1.5 mb-6">
                <i class="bi bi-diagram-3 text-primary"></i>
                <span class="text-primary font-semibold text-sm tracking-widest uppercase"><?= htmlspecialchars($meta['label']) ?></span>
            </div>
            <h1 class="text-4xl md:text-6xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-4 transition-colors">
                <?= htmlspecialchars($page['title']) ?>
            </h1>
            <p class="text-lg text-slate-500 dark:text-white/50 font-light">Garis komando &amp; tanggung jawab dalam ekosistem logistik kami.</p>
        </div>
    </div>
</section>

<!-- ================================================
     ORG CHART SECTION (Light Blueprint)
     ================================================ -->
<section class="so-chart-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Section Label -->
        <div class="flex items-center gap-4 mb-8" data-aos="fade-right">
            <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-diagram-3 text-primary"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Bagan Hierarki</h2>
                <p class="text-sm text-slate-500 dark:text-white/40">Geser kiri/kanan untuk melihat seluruh struktur</p>
            </div>
            <div class="ml-auto">
                <span class="swipe-hint text-slate-400 dark:text-white/30 text-sm">
                    <i class="bi bi-arrow-left-right text-primary/60"></i>
                    <span>Scroll</span>
                </span>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-2xl bg-white dark:bg-transparent" data-aos="fade-up">
            <div class="chart-scroll-wrapper" id="chartWrapper">
                <?php if (!empty($org_nodes)): ?>
                    <div class="org-tree <?= htmlspecialchars($org_chart_style) ?>">
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
                    <div class="text-center py-20 px-4">
                        <i class="bi bi-diagram-3 text-5xl text-white/10 mb-4 block"></i>
                        <h3 class="text-lg font-bold text-white/30 mb-2">Bagan Struktur Belum Tersedia</h3>
                        <p class="text-white/20 text-sm">Silakan buat struktur organisasi melalui halaman admin.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Admin Quick Edit -->
        <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
        <div class="mt-8 flex justify-center">
            <a href="<?= BASE_URL ?>/settings/pages/edit/<?= $page['id'] ?>" class="flex items-center px-6 py-3 bg-slate-800 dark:bg-white/10 hover:bg-slate-900 dark:hover:bg-white/20 border border-transparent dark:border-white/20 text-white text-sm font-bold rounded-xl transition-all hover:scale-105">
                <i class="bi bi-pencil-fill mr-2"></i> Pengaturan Struktur
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ================================================
     OUR TEAM SECTION (Light & Modern)
     ================================================ -->
<?php if (!empty($org_team)): ?>
<section class="py-28 bg-white dark:bg-slate-950 relative overflow-hidden transition-colors">
    <!-- Subtle background grid -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.03] dark:opacity-[0.06]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section heading -->
        <div class="max-w-2xl mx-auto text-center mb-20" data-aos="fade-up">
            <span class="inline-block text-xs font-black tracking-[0.3em] uppercase text-primary mb-4">Kenali Kami</span>
            <h2 class="text-4xl md:text-5xl font-heading font-black text-slate-900 dark:text-white leading-tight mb-4">
                Tim <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-sky-400">Profesional</span> Kami
            </h2>
            <p class="text-slate-500 dark:text-slate-400">Orang-orang berdedikasi di balik setiap pengiriman yang kami janjikan.</p>
        </div>

        <!-- Team grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($org_team as $i => $member): 
                $photo = $settingModel->get('org_team_photo_' . $member['id'], 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=0f172a&color=38bdf8&size=512&bold=true&length=2');
            ?>
            <div class="group relative" data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 100 ?>">
                <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 dark:border-slate-800 hover:-translate-y-3">
                    <!-- Photo -->
                    <div class="relative overflow-hidden h-56">
                        <img src="<?= htmlspecialchars($photo) ?>" 
                             alt="<?= htmlspecialchars($member['name']) ?>" 
                             class="w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700">
                        <!-- Gradient overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-5">
                            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <p class="text-white text-xs font-semibold uppercase tracking-wider opacity-80"><?= htmlspecialchars($member['title']) ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="p-5 text-center">
                        <h3 class="text-base font-bold text-slate-800 dark:text-white leading-tight"><?= htmlspecialchars($member['name']) ?></h3>
                        <p class="text-xs font-semibold text-primary/80 mt-1 tracking-wide"><?= htmlspecialchars($member['title']) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Drag to scroll for the chart wrapper
(function() {
    const el = document.getElementById('chartWrapper');
    if(!el) return;
    let isDown = false, startX, scrollLeft;
    el.addEventListener('mousedown', e => { isDown = true; el.classList.add('active'); startX = e.pageX - el.offsetLeft; scrollLeft = el.scrollLeft; });
    el.addEventListener('mouseleave', () => { isDown = false; });
    el.addEventListener('mouseup', () => { isDown = false; });
    el.addEventListener('mousemove', e => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - el.offsetLeft;
        const walk = (x - startX) * 1.5;
        el.scrollLeft = scrollLeft - walk;
    });
})();
</script>

<?php
$slot = ob_get_clean();
require __DIR__ . '/layout.php';
