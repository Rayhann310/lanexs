<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pelacakan <?= htmlspecialchars($package['resi']) ?> - LANEXS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#4e73df', secondary: '#224abe' }, fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4">
        <div class="max-w-5xl mx-auto px-4 flex justify-between items-center">
            <a href="<?= BASE_URL ?>/" class="flex items-center space-x-2 text-primary">
                <i class="bi bi-truck text-3xl"></i>
                <span class="font-bold text-2xl tracking-tight">LANEXS</span>
            </a>
            <a href="<?= BASE_URL ?>/" class="text-gray-500 hover:text-primary transition font-medium"><i class="bi bi-house-door mr-1"></i> Beranda</a>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Status Pengiriman</h1>
                <p class="text-gray-500">Nomor Resi: <strong class="text-primary"><?= htmlspecialchars($package['resi']) ?></strong></p>
            </div>
            <div class="hidden sm:block">
                <span id="liveStatus" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span> Live Tracking Active
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Info Panel -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Package Details Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4"><i class="bi bi-box-seam text-primary mr-2"></i> Detail Paket</h3>
                    
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Pengirim</p>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($package['sender_name']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($package['origin_city'] ?? 'Unknown') ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Penerima</p>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($package['receiver_name']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($package['dest_city'] ?? 'Unknown') ?></p>
                        <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($package['receiver_address']) ?></p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Berat</p>
                        <p class="font-medium text-gray-800"><?= number_format($package['weight'], 1) ?> Kg</p>
                    </div>
                </div>
            </div>

            <!-- Right Timeline Panel -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 min-h-[400px]">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-6"><i class="bi bi-clock-history text-primary mr-2"></i> Riwayat Perjalanan</h3>
                    
                    <div class="relative timeline-container pl-10 space-y-8" id="timelineList">
                        <?php if (empty($histories)): ?>
                            <p class="text-gray-400 text-sm">Belum ada riwayat pergerakan.</p>
                        <?php else: ?>
                            <?php foreach ($histories as $idx => $history): ?>
                                <div class="relative z-10" id="hist-<?= $history['id'] ?>">
                                    <!-- Timeline dot -->
                                    <div class="absolute -left-10 w-4 h-4 rounded-full border-4 border-white shadow-sm mt-1 <?= $idx === 0 ? 'bg-primary' : 'bg-gray-300' ?>"></div>
                                    
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800"><?= htmlspecialchars($history['status']) ?></h4>
                                            <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($history['description']) ?></p>
                                            <?php if ($history['location']): ?>
                                                <p class="text-xs font-semibold text-primary mt-2"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($history['location']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-2 sm:mt-0 text-left sm:text-right">
                                            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full"><?= date('d M Y, H:i', strtotime($history['created_at'])) ?></span>
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
                    
                    // Reset old dots color from primary to gray
                    const oldDots = document.querySelectorAll('.bg-primary.rounded-full');
                    oldDots.forEach(dot => {
                        dot.classList.remove('bg-primary');
                        dot.classList.add('bg-gray-300');
                    });

                    // Remove empty message if exists
                    const emptyMsg = timelineList.querySelector('p.text-gray-400');
                    if(emptyMsg) emptyMsg.remove();

                    // Format date
                    const dateObj = new Date(data.created_at);
                    const formattedDate = dateObj.toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'}) + ', ' + 
                                          dateObj.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
                                          
                    const locHtml = data.location ? `<p class="text-xs font-semibold text-primary mt-2"><i class="bi bi-geo-alt-fill"></i> ${data.location}</p>` : '';

                    // Construct new HTML node
                    const newItem = document.createElement('div');
                    newItem.className = "relative z-10 opacity-0 transition-opacity duration-1000";
                    newItem.id = "hist-" + data.id;
                    newItem.innerHTML = `
                        <div class="absolute -left-10 w-4 h-4 rounded-full border-4 border-white shadow-sm mt-1 bg-primary ring-4 ring-primary/20 animate-pulse"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start bg-blue-50 p-4 rounded-xl border border-blue-100 transition-all">
                            <div>
                                <h4 class="font-bold text-gray-800">${data.status}</h4>
                                <p class="text-sm text-gray-600 mt-1">${data.description}</p>
                                ${locHtml}
                            </div>
                            <div class="mt-2 sm:mt-0 text-left sm:text-right">
                                <span class="text-xs font-bold text-primary bg-white px-3 py-1 rounded-full shadow-sm">${formattedDate}</span>
                            </div>
                        </div>
                    `;
                    
                    // Insert at top
                    timelineList.insertBefore(newItem, timelineList.firstChild);
                    
                    // Fade in effect
                    setTimeout(() => {
                        newItem.classList.remove('opacity-0');
                    }, 50);

                } catch(e) {
                    console.error("Error parsing SSE data", e);
                }
            };

            eventSource.onerror = function() {
                statusBadge.className = "inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700";
                statusBadge.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Connection Lost (Reconnecting...)';
            };
            
            eventSource.onopen = function() {
                statusBadge.className = "inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700";
                statusBadge.innerHTML = '<span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span> Live Tracking Active';
            }
        });
    </script>
</body>
</html>
