<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi: <?= htmlspecialchars($package['resi']) ?></title>
    <style>
        /* Thermal size usually 100x150mm */
        @page { size: 100mm 150mm; margin: 10px; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 11px; color: #000; margin: 0; }
        .no-print { display: <?= isset($isPdf) && $isPdf ? 'none' : 'block' ?>; text-align: center; margin-bottom: 20px; }
        .btn { background: #3b82f6; color: #fff; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px; }
        .btn-close { background: #e5e7eb; color: #374151; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        
        .border-bottom { border-bottom: 2px dotted #000; padding-bottom: 5px; margin-bottom: 5px; }
        .border-top { border-top: 2px dotted #000; padding-top: 5px; margin-top: 5px; }
        
        .header-title { font-size: 16px; font-weight: bold; margin: 0; }
        .header-sub { font-size: 10px; font-weight: bold; }
        
        .route-table { margin: 10px 0; }
        .route-box { border: 1px solid #000; padding: 5px; text-align: center; }
        .route-code { font-size: 18px; font-weight: bold; }
        
        .barcode-area { text-align: center; padding: 10px 0; }
        .barcode-text { font-size: 14px; font-weight: bold; letter-spacing: 2px; }
        
        .payment-box { border: 2px solid #000; padding: 2px 5px; font-weight: bold; text-align: center; display: inline-block; }
    </style>
</head>
<body>
    <?php if(!isset($isPdf) || !$isPdf): ?>
    <div class="no-print">
        <a href="?pdf=1" onclick="window.print(); return false;" class="btn">Cetak Resi (Thermal)</a>
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
            'DELIVERY' => 'Dalam Pengiriman',
            'SELESAI' => 'Terkirim',
            'RETUR' => 'Dikembalikan',
            'MANIFESTED' => 'Sedang Dikirim (Manifest)'
        ];
        $currentStatus = $statusLabels[$package['status']] ?? $package['status'];
    ?>

    <div class="border-bottom">
        <table>
            <tr>
                <td width="60%">
                    <h1 class="header-title"><?= APP_NAME ?></h1>
                    <div class="header-sub">RESI PENGIRIMAN</div>
                </td>
                <td width="40%" style="text-align: right;">
                    <strong><?= date('d M Y', strtotime($package['created_at'])) ?></strong><br>
                    <div class="payment-box"><?= $package['payment_type'] ?></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="barcode-area border-bottom">
        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= urlencode($package['resi']) ?>&code=Code128&dpi=96&hidehrt=1" alt="Barcode" style="max-width: 100%; height: 40px; margin-bottom: 5px;">
        <div class="barcode-text"><?= htmlspecialchars($package['resi']) ?></div>
    </div>

    <table class="route-table border-bottom">
        <tr>
            <td width="50%" style="padding-right: 5px;">
                <div class="route-box">
                    <div style="font-size: 8px;">ASAL</div>
                    <div class="route-code"><?= substr($package['origin_city'] ?? 'XXX', 0, 3) ?></div>
                    <div style="font-size: 9px;"><?= htmlspecialchars($package['origin_branch_name'] ?? '-') ?></div>
                </div>
            </td>
            <td width="50%" style="padding-left: 5px;">
                <div class="route-box">
                    <div style="font-size: 8px;">TUJUAN</div>
                    <div class="route-code"><?= substr($package['dest_city'] ?? 'XXX', 0, 3) ?></div>
                    <div style="font-size: 9px;"><?= htmlspecialchars($package['dest_branch_name'] ?? '-') ?></div>
                </div>
            </td>
        </tr>
    </table>

    <table class="border-bottom">
        <tr>
            <td width="50%">
                <div style="font-size: 9px;">PENERIMA:</div>
                <strong style="font-size: 13px;"><?= htmlspecialchars($package['receiver_name']) ?></strong><br>
                <?= htmlspecialchars($package['receiver_phone']) ?><br>
                <div style="font-size: 10px; margin-top: 5px;"><?= htmlspecialchars($package['receiver_address']) ?></div>
            </td>
            <td width="50%">
                <div style="font-size: 9px;">PENGIRIM:</div>
                <strong><?= htmlspecialchars($package['sender_name']) ?></strong><br>
                <?= htmlspecialchars($package['sender_phone']) ?><br>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td>
                Jenis: <?= htmlspecialchars($package['item_type'] ?? 'UMUM') ?><br>
                Koli: <?= $package['koli'] ?? 1 ?> pcs<br>
                Berat: <?= $package['weight'] ?> Kg<br>
                <?php if (!empty($package['volume']) && $package['volume'] > 0): ?>
                Volume: <?= number_format($package['volume'], 4) ?> m³<br>
                <?php endif; ?>
                Status: <?= $currentStatus ?>
            </td>
            <td style="text-align: right;">
                Ongkir: Rp <?= number_format($package['price'], 0, ',', '.') ?><br>
                <?php if($package['payment_status'] === 'COD'): ?>
                    <strong style="border: 1px solid #000; padding: 2px;">COD: Rp <?= number_format($package['price'], 0, ',', '.') ?></strong>
                <?php else: ?>
                    <strong><?= $package['payment_status'] ?></strong>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="border-top" style="text-align: center; margin-top: 15px; padding-top: 10px;">
        <div style="font-size: 9px;">Tanda Tangan Penerima</div>
        <div style="height: 30px;"></div>
    </div>
</body>
</html>
