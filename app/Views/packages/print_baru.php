<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Baru</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        /* === XPRINTER B420 - 100mm x 150mm === */
        @page {
            size: 100mm 150mm;
            margin: 0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 9px;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .page-break { page-break-after: always; }

        /* Wrapper = persis satu halaman kertas */
        .wrapper {
            width: 100mm;
            height: 150mm;
            padding: 3mm 4mm 2mm 4mm; /* top right bottom left */
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ---- Header ---- */
        .hdr { text-align: center; margin-bottom: 1mm; }
        .hdr img { max-width: 22mm; max-height: 7mm; }
        .hdr-company { font-size: 8px; font-weight: 700; line-height: 1.2; }
        .hdr-date { font-size: 7px; }

        /* ---- Barcode ---- */
        .barcode { text-align: center; margin: 1mm 0 0.5mm; }
        .barcode img { max-width: 100%; height: 8mm; }
        .barcode-num { font-size: 12px; font-weight: 700; }

        /* ---- Route ---- */
        .route {
            font-size: 7px; font-weight: 700;
            text-align: center; text-transform: uppercase;
            border-top: 1.5px solid #000; border-bottom: 1.5px solid #000;
            padding: 1mm 0; margin: 1mm 0;
        }

        /* ---- Sections ---- */
        .divider { border-top: 1px dashed #888; margin: 1mm 0; }
        .lbl   { font-size: 7px; font-weight: 700; margin: 0; line-height: 1.3; }
        .name  { font-size: 9px; font-weight: 700; line-height: 1.2; }
        .phone { font-size: 7.5px; }
        .addr  { font-size: 7px; line-height: 1.2; }

        /* ---- Info Barang ---- */
        .box {
            border: 1px solid #aaa;
            border-radius: 2px;
            padding: 1mm 2mm;
            margin-top: 1mm;
            font-size: 7.5px;
        }
        .box-title {
            text-align: center; font-weight: 700; font-size: 8px;
            border-bottom: 1px dashed #999; margin-bottom: 1mm; padding-bottom: 0.5mm;
        }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 0; }
        .tr { text-align: right; }

        /* ---- Footer ---- */
        .footer {
            margin-top: auto;
            text-align: center;
            font-size: 7px;
            border-top: 1px solid #000;
            padding-top: 1mm;
        }
    </style>
</head>
<body>
<?php foreach($packages as $i => $pkg): ?>
<div class="wrapper">

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
        &nbsp;&rarr;&nbsp;
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
        <table>
            <tr>
                <td><strong>Isi:</strong> <?= htmlspecialchars($pkg['item_type'] ?: '-') ?></td>
                <td class="tr"><strong>Layanan:</strong> <?= htmlspecialchars($pkg['service_name'] ?: 'Reguler') ?></td>
            </tr>
            <tr>
                <td><strong>Berat:</strong> <?= (float)$pkg['weight'] ?> kg</td>
                <td class="tr"><strong>Koli:</strong> <?= (int)$pkg['koli'] ?> pcs</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Volume:</strong> <?= number_format(($pkg['length']*$pkg['width']*$pkg['height'])/1000000, 4) ?> m&sup3;</td>
            </tr>
        </table>
    </div>

    <?php if(!empty($pkg['description'])): ?>
    <div class="addr" style="margin-top:1mm;"><strong>Catatan:</strong> <?= htmlspecialchars($pkg['description']) ?></div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <strong>Lacak: www.lanexgroup.com</strong> &nbsp;|&nbsp; <strong>Terima Kasih</strong>
    </div>

</div>
<?php if ($i < count($packages) - 1): ?><div class="page-break"></div><?php endif; ?>
<?php endforeach; ?>
</body>
</html>
