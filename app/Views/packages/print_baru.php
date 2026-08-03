<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Baru</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        /* XPrinter B420: 100mm x 150mm */
        @page { 
            size: 100mm 150mm; 
            margin: 2mm 2mm 0mm 2mm;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            font-size: 9px;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        
        .page-break { page-break-after: always; }
        
        .wrapper {
            width: 96mm;
            border: 2px solid #000;
            padding: 2mm;
            box-sizing: border-box;
            page-break-inside: avoid;
            overflow: hidden;
        }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        
        .logo { max-width: 80px; max-height: 25px; margin-bottom: 1px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 1px 0; }
        
        .barcode { text-align: center; margin: 2px 0; }
        .barcode img { max-width: 100%; height: 30px; }
        .barcode-num { font-size: 11px; font-weight: 700; margin-top: 1px; }
        
        .route { 
            font-size: 8px; font-weight: bold; 
            padding: 2px 0; 
            border-top: 2px solid #000; border-bottom: 2px solid #000; 
            margin: 2px 0; text-transform: uppercase; 
            text-align: center;
        }
        
        .section-label { font-size: 8px; font-weight: 700; margin: 0; }
        .section-name  { font-size: 9px; font-weight: 600; }
        .section-phone { font-size: 8px; }
        .section-addr  { font-size: 7px; }
        .divider { border-top: 1px dashed #888; margin: 2px 0; }
        
        .box {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 2px 3px;
            margin-top: 2px;
            font-size: 8px;
        }
        .box-title { text-align: center; font-weight: 700; font-size: 8px; border-bottom: 1px dashed #aaa; margin-bottom: 2px; padding-bottom: 1px; }
        
        .footer { text-align: center; font-size: 7px; border-top: 1px solid #000; padding-top: 2px; margin-top: 2px; }
    </style>
</head>
<body>
    <?php foreach($packages as $i => $pkg): ?>
    <div class="wrapper">
        <!-- Header -->
        <div class="text-center" style="margin-bottom:1px;">
            <?php 
                $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/lanex/public/assets/images/a.png';
                if(!file_exists($logoPath)) $logoPath = dirname(dirname(dirname(__DIR__))) . '/public/assets/images/a.png';
                if(file_exists($logoPath)) {
                    $imgType = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $imgData = file_get_contents($logoPath);
                    $base64 = 'data:image/' . $imgType . ';base64,' . base64_encode($imgData);
                    echo '<img src="'.$base64.'" class="logo">';
                } else {
                    echo '<strong style="font-size:12px;">LANEXS</strong>';
                }
            ?>
            <div style="font-size:9px; font-weight:700;">PT. LINTAS AREA NUSANTARA EXPRESS</div>
            <div style="font-size:7px;">Tgl Kirim: <?= date('d M Y', strtotime($pkg['created_at'])) ?></div>
        </div>

        <!-- Barcode -->
        <div class="barcode">
            <?php 
                $resiUrl = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode($pkg['resi']) . "&code=Code128&dpi=96";
                echo '<img src="'.$resiUrl.'" alt="Barcode">';
            ?>
            <div class="barcode-num"><?= htmlspecialchars($pkg['resi']) ?></div>
        </div>
        
        <!-- Route -->
        <div class="route">
            <?= htmlspecialchars($pkg['origin_city'] ?: ($pkg['branch_origin_city'] ?? ($pkg['origin_branch_name'] ?? '-'))) ?> 
            &nbsp;&rarr;&nbsp; 
            <?= htmlspecialchars($pkg['destination_city'] ?: ($pkg['dest_city'] ?? ($pkg['branch_dest_city'] ?? ($pkg['dest_branch_name'] ?? '-')))) ?>
        </div>

        <!-- Pengirim -->
        <div class="divider"></div>
        <div class="section-label">PENGIRIM:</div>
        <div class="section-name"><?= htmlspecialchars($pkg['sender_name']) ?></div>
        <div class="section-phone"><?= htmlspecialchars($pkg['sender_phone']) ?></div>
        <div class="section-addr"><?= htmlspecialchars($pkg['sender_address']) ?></div>
        
        <!-- Penerima -->
        <div class="divider"></div>
        <div class="section-label">PENERIMA:</div>
        <div class="section-name"><?= htmlspecialchars($pkg['receiver_name']) ?></div>
        <div class="section-phone"><?= htmlspecialchars($pkg['receiver_phone']) ?></div>
        <div class="section-addr"><?= htmlspecialchars($pkg['receiver_address']) ?></div>

        <!-- Info Barang -->
        <div class="box">
            <div class="box-title">INFORMASI BARANG</div>
            <table>
                <tr>
                    <td><strong>Isi:</strong> <?= htmlspecialchars($pkg['item_type'] ?: '-') ?></td>
                    <td class="text-right"><strong>Layanan:</strong> <?= htmlspecialchars($pkg['service_name'] ?: 'Reguler') ?></td>
                </tr>
                <tr>
                    <td><strong>Berat:</strong> <?= (float)$pkg['weight'] ?> kg</td>
                    <td class="text-right"><strong>Koli:</strong> <?= (int)$pkg['koli'] ?> pcs</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Volume:</strong> <?= number_format(($pkg['length']*$pkg['width']*$pkg['height'])/1000000, 4) ?> m&sup3;</td>
                </tr>
            </table>
        </div>
        
        <?php if(!empty($pkg['description'])): ?>
        <div style="font-size:7px; margin-top:1px;"><strong>Catatan:</strong> <?= htmlspecialchars($pkg['description']) ?></div>
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
