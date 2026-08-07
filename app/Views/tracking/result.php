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
        left: 24px;
        top: 24px;
        bottom: 24px;
        width: 2px;
        background: #e2e8f0;
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

<div class="pt-28 pb-12 bg-white dark:bg-slate-900 transition-colors min-h-screen relative">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.06]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 mt-4 gap-4" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2 transition-colors">Detail Pengiriman</h1>
                <p class="text-slate-500 dark:text-slate-400 transition-colors">Nomor Resi: <strong class="text-teal-600 text-lg tracking-wide bg-teal-50 dark:bg-teal-900/30 px-3 py-1 rounded-lg border border-teal-200 dark:border-teal-800 ml-1"><?= htmlspecialchars($package['resi']) ?></strong></p>
            </div>
            <div>
                <span id="liveStatus" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-teal-50 text-teal-700 dark:bg-teal-900/40 dark:text-teal-400 shadow-sm border border-teal-200 dark:border-teal-800/50 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-teal-500 mr-2 animate-pulse"></span> Live Tracking Aktif
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Info Panel -->
            <div class="lg:col-span-1 space-y-6" data-aos="fade-right">
                <!-- Package Details Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-colors">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-5 transition-colors flex items-center">
                        <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 flex items-center justify-center mr-3 border border-teal-100 dark:border-teal-800"><i class="bi bi-box-seam"></i></div> 
                        Informasi Paket
                    </h3>
                    
                    <div class="mb-5 relative pl-4 border-l-2 border-teal-100 dark:border-teal-900/50">
                        <div class="absolute -left-1.5 top-1 w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 border-2 border-white dark:border-slate-800"></div>
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider mb-1.5 transition-colors">Pengirim</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200 transition-colors text-lg"><?= htmlspecialchars($package['sender_name']) ?></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors"><i class="bi bi-geo-alt mr-1"></i> <?= htmlspecialchars($package['origin_city'] ?? 'Unknown') ?></p>
                    </div>
                    
                    <div class="mb-5 relative pl-4 border-l-2 border-teal-100 dark:border-teal-900/50">
                        <div class="absolute -left-1.5 top-1 w-3 h-3 rounded-full bg-teal-500 border-2 border-white dark:border-slate-800"></div>
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider mb-1.5 transition-colors">Penerima</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200 transition-colors text-lg"><?= htmlspecialchars($package['receiver_name']) ?></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors"><i class="bi bi-geo-alt-fill text-teal-600 mr-1"></i> <?= htmlspecialchars($package['dest_city'] ?? 'Unknown') ?></p>
                        <div class="mt-2 bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700 transition-colors">
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed"><?= htmlspecialchars($package['receiver_address']) ?></p>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 bg-teal-50 dark:bg-teal-900/10 p-4 rounded-2xl mt-4">
                        <p class="text-xs text-teal-600 dark:text-teal-400 font-bold uppercase tracking-wider mb-1 transition-colors">Berat Barang</p>
                        <p class="font-black text-slate-900 dark:text-white text-2xl transition-colors"><?= number_format($package['weight'], 1) ?> <span class="text-sm font-medium text-slate-500">Kg</span></p>
                    </div>
                </div>
            </div>

            <!-- Right Timeline Panel -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="bg-white dark:bg-slate-800 p-6 md:p-10 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 min-h-[400px] transition-colors">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-8 transition-colors flex items-center text-xl">
                        <div class="w-10 h-10 rounded-2xl bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 flex items-center justify-center mr-3 border border-cyan-100 dark:border-cyan-800"><i class="bi bi-clock-history"></i></div> 
                        Riwayat Perjalanan
                    </h3>
                    
                    <div class="relative timeline-container pl-10 space-y-10" id="timelineList">
                        <?php if (empty($histories)): ?>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 dark:text-slate-600 mx-auto mb-4 text-4xl"><i class="bi bi-hourglass-split"></i></div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors text-lg">Belum ada riwayat pergerakan.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($histories as $idx => $history): ?>
                                <div class="relative z-10 group" id="hist-<?= $history['id'] ?>">
                                    <!-- Timeline dot -->
                                    <div class="absolute -left-12 w-5 h-5 rounded-full border-4 border-white dark:border-slate-800 shadow-md mt-1.5 <?= $idx === 0 ? 'bg-teal-500 ring-4 ring-teal-500/20' : 'bg-slate-300 dark:bg-slate-600' ?> transition-colors z-10 group-hover:scale-110"></div>
                                    
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start <?= $idx === 0 ? 'bg-teal-50 dark:bg-teal-900/20 p-5 rounded-2xl border border-teal-100 dark:border-teal-800 shadow-sm' : 'p-2' ?> transition-all">
                                        <div>
                                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-lg transition-colors"><?= htmlspecialchars($history['status']) ?></h4>
                                            <p class="text-slate-600 dark:text-slate-400 mt-1 transition-colors leading-relaxed"><?= htmlspecialchars($history['description']) ?></p>
                                            
                                            <?php if ($history['location']): ?>
                                                <p class="text-sm font-semibold text-teal-600 mt-3 flex items-center"><i class="bi bi-geo-alt-fill mr-1.5"></i> <?= htmlspecialchars($history['location']) ?></p>
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
                                                        <a href="<?= BASE_URL ?>/<?= $history['proof_image'] ?>" download class="inline-flex items-center text-xs font-bold text-teal-600 hover:text-white bg-teal-50 hover:bg-teal-600 px-3 py-2 rounded-lg transition-colors border border-teal-100">
                                                            <i class="bi bi-download mr-1.5"></i> Unduh Foto
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-3 sm:mt-0 text-left sm:text-right">
                                            <span class="inline-flex items-center text-xs font-bold text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 transition-colors shadow-sm">
                                                <i class="bi bi-calendar3 mr-1.5 text-teal-500"></i> <?= date('d M Y, H:i', strtotime($history['created_at'])) ?>
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
                const oldDots = document.querySelectorAll('.bg-teal-500.rounded-full');
                oldDots.forEach(dot => {
                    dot.classList.remove('bg-teal-500', 'ring-4', 'ring-teal-500/20');
                    dot.classList.add('bg-slate-300', 'dark:bg-slate-600');
                });
                
                // Remove highlight background from old top items
                const oldHighlights = document.querySelectorAll('.bg-teal-50');
                oldHighlights.forEach(el => {
                    el.classList.remove('bg-teal-50', 'dark:bg-teal-900/20', 'p-5', 'rounded-2xl', 'border', 'border-teal-100', 'dark:border-teal-800', 'shadow-sm');
                    el.classList.add('p-2');
                });

                // Remove empty message if exists
                const emptyMsg = timelineList.querySelector('.py-12');
                if(emptyMsg) emptyMsg.remove();

                // Format date
                const dateObj = new Date(data.created_at);
                const formattedDate = dateObj.toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'}) + ', ' + 
                                      dateObj.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
                                      
                const locHtml = data.location ? `<p class="text-sm font-semibold text-teal-600 mt-3 flex items-center"><i class="bi bi-geo-alt-fill mr-1.5"></i> ${data.location}</p>` : '';

                
                const imgHtml = data.proof_image ? `
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>/${data.proof_image}" target="_blank" class="inline-block relative group rounded-xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700">
                            <img src="<?= BASE_URL ?>/${data.proof_image}" alt="Bukti Pengiriman" class="h-36 w-auto object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-300" style="max-width: 100%;">
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="bi bi-zoom-in text-white text-3xl drop-shadow-md"></i>
                            </div>
                        </a>
                        <div class="mt-2">
                            <a href="<?= BASE_URL ?>/${data.proof_image}" download class="inline-flex items-center text-xs font-bold text-teal-600 hover:text-white bg-teal-50 hover:bg-teal-600 px-3 py-2 rounded-lg transition-colors border border-teal-100">
                                <i class="bi bi-download mr-1.5"></i> Unduh Foto
                            </a>
                        </div>
                    </div>
                ` : '';

                // Construct new HTML node
                const newItem = document.createElement('div');
                newItem.className = "relative z-10 opacity-0 transition-all duration-700 transform -translate-y-4 group";
                newItem.id = "hist-" + data.id;
                newItem.innerHTML = `
                    <div class="absolute -left-12 w-5 h-5 rounded-full border-4 border-white dark:border-slate-800 shadow-md mt-1.5 bg-teal-500 ring-4 ring-teal-500/20 animate-pulse transition-colors z-10 group-hover:scale-110"></div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start bg-teal-50 dark:bg-teal-900/20 p-5 rounded-2xl border border-teal-100 dark:border-teal-800 shadow-sm transition-all">
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-lg transition-colors">${data.status}</h4>
                            <p class="text-slate-600 dark:text-slate-400 mt-1 transition-colors leading-relaxed">${data.description}</p>
                            ${locHtml}
                            ${imgHtml}
                        </div>
                        <div class="mt-3 sm:mt-0 text-left sm:text-right">
                            <span class="inline-flex items-center text-xs font-bold text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 transition-colors shadow-sm">
                                <i class="bi bi-calendar3 mr-1.5 text-teal-500"></i> ${formattedDate}
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
$slot = ob_get_clean();
require __DIR__ . '/../landing/layout.php';
?>
