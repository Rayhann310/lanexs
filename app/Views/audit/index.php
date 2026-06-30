<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('head'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="{
    detailModal: false,
    selectedLog: null,
    showDetail(log) {
        this.selectedLog = log;
        this.detailModal = true;
    }
}">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Audit Trail</h2>
        <p class="text-slate-500 mt-1">Log aktivitas dan perubahan data dalam sistem</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800"><i class="bi bi-shield-lock text-primary mr-2"></i> Riwayat Aktivitas</h3>
        </div>
        <div class="overflow-x-auto p-4">
            <table id="auditTable" class="w-full whitespace-nowrap text-sm">
                <thead class="bg-slate-50/50 text-slate-500 text-left font-semibold uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-xl">Waktu</th>
                        <th class="px-4 py-3">Pengguna</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Entitas</th>
                        <th class="px-4 py-3">IP Address</th>
                        <th class="px-4 py-3 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Log -->
    <div x-show="detailModal" style="display: none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
        <div @click.away="detailModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[90vh] flex flex-col" x-show="detailModal" x-transition>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Detail Aktivitas</h3>
                <button @click="detailModal = false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 bg-slate-50">
                <template x-if="selectedLog">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-xl border border-slate-200">
                                <span class="text-xs font-bold text-slate-400 block mb-1">Aksi & Entitas</span>
                                <span class="font-mono text-primary font-bold" x-text="selectedLog.action"></span> 
                                <span class="text-slate-600">pada</span> 
                                <span class="font-bold text-slate-800" x-text="selectedLog.entity_type"></span>
                                <span class="text-sm text-slate-500" x-text="'(ID: ' + selectedLog.entity_id + ')'"></span>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-slate-200">
                                <span class="text-xs font-bold text-slate-400 block mb-1">User & Waktu</span>
                                <div class="font-bold text-slate-800" x-text="selectedLog.user_name"></div>
                                <div class="text-sm text-slate-500" x-text="selectedLog.created_at"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Old Data -->
                            <div class="bg-white p-4 rounded-xl border border-slate-200">
                                <span class="text-xs font-bold text-slate-400 block mb-2">Data Lama (Before)</span>
                                <pre class="text-xs font-mono text-red-600 bg-red-50 p-3 rounded-lg overflow-x-auto" x-text="formatJSON(selectedLog.old_data)"></pre>
                            </div>
                            <!-- New Data -->
                            <div class="bg-white p-4 rounded-xl border border-slate-200">
                                <span class="text-xs font-bold text-slate-400 block mb-2">Data Baru (After)</span>
                                <pre class="text-xs font-mono text-emerald-600 bg-emerald-50 p-3 rounded-lg overflow-x-auto" x-text="formatJSON(selectedLog.new_data)"></pre>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="p-6 border-t border-slate-100 flex justify-end bg-white rounded-b-2xl">
                <button type="button" @click="detailModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-slate-100 hover:bg-slate-200">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script>
    function formatJSON(jsonString) {
        if (!jsonString) return 'Kosong / Tidak Ada';
        try {
            return JSON.stringify(JSON.parse(jsonString), null, 2);
        } catch (e) {
            return jsonString;
        }
    }

    $(document).ready(function() {
        // Alpine data binding helper
        const alpineData = document.querySelector('[x-data]').__x.$data;

        $('#auditTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "<?= BASE_URL ?>/api/audit-logs/datatable",
            "pageLength": 10,
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            },
            "columns": [
                { "data": "created_at" },
                { "data": "user_name", "className": "font-bold text-slate-800" },
                { 
                    "data": "action",
                    "render": function(data, type, row) {
                        let color = 'slate';
                        if (data.includes('CREATE')) color = 'emerald';
                        else if (data.includes('UPDATE')) color = 'indigo';
                        else if (data.includes('DELETE')) color = 'red';
                        return `<span class="bg-${color}-100 text-${color}-700 px-2 py-1 rounded text-xs font-bold">${data}</span>`;
                    }
                },
                { 
                    "data": "entity_type",
                    "render": function(data, type, row) {
                        return `<span class="font-medium text-slate-700">${data}</span> <span class="text-xs text-slate-400">(ID: ${row.entity_id})</span>`;
                    }
                },
                { "data": "ip_address", "className": "font-mono text-xs text-slate-500" },
                {
                    "data": null,
                    "orderable": false,
                    "className": "text-right",
                    "render": function(data, type, row) {
                        return `<button class="btn-detail text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>`;
                    }
                }
            ],
            "rowCallback": function(row, data) {
                $(row).find('.btn-detail').on('click', function() {
                    alpineData.showDetail(data);
                });
            }
        });
    });
</script>
<?php \App\Helpers\View::endSection(); ?>
