<?php \App\Helpers\View::extends('app'); ?>

<?php \App\Helpers\View::section('content'); ?>
<div class="px-8 py-8" x-data="scannerApp()">
    <div class="flex flex-col md:flex-row justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Barcode Scanner</h2>
            <p class="text-slate-500 mt-1">Sistem Otomatisasi Status (Inbound & Outbound)</p>
        </div>
    </div>

    <!-- Alert Box -->
    <div x-show="alert.show" x-transition 
         :class="alert.type === 'success' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100'"
         class="px-4 py-3 rounded-xl border mb-6 flex items-center shadow-sm">
        <i :class="alert.type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'" class="bi mr-3"></i>
        <span x-text="alert.message"></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Scanner Module -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                
                <h3 class="font-bold text-slate-800 mb-4 flex items-center">
                    <i class="bi bi-upc-scan text-primary mr-2 text-xl"></i> Panel Scan
                </h3>
                
                <form @submit.prevent="processScan">
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Mode Scan (Status Tujuan)</label>
                        <select x-model="status" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-slate-700">
                            <option value="TRANSIT">OUTBOUND - Dikirim / Transit</option>
                            <option value="GUDANG_TUJUAN">INBOUND - Tiba di Gudang Tujuan</option>
                            <option value="DELIVERED">INBOUND - Diterima Pelanggan</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Barcode (Resi / Karung / Surat Jalan)</label>
                        <div class="relative">
                            <input type="text" x-model="barcode" x-ref="barcodeInput" required autofocus autocomplete="off" placeholder="Scan Barcode di sini..." 
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-lg font-mono font-bold uppercase">
                            <i class="bi bi-upc absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-2xl"></i>
                        </div>
                        <p class="text-xs text-slate-500 mt-2"><i class="bi bi-info-circle mr-1"></i> Tekan Enter setelah mengetik/scan barcode</p>
                    </div>

                    <button type="submit" :disabled="isLoading" class="w-full bg-primary hover:bg-secondary text-white py-3.5 rounded-xl font-bold transition shadow-sm flex items-center justify-center">
                        <span x-show="!isLoading"><i class="bi bi-check-lg mr-2"></i> Proses Scan</span>
                        <span x-show="isLoading"><i class="bi bi-arrow-repeat animate-spin mr-2"></i> Memproses...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- History Log -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-800 flex items-center">
                        <i class="bi bi-clock-history text-slate-400 mr-2"></i> Log Aktivitas Scan (Sesi Ini)
                    </h3>
                    <button @click="logs = []" class="text-xs text-red-500 hover:text-red-700 font-medium">Bersihkan Log</button>
                </div>
                
                <div class="flex-1 bg-slate-50 rounded-xl border border-slate-200 p-4 overflow-y-auto" style="min-height: 300px; max-height: 500px;">
                    <template x-if="logs.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-slate-400">
                            <i class="bi bi-inbox text-4xl mb-2 text-slate-300"></i>
                            <p class="text-sm">Belum ada aktivitas scan.</p>
                        </div>
                    </template>
                    
                    <ul class="space-y-3">
                        <template x-for="(log, index) in logs" :key="index">
                            <li class="bg-white p-3 rounded-lg shadow-sm border border-slate-100 flex items-start" x-transition>
                                <div class="mt-0.5 mr-3">
                                    <i :class="log.success ? 'bi-check-circle-fill text-emerald-500' : 'bi-x-circle-fill text-red-500'" class="bi text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800" x-text="log.message"></p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        <span class="font-mono font-bold bg-slate-100 px-1.5 py-0.5 rounded" x-text="log.barcode"></span> 
                                        &bull; <span x-text="log.time"></span>
                                    </p>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php \App\Helpers\View::endSection(); ?>

<?php \App\Helpers\View::section('scripts'); ?>
<script>
    function scannerApp() {
        return {
            barcode: '',
            status: 'TRANSIT',
            isLoading: false,
            logs: [],
            alert: { show: false, type: 'success', message: '' },
            
            init() {
                // Focus on input when initialized
                setTimeout(() => { this.$refs.barcodeInput.focus(); }, 100);
            },
            
            showAlert(type, message) {
                this.alert = { show: true, type, message };
                setTimeout(() => { this.alert.show = false; }, 3000);
            },
            
            addLog(success, message, barcode) {
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' + 
                              now.getMinutes().toString().padStart(2, '0') + ':' + 
                              now.getSeconds().toString().padStart(2, '0');
                
                this.logs.unshift({ success, message, barcode, time: timeStr });
            },
            
            processScan() {
                if(!this.barcode || this.isLoading) return;
                
                this.isLoading = true;
                const scannedCode = this.barcode.toUpperCase(); // Save before clearing
                
                const formData = new FormData();
                formData.append('barcode', scannedCode);
                formData.append('status', this.status);
                
                fetch('<?= BASE_URL ?>/scan/process', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok && response.status !== 200) {
                        return response.text().then(text => {
                            throw new Error('Server ' + response.status + ': ' + text.substring(0, 100));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        this.showAlert('success', data.message);
                        this.addLog(true, data.message, scannedCode);
                    } else {
                        this.showAlert('error', data.message);
                        this.addLog(false, data.message, scannedCode);
                    }
                })
                .catch(error => {
                    const errMsg = error.message || 'Terjadi kesalahan jaringan.';
                    this.showAlert('error', errMsg);
                    this.addLog(false, errMsg, scannedCode);
                })
                .finally(() => {
                    this.isLoading = false;
                    this.barcode = '';
                    this.$refs.barcodeInput.focus();
                });
            }
        }
    }
</script>
<?php \App\Helpers\View::endSection(); ?>
