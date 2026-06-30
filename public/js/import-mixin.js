/**
 * LANEXS Excel Import Mixin
 * Include this script on any page that needs import functionality.
 * Usage: spread importMixin() into your Alpine component data object.
 *
 * Example in view:
 *   function myPageManager() {
 *       return {
 *           ...importMixin(),
 *           // ... your own state
 *       }
 *   }
 */
function importMixin() {
    return {
        // State
        importModal:   false,
        previewModal:  false,
        importFile:    null,
        importLoading: false,
        processLoading: false,
        previewRows:   [],
        importErrors:  [],

        // Pick file
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire('File Terlalu Besar', 'Ukuran file maksimal 10MB.', 'warning');
                return;
            }
            this.importFile = file;
            this.importErrors = [];
        },

        // Preview — send file to server, open preview modal
        async previewImport(previewUrl) {
            if (!this.importFile) return;
            this.importLoading = true;
            this.importErrors  = [];

            const fd = new FormData();
            fd.append('import_file', this.importFile);

            try {
                const res  = await fetch(previewUrl, { method: 'POST', body: fd });
                const data = await res.json();

                if (data.success) {
                    this.previewRows   = data.rows || [];
                    this.importErrors  = data.errors || [];
                    this.importModal   = false;
                    this.previewModal  = true;
                } else {
                    this.importErrors = [data.message];
                    Swal.fire('Gagal Membaca File', data.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Terjadi kesalahan jaringan. Coba lagi.', 'error');
            } finally {
                this.importLoading = false;
            }
        },

        // Process — send valid rows to server
        async processImport(processUrl, entityLabel = 'data') {
            const validRows = this.previewRows.filter(r => r._valid);
            if (!validRows.length) {
                Swal.fire('Tidak Ada Data Valid', 'Semua baris memiliki error. Periksa kembali file Anda.', 'warning');
                return;
            }

            const confirm = await Swal.fire({
                title: 'Konfirmasi Import',
                html:  `Akan memproses <b>${validRows.length} ${entityLabel}</b>.<br>Baris error (${this.previewRows.filter(r => !r._valid).length}) akan dilewati. Lanjutkan?`,
                icon:  'question',
                showCancelButton:   true,
                confirmButtonColor: '#4e73df',
                confirmButtonText:  'Ya, Import Sekarang!',
                cancelButtonText:   'Batal'
            });
            if (!confirm.isConfirmed) return;

            this.processLoading = true;
            const fd = new FormData();
            fd.append('rows', JSON.stringify(validRows));

            try {
                const res  = await fetch(processUrl, { method: 'POST', body: fd });
                const data = await res.json();

                if (data.success) {
                    this.previewModal  = false;
                    this.importFile    = null;
                    this.previewRows   = [];
                    this.importErrors  = [];

                    Swal.fire({
                        title: 'Import Berhasil! 🎉',
                        html:  `<b>${data.imported}</b> ${entityLabel} berhasil diimport.${data.failed > 0 ? ` <b class="text-red-600">${data.failed}</b> gagal.` : ''}`,
                        icon:  'success',
                        confirmButtonColor: '#4e73df'
                    }).then(() => {
                        // Refresh page or DataTable if available
                        if (typeof $ !== 'undefined' && $.fn.DataTable) {
                            $('table.dataTable').each(function() {
                                try { $(this).DataTable().ajax.reload(); } catch(e) {}
                            });
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire('Import Gagal', data.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
            } finally {
                this.processLoading = false;
            }
        }
    };
}
