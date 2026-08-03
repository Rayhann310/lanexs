<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Baru</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        @page { size: 100mm 150mm; margin: 0; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 9px;
            color: #000;
            background: #fff;
            width: 100mm;
        }

        /* Receipt border lives INSIDE body padding agar konten tidak terpotong */
        .receipt {
            margin: 2mm;                /* jarak dari tepi kertas */
            border: 1.5px solid #000;
            padding: 2mm 2.5mm;
            overflow: hidden;
            page-break-inside: avoid;
            width: calc(100mm - 4mm);   /* 100mm - 2×margin */
        }

        /* --- Header --- */
        .hdr { text-align: center; margin-bottom: 1mm; }
        .hdr img { max-width: 20mm; max-height: 7mm; display: block; margin: 0 auto 1px; }
        .hdr-company { font-size: 8px; font-weight: 700; line-height: 1.3; }
        .hdr-date { font-size: 7px; }

        /* --- Barcode --- */
        .barcode { text-align: center; margin: 1mm 0; }
        .barcode img { width: 100%; max-height: 9mm; display: block; }
        .barcode-num { font-size: 12px; font-weight: 700; margin-top: 0.5mm; }

        /* --- Route --- */
        .route {
            font-size: 7px; font-weight: 700; text-align: center;
            text-transform: uppercase;
            border-top: 1.5px solid #000; border-bottom: 1.5px solid #000;
            padding: 1.2mm 0; margin: 1.2mm 0;
            word-break: break-all;
        }

        /* --- Sections --- */
        .divider { border-top: 1px dashed #999; margin: 1.2mm 0; }
        .lbl  { font-size: 7px; font-weight: 700; margin-bottom: 0.3mm; }
        .name { font-size: 9px; font-weight: 700; line-height: 1.2; }
        .phone { font-size: 7.5px; }
        .addr  { font-size: 7px; line-height: 1.3; word-break: break-word; }

        /* --- Info Barang --- */
        .box {
            border: 1px solid #bbb; border-radius: 2px;
            padding: 1mm 2mm; margin-top: 1.2mm;
            font-size: 7.5px;
        }
        .box-title {
            text-align: center; font-weight: 700; font-size: 7.5px;
            border-bottom: 1px dashed #aaa;
            margin-bottom: 1mm; padding-bottom: 0.5mm;
        }
        /* Pakai grid 2 baris sederhana, bukan table, agar tidak overflow */
        .info-row {
            display: flex; justify-content: space-between;
            margin-bottom: 0.5mm;
        }
        .info-row span { flex: 1; }
        .info-row span:last-child { text-align: right; flex: 0 0 auto; max-width: 50%; }

        /* --- Footer --- */
        .footer {
            text-align: center; font-size: 7px;
            border-top: 1px solid #000;
            padding-top: 1mm; margin-top: 1.2mm;
        }

        .note { font-size: 7px; margin-top: 1mm; word-break: break-word; }
    </style>
</head>
<body>
<?php foreach($packages as $i => $pkg): ?>
<?php if ($i > 0): ?><div style="page-break-after:always;height:0;overflow:hidden;"></div><?php endif; ?>
<div class="receipt">

    <!-- Header -->
    <div class="hdr">
        <?php
            $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/lanex/public/assets/images/a.png';
            if(!file_exists($logoPath)) $logoPath = dirname(dirname(dirname(__DIR__))) . '/public/assets/images/a.png';
            if(file_exists($logoPath)) {
                $imgType = pathinfo($logoPath, PATHINFO_EXTENSION);
                $base64 = 'data:image/'.$imgType.';base64,'.base64_encode(file_get_contents($logoPath));
                echo '<img src="'.$base64.'">';
            } else { echo '<strong style="font-size:11px;">LANEXS</strong>'; }
        ?>
        <div class="hdr-company">PT. LINTAS AREA NUSANTARA EXPRESS</div>
        <div class="hdr-date">Tgl Kirim: <?= date('d M Y', strtotime($pkg['created_at'])) ?></div>
    </div>

    <!-- Barcode -->
    <div class="barcode">
        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= urlencode($pkg['resi']) ?>&code=Code128&dpi=96" alt="Barcode">
        <div class="barcode-num"><?= htmlspecialchars($pkg['resi']) ?></div>
    </div>

    <!-- Route -->
    <div class="route">
        <?= htmlspecialchars($pkg['origin_city'] ?: ($pkg['branch_origin_city'] ?? ($pkg['origin_branch_name'] ?? '-'))) ?>
        &rarr;
        <?= htmlspecialchars($pkg['destination_city'] ?: ($pkg['dest_city'] ?? ($pkg['branch_dest_city'] ?? ($pkg['dest_branch_name'] ?? '-')))) ?>
    </div>

    <!-- Pengirim -->
    <div class="lbl">PENGIRIM:</div>
    <div class="name"><?= htmlspecialchars($pkg['sender_name']) ?></div>
    <div class="phone"><?= htmlspecialchars($pkg['sender_phone']) ?></div>
    <div class="addr"><?= htmlspecialchars($pkg['sender_address']) ?></div>

    <div class="divider"></div>

    <!-- Penerima -->
    <div class="lbl">PENERIMA:</div>
    <div class="name"><?= htmlspecialchars($pkg['receiver_name']) ?></div>
    <div class="phone"><?= htmlspecialchars($pkg['receiver_phone']) ?></div>
    <div class="addr"><?= htmlspecialchars($pkg['receiver_address']) ?></div>

    <!-- Info Barang -->
    <div class="box">
        <div class="box-title">INFORMASI BARANG</div>
        <div class="info-row">
            <span><strong>Isi:</strong> <?= htmlspecialchars($pkg['item_type'] ?: '-') ?></span>
            <span><strong>Layanan:</strong> <?= htmlspecialchars($pkg['service_name'] ?: 'Reguler') ?></span>
        </div>
        <div class="info-row">
            <span><strong>Berat:</strong> <?= (float)$pkg['weight'] ?> kg</span>
            <span><strong>Koli:</strong> <?= (int)$pkg['koli'] ?> pcs</span>
        </div>
        <div><strong>Volume:</strong> <?= number_format(($pkg['length']*$pkg['width']*$pkg['height'])/1000000, 4) ?> m&sup3;</div>
    </div>

    <?php if(!empty($pkg['description'])): ?>
    <div class="note"><strong>Catatan:</strong> <?= htmlspecialchars($pkg['description']) ?></div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <strong>Lacak: www.lanexgroup.com</strong> &nbsp;|&nbsp; <strong>Terima Kasih</strong>
    </div>

</div>
<?php endforeach; ?>
</body>
</html>
