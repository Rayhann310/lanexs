const fs = require('fs');
let content = fs.readFileSync('app/Views/packages/index.php', 'utf8');

const oldRute = `                                     <!-- Rute -->
                                    <td class="p-2 border border-slate-200">
                                        <div class="space-y-2 min-w-[160px]">
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Cabang Asal <span class="text-red-400">*</span></label>
                                                <select x-model="pkg.origin_branch_id" @change="calculateMassPrice(idx)" class="w-full text-xs px-2 py-1.5 border border-slate-300 rounded-lg focus:border-indigo-400 outline-none transition bg-white">
                                                    <option value="">-- Pilih Cabang Asal --</option>
                                                    <?php foreach($branches as $b): ?>
                                                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Cabang Tujuan <span class="text-red-400">*</span></label>
                                                <select x-model="pkg.destination_branch_id" @change="calculateMassPrice(idx)" class="w-full text-xs px-2 py-1.5 border border-slate-300 rounded-lg focus:border-indigo-400 outline-none transition bg-white">
                                                    <option value="">-- Pilih Cabang Tujuan --</option>
                                                    <?php foreach($branches as $b): ?>
                                                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>`;

const newRute = `                                     <!-- Rute -->
                                    <td class="p-2 border border-slate-200">
                                        <div class="space-y-2 min-w-[170px]">
                                            <!-- Mode Toggle -->
                                            <div class="flex bg-slate-100 rounded-lg p-0.5 gap-0.5 w-full">
                                                <button type="button" @click="pkg.route_mode='branch'; calculateMassPrice(idx)"
                                                    :class="pkg.route_mode!=='city' ? 'bg-white shadow text-primary font-bold' : 'text-slate-500'"
                                                    class="flex-1 py-1 rounded-md text-[10px] transition-all flex items-center justify-center gap-1">
                                                    <i class="bi bi-building"></i> Cabang
                                                </button>
                                                <button type="button" @click="pkg.route_mode='city'; calculateMassPrice(idx)"
                                                    :class="pkg.route_mode==='city' ? 'bg-white shadow text-indigo-600 font-bold' : 'text-slate-500'"
                                                    class="flex-1 py-1 rounded-md text-[10px] transition-all flex items-center justify-center gap-1">
                                                    <i class="bi bi-geo-alt"></i> Kota
                                                </button>
                                            </div>
                                            
                                            <!-- BRANCH MODE -->
                                            <div x-show="pkg.route_mode!=='city'">
                                                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Cabang Asal <span class="text-red-400">*</span></label>
                                                <select x-model="pkg.origin_branch_id" @change="calculateMassPrice(idx)" class="w-full text-xs px-2 py-1.5 border border-slate-300 rounded-lg focus:border-indigo-400 outline-none transition bg-white">
                                                    <option value="">-- Pilih Cabang --</option>
                                                    <?php foreach($branches as $b): ?>
                                                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div x-show="pkg.route_mode!=='city'">
                                                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Cabang Tujuan <span class="text-red-400">*</span></label>
                                                <select x-model="pkg.destination_branch_id" @change="calculateMassPrice(idx)" class="w-full text-xs px-2 py-1.5 border border-slate-300 rounded-lg focus:border-indigo-400 outline-none transition bg-white">
                                                    <option value="">-- Pilih Cabang --</option>
                                                    <?php foreach($branches as $b): ?>
                                                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- CITY MODE -->
                                            <div x-show="pkg.route_mode==='city'">
                                                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Kota Asal <span class="text-red-400">*</span></label>
                                                <input type="text" list="indonesia_city_list" x-model="pkg.origin_city" @change="calculateMassPrice(idx)" placeholder="Cari kota..." class="w-full text-xs px-2 py-1.5 border border-slate-300 rounded-lg focus:border-indigo-400 outline-none transition bg-white">
                                            </div>
                                            <div x-show="pkg.route_mode==='city'">
                                                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wide mb-0.5">Kota Tujuan <span class="text-red-400">*</span></label>
                                                <input type="text" list="indonesia_city_list" x-model="pkg.destination_city" @change="calculateMassPrice(idx)" placeholder="Cari kota..." class="w-full text-xs px-2 py-1.5 border border-slate-300 rounded-lg focus:border-indigo-400 outline-none transition bg-white">
                                            </div>
                                        </div>
                                    </td>`;

const oldAddPkg = `            addMassPackage() {
                // If there's previous package, copy origin and destination branch for convenience
                let prev = this.massPackages[this.massPackages.length - 1];
                let next = { ...this.defaultFormData };
                if (prev) {
                    next.origin_branch_id = prev.origin_branch_id;
                    next.destination_branch_id = prev.destination_branch_id;
                }
                this.massPackages.push(next);
            },`;

const newAddPkg = `            addMassPackage() {
                // If there's previous package, copy origin and destination branch for convenience
                let prev = this.massPackages[this.massPackages.length - 1];
                let next = { ...this.defaultFormData, route_mode: 'branch' };
                if (prev) {
                    next.route_mode = prev.route_mode || 'branch';
                    next.origin_branch_id = prev.origin_branch_id;
                    next.destination_branch_id = prev.destination_branch_id;
                    next.origin_city = prev.origin_city;
                    next.destination_city = prev.destination_city;
                }
                this.massPackages.push(next);
            },`;

if (content.includes(oldRute)) {
    content = content.replace(oldRute, newRute);
    console.log('✅ Mass resi rute patched');
}
if (content.includes(oldAddPkg)) {
    content = content.replace(oldAddPkg, newAddPkg);
    console.log('✅ addMassPackage patched');
}

// Add datalist at the end of the body
if (!content.includes('id="indonesia_city_list"')) {
    const jsInsertPos = content.indexOf('<script>');
    if (jsInsertPos > -1) {
        content = content.slice(0, jsInsertPos) + `
    <!-- Datalist for Mass Resi City Inputs -->
    <datalist id="indonesia_city_list"></datalist>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof INDONESIA_CITIES !== 'undefined') {
                const dl = document.getElementById('indonesia_city_list');
                let options = '';
                INDONESIA_CITIES.forEach(c => {
                    options += \`<option value="\${c.value}">\${c.label}</option>\`;
                });
                dl.innerHTML = options;
            }
        });
    </script>
` + content.slice(jsInsertPos);
        console.log('✅ Datalist injected');
    }
}

fs.writeFileSync('app/Views/packages/index.php', content, 'utf8');
console.log('Done patch mass resi UI');
