<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Baru</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .page-break { page-break-after: always; }
        .wrapper {
            width: 100%;
            max-width: 80mm;
            margin: 0 auto;
            padding: 5px;
            box-sizing: border-box;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .text-xs { font-size: 9px; }
        .text-sm { font-size: 10px; }
        .text-lg { font-size: 14px; }
        .text-xl { font-size: 18px; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .border-t { border-top: 1px dashed #cbd5e1; }
        .border-b { border-bottom: 1px dashed #cbd5e1; }
        .py-1 { padding-top: 5px; padding-bottom: 5px; }
        .py-2 { padding-top: 10px; padding-bottom: 10px; }
        
        .logo { max-width: 120px; max-height: 40px; margin-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        
        .barcode { text-align: center; margin: 10px 0; }
        .barcode img { max-width: 100%; height: 40px; }
        
        .box {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 5px;
            margin-top: 5px;
        }
        
        .route { font-size: 14px; font-weight: bold; padding: 5px 0; border-top: 2px solid #000; border-bottom: 2px solid #000; margin: 10px 0; text-transform: uppercase; }
        
    </style>
</head>
<body>
    <?php foreach($packages as $i => $pkg): ?>
    <div class="wrapper">
        <!-- Header -->
        <div class="text-center mb-2">
            <?php 
                $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/lanex/public/assets/images/a.png';
                if(!file_exists($logoPath)) $logoPath = dirname(dirname(dirname(__DIR__))) . '/public/assets/images/a.png';
                if(file_exists($logoPath)) {
                    $imgType = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $imgData = file_get_contents($logoPath);
                    $base64 = 'data:image/' . $imgType . ';base64,' . base64_encode($imgData);
                    echo '<img src="'.$base64.'" class="logo">';
                } else {
                    echo '<h2 style="margin:0;">LANEXS</h2>';
                }
            ?>
            <div class="font-bold text-sm">PT. LINTAS AREA NUSANTARA</div>
            <div class="text-xs text-center mt-1">
                Tgl Kirim: <?= date('d M Y', strtotime($pkg['created_at'])) ?>
            </div>
        </div>

        <!-- Barcode -->
        <div class="barcode">
            <?php 
                $resiUrl = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode($pkg['resi']) . "&code=Code128&dpi=96";
                echo '<img src="'.$resiUrl.'" alt="Barcode">';
            ?>
            <div class="font-bold text-lg mt-1"><?= htmlspecialchars($pkg['resi']) ?></div>
        </div>
        
        <!-- Route -->
        <div class="route text-center">
            <?= htmlspecialchars($pkg['origin_city'] ?: ($pkg['origin_branch_name'] ?? '-')) ?> 
            &nbsp;&rarr;&nbsp; 
            <?= htmlspecialchars($pkg['dest_city'] ?: ($pkg['dest_branch_name'] ?? '-')) ?>
        </div>

        <!-- Pengirim & Penerima -->
        <div class="border-t py-1">
            <div class="font-bold">PENGIRIM:</div>
            <div class="font-semibold"><?= htmlspecialchars($pkg['sender_name']) ?></div>
            <div class="text-xs"><?= htmlspecialchars($pkg['sender_phone']) ?></div>
            <div class="text-xs mt-1"><?= htmlspecialchars($pkg['sender_address']) ?></div>
        </div>
        
        <div class="border-t border-b py-1 mb-2">
            <div class="font-bold">PENERIMA:</div>
            <div class="font-semibold text-sm"><?= htmlspecialchars($pkg['receiver_name']) ?></div>
            <div class="text-xs font-bold"><?= htmlspecialchars($pkg['receiver_phone']) ?></div>
            <div class="text-xs mt-1"><?= htmlspecialchars($pkg['receiver_address']) ?></div>
        </div>

        <!-- Info Barang -->
        <div class="box">
            <div class="text-center font-bold mb-1 border-b pb-1">INFORMASI BARANG</div>
            <table class="text-xs">
                <tr>
                    <td><strong>Isi:</strong> <?= htmlspecialchars($pkg['item_type'] ?: '-') ?></td>
                    <td class="text-right"><strong>Layanan:</strong> Paket Darat</td>
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
        <div class="text-xs mt-1">
            <strong>Catatan:</strong> <?= htmlspecialchars($pkg['description']) ?>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="text-center text-xs mt-2 border-t pt-2">
            <div class="font-bold">Lacak pengiriman di:</div>
            <div>www.lanexgroup.com</div>
            <div class="mt-1 font-bold">Terima Kasih</div>
        </div>
    </div>
    <?php if ($i < count($packages) - 1): ?><div class="page-break"></div><?php endif; ?>
    <?php endforeach; ?>
</body>
</html>
