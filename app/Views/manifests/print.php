<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan: <?= htmlspecialchars($manifest['manifest_code']) ?></title>
    <style>
        @page { margin: 20px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; margin: 0; }
        .no-print { display: <?= isset($isPdf) && $isPdf ? 'none' : 'block' ?>; text-align: center; margin-bottom: 20px; }
        .btn { background: #3b82f6; color: #fff; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px; }
        .btn-close { background: #e5e7eb; color: #374151; }
        
        table { width: 100%; border-collapse: collapse; }
        .header-table { margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header-table td { vertical-align: top; }
        
        .logo { width: 60px; height: auto; }
        .company-name { font-size: 24px; font-weight: bold; margin: 0; }
        .company-sub { font-size: 10px; font-weight: bold; color: #555; }
        .company-addr { font-size: 10px; color: #777; }
        
        .title { text-align: right; font-size: 20px; font-weight: bold; text-transform: uppercase; }
        
        .info-box { border: 1px solid #ccc; background: #f9fafb; margin-bottom: 20px; }
        .info-box td { padding: 5px 10px; }
        .info-label { font-weight: bold; color: #555; width: 100px; }
        
        .data-table { margin-bottom: 20px; }
        .data-table th { background: #f3f4f6; border: 1px solid #ccc; padding: 8px; text-align: center; }
        .data-table td { border: 1px solid #ccc; padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .signatures { margin-top: 50px; }
        .signatures td { text-align: center; width: 33%; vertical-align: bottom; height: 100px; }
        .sign-line { border-bottom: 1px solid #000; margin: 0 20px 5px 20px; }
        .sign-title { font-weight: bold; margin-bottom: 40px; }
        .sign-name { font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <?php if(!isset($isPdf) || !$isPdf): ?>
    <div class="no-print">
        <a href="?pdf=1" onclick="window.print(); return false;" class="btn">Cetak Surat Jalan (A4)</a>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>
    <?php endif; ?>

    <?php
        $statusLabels = [
            'PENDING' => 'Menunggu Penjemputan',
            'PICKUP' => 'Dijemput Kurir',
            'GUDANG_ASAL' => 'Di Gudang Asal',
            'TRANSIT' => 'Sedang Transit',
            'GUDANG_TUJUAN' => 'Di Gudang Tujuan',
            'DELIVERY' => 'Dalam Pengiriman (Kurir)',
            'SELESAI' => 'Selesai / Terkirim',
            'RETUR' => 'Dikembalikan',
            'MANIFESTED' => 'Sedang Dikirim (Manifest)'
        ];
        $currentStatus = $statusLabels[$manifest['status']] ?? $manifest['status'];
    ?>

    <table class="header-table">
        <tr>
            <td width="70">
                <!-- Fallback to text if image fails in dompdf -->
                <h1 style="margin:0; font-size: 30px;"><?= explode(' ', APP_NAME)[0] ?>.</h1>
            </td>
            <td>
                <h1 class="company-name"><?= APP_NAME ?></h1>
                <div class="company-sub">PT <?= strtoupper(explode(' ', APP_NAME)[0]) ?> EXPRESS INDONESIA</div>
                <div class="company-addr">Jl. Sudirman No.123, Jakarta Selatan<br>Telp: (021) 1234567</div>
            </td>
            <td class="title">
                Surat Jalan (Manifest)<br>
                <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= urlencode($manifest['manifest_code']) ?>&code=Code128&dpi=96&hidehrt=1" alt="Barcode" style="margin-top: 10px; max-width: 250px; height: 35px;"><br>
                <div style="font-size: 14px; margin-top: 5px; color:#333; font-family: monospace;"><?= htmlspecialchars($manifest['manifest_code']) ?></div>
            </td>
        </tr>
    </table>

    <table class="info-box">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr><td class="info-label">Tanggal</td><td>: <?= date('d M Y, H:i', strtotime($manifest['created_at'])) ?></td></tr>
                    <tr><td class="info-label">Cabang Asal</td><td>: <?= htmlspecialchars($manifest['origin_branch_name']) ?></td></tr>
                    <tr><td class="info-label">Cabang Tujuan</td><td>: <?= htmlspecialchars($manifest['dest_branch_name']) ?></td></tr>
                </table>
            </td>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr><td class="info-label">No. Kendaraan</td><td>: <strong style="text-transform: uppercase;"><?= htmlspecialchars($manifest['vehicle_plate'] ?? '-') ?></strong></td></tr>
                    <tr><td class="info-label">Nama Supir</td><td>: <strong style="text-transform: uppercase;"><?= htmlspecialchars($manifest['driver_name'] ?? '-') ?></strong></td></tr>
                    <tr><td class="info-label">Status</td><td>: <strong><?= $currentStatus ?></strong></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <h3 style="margin-bottom: 10px; font-size:14px;">Daftar Muatan (Karung / Bag)</h3>
    
    <table class="data-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th class="text-left">Kode Karung (Bag Code)</th>
                <th width="100">Jumlah Paket</th>
                <th width="120" class="text-right">Estimasi Berat (Kg)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bags)): ?>
            <tr>
                <td colspan="4" class="text-center" style="color: #777; font-style: italic; padding: 20px;">Tidak ada data muatan.</td>
            </tr>
            <?php else: ?>
                <?php 
                    $totalPkgs = 0; 
                    $totalWeight = 0;
                    foreach ($bags as $i => $bag): 
                        $totalPkgs += $bag['total_packages'];
                        $totalWeight += $bag['weight'];
                ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td class="text-left" style="font-family: monospace; font-weight: bold; font-size: 14px;"><?= htmlspecialchars($bag['bag_code']) ?></td>
                    <td class="text-center"><?= $bag['total_packages'] ?></td>
                    <td class="text-right"><?= number_format($bag['weight'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr style="background: #f9fafb; font-weight: bold;">
                <td colspan="2" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-center"><?= $totalPkgs ?? 0 ?> Paket</td>
                <td class="text-right"><?= number_format($totalWeight ?? 0, 2, ',', '.') ?> Kg</td>
            </tr>
        </tfoot>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <div class="sign-title">Petugas Asal (Dispatcher)</div>
                <div class="sign-line"></div>
                <div class="sign-name">Nama & Tanda Tangan</div>
            </td>
            <td>
                <div class="sign-title">Supir / Kurir (Driver)</div>
                <div class="sign-line"></div>
                <div class="sign-name">Nama & Tanda Tangan</div>
            </td>
            <td>
                <div class="sign-title">Petugas Tujuan (Receiver)</div>
                <div class="sign-line"></div>
                <div class="sign-name">Nama, Tanda Tangan & Stempel</div>
            </td>
        </tr>
    </table>

</body>
</html>
