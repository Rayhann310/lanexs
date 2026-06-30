<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="manifestApp()">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manifesting & Bagging</h2>
            <p class="text-slate-500 mt-1">Gabungkan paket ke dalam Karung dan Surat Jalan</p>
        </div>
        <div class="flex space-x-3">
            <button @click="openBagModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center">
                <i class="bi bi-box-seam mr-2"></i> Buat Karung Baru
            </button>
            <button @click="openManifestModal()" class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/20 transition flex items-center">
                <i class="bi bi-truck mr-2"></i> Buat Surat Jalan
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-6 flex items-center shadow-sm">
            <i class="bi bi-check-circle-fill mr-3"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 mb-6 flex items-center shadow-sm">
            <i class="bi bi-exclamation-triangle-fill mr-3"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Bags Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800"><i class="bi bi-box-seam text-indigo-500 mr-2"></i> Data Karung (Bags)</h3>
            </div>
            <div class="overflow-x-auto p-2">
                <table id="bagsTable" class="w-full whitespace-nowrap text-sm">
                    <thead class="bg-slate-50/50 text-slate-500 text-left font-semibold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-xl">Kode Karung</th>
                            <th class="px-4 py-3">Rute</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($bags as $b): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-4 py-3 font-mono font-bold text-indigo-600"><?= htmlspecialchars($b['bag_code']) ?></td>
                                <td class="px-4 py-3">
                                    <?php 
                                        $orig = array_filter($branches, fn($br) => $br['id'] == $b['origin_branch_id']);
                                        $dest = array_filter($branches, fn($br) => $br['id'] == $b['destination_branch_id']);
                                        echo htmlspecialchars(reset($orig)['name'] ?? '') . ' &rarr; ' . htmlspecialchars(reset($dest)['name'] ?? '');
                                    ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-xs font-bold"><?= htmlspecialchars($b['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Manifests Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800"><i class="bi bi-truck text-primary mr-2"></i> Data Surat Jalan (Manifests)</h3>
            </div>
            <div class="overflow-x-auto p-2">
                <table id="manifestsTable" class="w-full whitespace-nowrap text-sm">
                    <thead class="bg-slate-50/50 text-slate-500 text-left font-semibold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-xl">No. Manifest</th>
                            <th class="px-4 py-3">Driver / Plat</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($manifests as $m): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-4 py-3 font-mono font-bold text-primary"><?= htmlspecialchars($m['manifest_code']) ?></td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800"><?= htmlspecialchars($m['driver_name']) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($m['vehicle_plate']) ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-xs font-bold"><?= htmlspecialchars($m['status']) ?></span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="<?= BASE_URL ?>/manifests/print/<?= $m['id'] ?>" target="_blank" class="inline-flex w-8 h-8 rounded-lg items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-200 transition" title="Cetak Surat Jalan">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Create Bag -->
    <div x-show="bagModal" style="display: none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
        <div @click.away="bagModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col" x-show="bagModal" x-transition>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Buat Karung (Bagging)</h3>
                <button @click="bagModal = false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg text-xl"></i></button>
            </div>
            <form action="<?= BASE_URL ?>/manifests/bag" method="POST" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Asal</label>
                            <select name="origin_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Tujuan</label>
                            <select name="destination_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2 font-bold text-slate-800">Pilih Paket (Resi) yang akan dikarungkan:</div>
                    <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-xl bg-slate-50 p-2">
                        <?php foreach($packages as $pkg): ?>
                            <?php if($pkg['status'] === 'PENDING'): ?>
                            <label class="flex items-center p-2 hover:bg-white rounded-lg cursor-pointer transition">
                                <input type="checkbox" name="package_ids[]" value="<?= $pkg['id'] ?>" class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                                <span class="ml-3 font-mono text-sm"><?= htmlspecialchars($pkg['resi']) ?></span>
                                <span class="ml-auto text-xs bg-slate-200 text-slate-600 px-2 rounded"><?= htmlspecialchars($pkg['weight']) ?> Kg</span>
                            </label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" @click="bagModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-8 py-2.5 rounded-xl font-medium shadow-sm">Buat Karung</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Create Manifest -->
    <div x-show="manifestModal" style="display: none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
        <div @click.away="manifestModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col" x-show="manifestModal" x-transition>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Buat Surat Jalan (Manifest)</h3>
                <button @click="manifestModal = false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg text-xl"></i></button>
            </div>
            <form action="<?= BASE_URL ?>/manifests/create" method="POST" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Supir</label>
                            <select name="driver_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                <option value="">-- Pilih Supir --</option>
                                <?php foreach($drivers as $d): ?>
                                    <?php if($d['status'] == 'AVAILABLE'): ?>
                                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?> (<?= htmlspecialchars($d['license_number']) ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kendaraan</label>
                            <select name="vehicle_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                <option value="">-- Pilih Kendaraan --</option>
                                <?php foreach($vehicles as $v): ?>
                                    <?php if($v['status'] == 'AVAILABLE'): ?>
                                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['plate_number']) ?> (<?= htmlspecialchars($v['vehicle_type']) ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Asal</label>
                            <select name="origin_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cabang Tujuan</label>
                            <select name="destination_branch_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                <?php foreach($branches as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2 font-bold text-slate-800">Pilih Karung untuk dimasukkan ke Kendaraan:</div>
                    <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-xl bg-slate-50 p-2">
                        <?php foreach($bags as $bag): ?>
                            <?php if($bag['status'] !== 'MANIFESTED' && $bag['status'] !== 'DELIVERED'): ?>
                            <label class="flex items-center p-2 hover:bg-white rounded-lg cursor-pointer transition">
                                <input type="checkbox" name="bag_ids[]" value="<?= $bag['id'] ?>" class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                                <span class="ml-3 font-mono text-sm font-bold text-indigo-600"><?= htmlspecialchars($bag['bag_code']) ?></span>
                            </label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" @click="manifestModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-2.5 rounded-xl font-medium shadow-sm">Buat Manifest</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script>
    function manifestApp() {
        return {
            bagModal: false,
            manifestModal: false,
            openBagModal() { this.bagModal = true; },
            openManifestModal() { this.manifestModal = true; }
        }
    }
    
    $(document).ready(function() {
        $('#bagsTable, #manifestsTable').DataTable({
            "pageLength": 5,
            "lengthChange": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
                "search": "",
                "searchPlaceholder": "Cari..."
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center py-2 px-4 border-b border-slate-100"<"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center p-3"<"text-xs text-slate-500"i><"flex items-center space-x-1"p>>',
        });
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
