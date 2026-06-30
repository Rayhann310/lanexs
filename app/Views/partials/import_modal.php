<?php
/**
 * Reusable Import Modal Partial
 * 
 * Required variables from parent view:
 *   $importPreviewUrl  - POST URL for preview  e.g. BASE_URL . '/tariffs/import-preview'
 *   $importProcessUrl  - POST URL for process  e.g. BASE_URL . '/tariffs/import-process'
 *   $templateUrl       - GET URL for template  e.g. BASE_URL . '/tariffs/template'
 *   $moduleName        - Human name            e.g. 'Tarif'
 *   $previewColumns    - Array of column configs: [['label'=>'', 'key'=>'', 'badge'=>false], ...]
 */
?>

<!-- ========= IMPORT MODAL ========= -->
<div x-show="importModal" style="display:none" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" x-transition.opacity>
    <div @click.away="importModal = false" x-show="importModal" x-transition class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Import Data <?= $moduleName ?></h3>
                <p class="text-sm text-slate-500 mt-0.5">Upload file Excel (.xlsx) atau CSV untuk import massal</p>
            </div>
            <button @click="importModal = false" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg text-xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <p class="text-sm font-semibold text-amber-800 mb-2"><i class="bi bi-info-circle mr-1"></i> Cara Import:</p>
                <ol class="text-xs text-amber-700 space-y-1 list-decimal ml-4">
                    <li>Unduh <strong>Template Excel</strong> terlebih dahulu</li>
                    <li>Isi data, lihat sheet <em>petunjuk</em> untuk nilai ID yang valid</li>
                    <li>Simpan & upload file di sini (format .xlsx atau .csv)</li>
                    <li>Review Preview, lalu klik <strong>Proses Import</strong></li>
                </ol>
            </div>

            <label class="block border-2 border-dashed border-slate-300 hover:border-primary rounded-xl p-8 text-center cursor-pointer transition-colors group"
                   :class="importFile ? 'border-emerald-400 bg-emerald-50' : ''">
                <input type="file" accept=".xlsx,.csv,.txt" class="hidden" @change="handleFileSelect($event)">
                <i class="bi text-4xl mb-3 block transition-colors"
                   :class="importFile ? 'bi-file-earmark-check text-emerald-500' : 'bi-file-earmark-excel text-slate-400 group-hover:text-primary'"></i>
                <p class="text-sm font-medium" :class="importFile ? 'text-emerald-700' : 'text-slate-600'">
                    <span x-text="importFile ? importFile.name : 'Klik atau seret file .xlsx / .csv ke sini'"></span>
                </p>
                <p class="text-xs text-slate-400 mt-1" x-show="!importFile">Mendukung format .xlsx (Excel) dan .csv (max 10MB)</p>
            </label>

            <div x-show="importErrors.length > 0" class="bg-red-50 border border-red-200 rounded-xl p-3 max-h-28 overflow-y-auto">
                <template x-for="err in importErrors">
                    <p class="text-xs text-red-600 flex items-start"><i class="bi bi-exclamation-circle mr-1 mt-0.5 shrink-0"></i><span x-text="err"></span></p>
                </template>
            </div>
        </div>
        <div class="p-6 border-t border-slate-100 flex justify-between items-center">
            <a href="<?= $templateUrl ?>" class="text-sm text-primary hover:underline flex items-center font-medium">
                <i class="bi bi-file-earmark-excel mr-1"></i> Unduh Template .xlsx
            </a>
            <div class="flex space-x-3">
                <button @click="importModal = false" class="px-4 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 text-sm font-medium">Batal</button>
                <button @click="previewImport('<?= $importPreviewUrl ?>')"
                        :disabled="!importFile || importLoading"
                        class="px-5 py-2 rounded-xl text-white bg-amber-500 hover:bg-amber-600 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <svg x-show="importLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="importLoading ? 'Memproses...' : 'Preview Data'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
