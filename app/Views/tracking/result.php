<?php
$pageTitle = 'Hasil Pelacakan ' . htmlspecialchars($package['resi']) . ' - ' . APP_NAME;
$pageDescription = 'Hasil pelacakan untuk resi ' . htmlspecialchars($package['resi']) . ' di ' . APP_NAME;

ob_start();
?>
<style>
    /* Timeline line */
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 24px;
        bottom: 24px;
        width: 2px;
        background: #e5e7eb;
        z-index: 0;
        transition: background-color 0.3s;
    }
    @media (prefers-color-scheme: dark) {
        html.dark .timeline-container::before {
            background: #334155;
        }
    }
</style>
<?php $extraHead = ob_get_clean(); ?>

<?php ob_start(); ?>

<div class="pt-28 pb-12 bg-slate-50 dark:bg-slate-900 transition-colors min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 mt-4 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2 transition-colors">Status Pengiriman</h1>
                <p class="text-slate-500 dark:text-slate-400 transition-colors">Nomor Resi: <strong class="text-primary text-lg tracking-wide bg-primary/10 px-2 py-1 rounded-md border border-primary/20 ml-1"><?= htmlspecialchars($package['resi']) ?></strong></p>
            </div>
            <div>
                <span id="liveStatus" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 shadow-sm border border-emerald-200 dark:border-emerald-800/50 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Live Tracking Active
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Info Panel -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Package Details Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-lg shadow-slate-200/40 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-colors">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-5 transition-colors flex items-center"><div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center mr-3"><i class="bi bi-box-seam"></i></div> Detail Paket</h3>
                    
                    <div class="mb-5">
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider mb-1.5 transition-colors">Pengirim</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200 transition-colors"><?= htmlspecialchars($package['sender_name']) ?></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors"><i class="bi bi-geo-alt mr-1"></i> <?= htmlspecialchars($package['origin_city'] ?? 'Unknown') ?></p>
                    </div>
                    
                    <div class="mb-5 relative pl-4 border-l-2 border-dashed border-slate-200 dark:border-slate-700">
                        <!-- Connecting line visual -->
                    </div>

                    <div class="mb-5">
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider mb-1.5 transition-colors">Penerima</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200 transition-colors"><?= htmlspecialchars($package['receiver_name']) ?></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors"><i class="bi bi-geo-alt-fill text-primary mr-1"></i> <?= htmlspecialchars($package['dest_city'] ?? 'Unknown') ?></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700 transition-colors"><?= htmlspecialchars($package['receiver_address']) ?></p>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider mb-1 transition-colors">Berat Barang</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200 text-lg transition-colors"><?= number_format($package['weight'], 1) ?> <span class="text-sm font-medium text-slate-500">Kg</span></p>
                    </div>
                </div>
            </div>

            <!-- Right Timeline Panel -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-3xl shadow-lg shadow-slate-200/40 dark:shadow-none border border-slate-100 dark:border-slate-700 min-h-[400px] transition-colors">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-8 transition-colors flex items-center text-xl"><div class="w-10 h-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center mr-3"><i class="bi bi-clock-history"></i></div> Riwayat Perjalanan</h3>
                    
                    <div class="relative timeline-container pl-10 space-y-10" id="timelineList">
                        <?php if (empty($histories)): ?>
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 dark:text-slate-600 mx-auto mb-4 text-3xl"><i class="bi bi-hourglass-split"></i></div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">Belum ada riwayat pergerakan.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($histories as $idx => $history): ?>
                                <div class="relative z-10" id="hist-<?= $history['id'] ?>">
                                    <!-- Timeline dot -->
                                    <div class="absolute -left-10 w-4 h-4 rounded-full border-4 border-white dark:border-slate-800 shadow-sm mt-1.5 <?= $idx === 0 ? 'bg-primary ring-4 ring-primary/20' : 'bg-slate-300 dark:bg-slate-600' ?> transition-colors"></div>
                                    
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start <?= $idx === 0 ? 'bg-primary/5 dark:bg-primary/10 p-4 rounded-2xl border border-primary/10 dark:border-primary/20' : '' ?>">
                                        <div>
                                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-lg transition-colors"><?= htmlspecialchars($history['status']) ?></h4>
                                            <p class="text-slate-600 dark:text-slate-400 mt-1 transition-colors leading-relaxed"><?= htmlspecialchars($history['description']) ?></p>
                                            
                                            <?php if ($history['location']): ?>
                                                <p class="text-sm font-semibold text-primary mt-3 flex items-center"><i class="bi bi-geo-alt-fill mr-1.5"></i> <?= htmlspecialchars($history['location']) ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($history['proof_image'])): ?>
                                                <div class="mt-4">
                                                    <a href="<?= BASE_URL ?>/<?= $history['proof_image'] ?>" target="_blank" class="inline-block relative group rounded-xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700">
                                                        <img src="<?= BASE_URL ?>/<?= $history['proof_image'] ?>" alt="Bukti Pengiriman" class="h-36 w-auto object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-300" style="max-width: 100%;">
                                                        <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <i class="bi bi-zoom-in text-white text-3xl drop-shadow-md"></i>
                                                        </div>
                                                    </a>
                                                    <div class="mt-2">
                                                        <a href="<?= BASE_URL ?>/<?= $history['proof_image'] ?>" download class="inline-flex items-center text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-2 rounded-lg transition-colors">
                                                            <i class="bi bi-download mr-1.5"></i> Unduh Foto
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-3 sm:mt-0 text-left sm:text-right">
                                            <span class="inline-flex items-center text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700/50 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 transition-colors">
                                                <i class="bi bi-calendar3 mr-1.5"></i> <?= date('d M Y, H:i', strtotime($history['created_at'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SSE Real-time Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const packageId = <?= $package['id'] ?>;
        const eventSource = new EventSource("<?= BASE_URL ?>/api/tracking/stream/" + packageId);
        const timelineList = document.getElementById('timelineList');
        const statusBadge = document.getElementById('liveStatus');

        eventSource.onmessage = function(event) {
            if (event.data === 'ping') return;
            
            try {
                const data = JSON.parse(event.data);
                
                // Reset old dots color
                const oldDots = document.querySelectorAll('.bg-primary.rounded-full');
                oldDots.forEach(dot => {
                    dot.classList.remove('bg-primary', 'ring-4', 'ring-primary/20');
                    dot.classList.add('bg-slate-300', 'dark:bg-slate-600');
                });
                
                // Remove highlight background from old top items
                const oldHighlights = document.querySelectorAll('.bg-primary\\/5');
                oldHighlights.forEach(el => {
                    el.classList.remove('bg-primary/5', 'dark:bg-primary/10', 'p-4', 'rounded-2xl', 'border', 'border-primary/10', 'dark:border-primary/20');
                });

                // Remove empty message if exists
                const emptyMsg = timelineList.querySelector('.py-12');
                if(emptyMsg) emptyMsg.remove();

                // Format date
                const dateObj = new Date(data.created_at);
                const formattedDate = dateObj.toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'}) + ', ' + 
                                      dateObj.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
                                      
                const locHtml = data.location ? `<p class="text-sm font-semibold text-primary mt-3 flex items-center"><i class="bi bi-geo-alt-fill mr-1.5"></i> ${data.location}</p>` : '';
                
                const imgHtml = data.proof_image ? `
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>/${data.proof_image}" target="_blank" class="inline-block relative group rounded-xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700">
                            <img src="<?= BASE_URL ?>/${data.proof_image}" alt="Bukti Pengiriman" class="h-36 w-auto object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-300" style="max-width: 100%;">
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="bi bi-zoom-in text-white text-3xl drop-shadow-md"></i>
                            </div>
                        </a>
                        <div class="mt-2">
                            <a href="<?= BASE_URL ?>/${data.proof_image}" download class="inline-flex items-center text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-2 rounded-lg transition-colors">
                                <i class="bi bi-download mr-1.5"></i> Unduh Foto
                            </a>
                        </div>
                    </div>
                ` : '';

                // Construct new HTML node
                const newItem = document.createElement('div');
                newItem.className = "relative z-10 opacity-0 transition-all duration-700 transform -translate-y-4";
                newItem.id = "hist-" + data.id;
                newItem.innerHTML = `
                    <div class="absolute -left-10 w-4 h-4 rounded-full border-4 border-white dark:border-slate-800 shadow-sm mt-1.5 bg-primary ring-4 ring-primary/20 animate-pulse transition-colors"></div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start bg-primary/5 dark:bg-primary/10 p-4 rounded-2xl border border-primary/10 dark:border-primary/20 transition-all">
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-lg transition-colors">${data.status}</h4>
                            <p class="text-slate-600 dark:text-slate-400 mt-1 transition-colors leading-relaxed">${data.description}</p>
                            ${locHtml}
                            ${imgHtml}
                        </div>
                        <div class="mt-3 sm:mt-0 text-left sm:text-right">
                            <span class="inline-flex items-center text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700/50 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 transition-colors">
                                <i class="bi bi-calendar3 mr-1.5"></i> ${formattedDate}
                            </span>
                        </div>
                    </div>
                `;
                
                // Insert at top
                timelineList.insertBefore(newItem, timelineList.firstChild);
                
                // Fade in effect
                setTimeout(() => {
                    newItem.classList.remove('opacity-0', '-translate-y-4');
                }, 50);

            } catch(e) {
                console.error("Error parsing SSE data", e);
            }
        };

        eventSource.onerror = function() {
            statusBadge.className = "inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400 shadow-sm border border-red-200 dark:border-red-800/50 transition-colors";
            statusBadge.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Connection Lost (Reconnecting...)';
        };
        
        eventSource.onopen = function() {
            statusBadge.className = "inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 shadow-sm border border-emerald-200 dark:border-emerald-800/50 transition-colors";
            statusBadge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Live Tracking Active';
        }
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../landing/layout.php';
?>
