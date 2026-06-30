<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="fleetManager()">
    <div class="flex flex-col md:flex-row justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Armada & Kurir</h2>
            <p class="text-slate-500 mt-1">Master data Kendaraan dan Pengemudi</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <button x-show="activeTab === 'vehicles'" @click="vehicleModal = true" class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Kendaraan
            </button>
            <button x-show="activeTab === 'drivers'" @click="driverModal = true" class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Supir
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

    <!-- Tabs Navigation -->
    <div class="flex space-x-1 border-b border-slate-200 mb-6">
        <button @click="activeTab = 'vehicles'" 
                :class="activeTab === 'vehicles' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors focus:outline-none flex items-center">
            <i class="bi bi-truck mr-2"></i> Data Kendaraan
        </button>
        <button @click="activeTab = 'drivers'" 
                :class="activeTab === 'drivers' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors focus:outline-none flex items-center">
            <i class="bi bi-person-vcard mr-2"></i> Data Supir
        </button>
    </div>

    <!-- Tab Content: Vehicles -->
    <div x-show="activeTab === 'vehicles'" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto p-2">
                <table id="vehiclesTable" class="w-full whitespace-nowrap text-sm">
                    <thead class="bg-slate-50/50 text-slate-500 text-left font-semibold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-xl">Plat Nomor</th>
                            <th class="px-6 py-4">Tipe Kendaraan</th>
                            <th class="px-6 py-4">Kapasitas (Kg)</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($vehicles as $v): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-bold font-mono text-indigo-700"><?= htmlspecialchars($v['plate_number']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($v['vehicle_type']) ?></td>
                                <td class="px-6 py-4 font-bold text-slate-600"><?= number_format($v['capacity_kg']) ?> Kg</td>
                                <td class="px-6 py-4">
                                    <?php if($v['status'] == 'AVAILABLE'): ?>
                                        <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold">TERSEDIA</span>
                                    <?php else: ?>
                                        <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded text-xs font-bold"><?= $v['status'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 flex justify-end space-x-2">
                                    <button @click="deleteItem(<?= $v['id'] ?>, 'vehicles')" class="text-red-500 hover:bg-red-50 p-2 rounded transition"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Content: Drivers -->
    <div x-show="activeTab === 'drivers'" style="display: none;" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto p-2">
                <table id="driversTable" class="w-full whitespace-nowrap text-sm">
                    <thead class="bg-slate-50/50 text-slate-500 text-left font-semibold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-xl">Nama Supir</th>
                            <th class="px-6 py-4">No. HP</th>
                            <th class="px-6 py-4">No. SIM</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($drivers as $d): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-bold text-slate-800"><?= htmlspecialchars($d['name']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($d['phone']) ?></td>
                                <td class="px-6 py-4 font-mono font-medium text-slate-600"><?= htmlspecialchars($d['license_number']) ?></td>
                                <td class="px-6 py-4">
                                    <?php if($d['status'] == 'AVAILABLE'): ?>
                                        <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold">TERSEDIA</span>
                                    <?php else: ?>
                                        <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded text-xs font-bold"><?= $d['status'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 flex justify-end space-x-2">
                                    <button @click="deleteItem(<?= $d['id'] ?>, 'drivers')" class="text-red-500 hover:bg-red-50 p-2 rounded transition"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals (Hidden) -->
    <!-- Add Vehicle Modal -->
    <div x-show="vehicleModal" style="display: none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div @click.away="vehicleModal = false" class="bg-white rounded-2xl w-full max-w-lg p-6">
            <h3 class="text-xl font-bold mb-4">Tambah Kendaraan</h3>
            <form action="<?= BASE_URL ?>/fleet/vehicles" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Plat Nomor</label>
                    <input type="text" name="plate_number" required class="w-full border border-slate-200 px-4 py-2 rounded-xl" placeholder="B 1234 CD">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tipe Kendaraan</label>
                    <input type="text" name="vehicle_type" required class="w-full border border-slate-200 px-4 py-2 rounded-xl" placeholder="Truk Engkel / GranMax">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Kapasitas Maksimal (Kg)</label>
                    <input type="number" name="capacity_kg" required class="w-full border border-slate-200 px-4 py-2 rounded-xl" placeholder="2000">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="vehicleModal = false" class="px-4 py-2 border rounded-xl">Batal</button>
                    <button type="submit" class="bg-indigo-500 text-white px-6 py-2 rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Driver Modal -->
    <div x-show="driverModal" style="display: none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div @click.away="driverModal = false" class="bg-white rounded-2xl w-full max-w-lg p-6">
            <h3 class="text-xl font-bold mb-4">Tambah Supir</h3>
            <form action="<?= BASE_URL ?>/fleet/drivers" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Supir</label>
                    <input type="text" name="name" required class="w-full border border-slate-200 px-4 py-2 rounded-xl" placeholder="Budi Santoso">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">No. Handphone</label>
                    <input type="text" name="phone" required class="w-full border border-slate-200 px-4 py-2 rounded-xl" placeholder="0812...">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Nomor SIM</label>
                    <input type="text" name="license_number" required class="w-full border border-slate-200 px-4 py-2 rounded-xl" placeholder="SIM-B1-...">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="driverModal = false" class="px-4 py-2 border rounded-xl">Batal</button>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" class="hidden"></form>
</div>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script>
    function fleetManager() {
        // Read URL parameter to set active tab if needed
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'vehicles';

        return {
            activeTab: tab,
            vehicleModal: false,
            driverModal: false,
            
            deleteItem(id, type) {
                Swal.fire({
                    title: 'Hapus data?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteForm');
                        form.action = `<?= BASE_URL ?>/fleet/${type}/delete/${id}`;
                        form.submit();
                    }
                });
            }
        }
    }

    $(document).ready(function() {
        $('#vehiclesTable, #driversTable').DataTable({
            "pageLength": 10,
            "language": { "search": "", "searchPlaceholder": "Cari data..." },
            "dom": '<"flex justify-between items-center py-3 border-b"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center p-4"ip>'
        });
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
