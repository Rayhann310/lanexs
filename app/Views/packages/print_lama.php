<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Lama</title>
    <style>
        /* XPrinter B420: 100mm x 150mm */
        @page { 
            size: 100mm 150mm; 
            margin: 2mm 2mm 0mm 2mm;
        }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9px; margin: 0; padding: 0; background: #fff; }
        .page-break { page-break-after: always; }
        .wrapper { 
            width: 96mm; 
            border: 2px solid #000; 
            box-sizing: border-box; 
            page-break-inside: avoid;
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 1px 2px; text-align: center; font-size: 9px; }
        .no-border-top { border-top: none; }
        .no-border-bottom { border-bottom: none; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .resi-header { font-size: 12px; font-weight: bold; padding: 3px; }
        .footer-note { text-align: center; border-top: 2px solid #000; font-size: 8px; padding: 2px; background: #fff; }
    </style>
</head>
<body>
    <?php foreach($packages as $i => $pkg): ?>
    <div class="wrapper">
        <table class="no-border-top no-border-bottom">
            <tr>
                <td style="width: 28%; border: none; border-right: 2px solid #000; border-bottom: 2px solid #000; padding: 2px; text-align:center;">
                    <?php 
                        $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/lanex/public/assets/images/a.png';
                        if(!file_exists($logoPath)) $logoPath = dirname(dirname(dirname(__DIR__))) . '/public/assets/images/a.png';
                        if(file_exists($logoPath)) {
                            $imgType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $imgData = file_get_contents($logoPath);
                            $base64 = 'data:image/' . $imgType . ';base64,' . base64_encode($imgData);
                            echo '<img src="'.$base64.'" style="max-height:30px; max-width:100%;">';
                        } else {
                            echo "<strong>LANEXS</strong>";
                        }
                    ?>
                </td>
                <td style="width: 72%; border: none; border-bottom: 2px solid #000; font-size: 11px; font-weight: bold; text-align:center; padding: 4px 2px;">
                    PT. LINTAS AREA NUSANTARA EXPRESS
                </td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td colspan="3" class="resi-header no-border-top" style="width: 50%; text-align:left;">
                    <?= htmlspecialchars($pkg['resi']) ?>
                </td>
                <td colspan="2" class="no-border-top" style="width: 50%; padding: 1px 2px;">
                    <div style="border-bottom: 1px solid #000; padding-bottom: 1px; font-weight: bold; font-size:8px;">
                        Tgl : <?= date('Y-m-d', strtotime($pkg['created_at'])) ?>
                    </div>
                    <table style="width: 100%; border: none; margin-top:1px;">
                        <tr>
                            <td style="width: 50%; border: none; border-right: 1px solid #000; font-weight: bold; font-size:8px;">ORIGIN</td>
                            <td style="width: 50%; border: none; font-weight: bold; font-size:8px;">DESTINATION</td>
                        </tr>
                        <tr>
                            <td style="border: none; border-right: 1px solid #000; font-size:8px;">
                                <?= htmlspecialchars($pkg['origin_city'] ?: ($pkg['branch_origin_city'] ?? ($pkg['origin_branch_name'] ?? '-'))) ?>
                            </td>
                            <td style="border: none; font-size:8px;">
                                <?= htmlspecialchars($pkg['destination_city'] ?: ($pkg['dest_city'] ?? ($pkg['branch_dest_city'] ?? ($pkg['dest_branch_name'] ?? '-')))) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="font-bold">Kilo</td>
                <td class="font-bold">Koli</td>
                <td class="font-bold">Volume</td>
                <td rowspan="2" class="font-bold">Nama<br>Barang</td>
                <td rowspan="2"><?= htmlspecialchars($pkg['item_type'] ?: '-') ?></td>
            </tr>
            <tr>
                <td><?= (float)$pkg['weight'] ?> kg</td>
                <td><?= (int)$pkg['koli'] ?></td>
                <td><?= number_format(($pkg['length']*$pkg['width']*$pkg['height'])/1000000, 4) ?> m³</td>
            </tr>
            <tr>
                <td class="font-bold">Jenis<br>Kiriman</td>
                <td>Paket</td>
                <td class="font-bold">Pembayaran</td>
                <td><?= htmlspecialchars($pkg['payment_type']) ?></td>
                <td>
                    <strong>Service Via</strong><br><?= htmlspecialchars($pkg['service_name'] ?: 'Darat') ?>
                </td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td colspan="2" class="font-bold no-border-top" style="width: 50%;">Pengirim</td>
                <td colspan="2" class="font-bold no-border-top" style="width: 50%;">Penerima</td>
            </tr>
            <tr>
                <td class="font-bold" style="width: 12%;">Nama</td>
                <td style="width: 38%; text-align:left;"><?= htmlspecialchars($pkg['sender_name']) ?></td>
                <td class="font-bold" style="width: 12%;">Nama</td>
                <td style="width: 38%; text-align:left;"><?= htmlspecialchars($pkg['receiver_name']) ?></td>
            </tr>
            <tr>
                <td class="font-bold">Alamat</td>
                <td style="font-size: 8px; text-align:left;"><?= htmlspecialchars($pkg['sender_address']) ?></td>
                <td class="font-bold">Alamat</td>
                <td style="font-size: 8px; text-align:left;"><?= htmlspecialchars($pkg['receiver_address']) ?></td>
            </tr>
            <tr>
                <td class="font-bold">No.Telp</td>
                <td><?= htmlspecialchars($pkg['sender_phone']) ?></td>
                <td class="font-bold">No.Telp</td>
                <td><?= htmlspecialchars($pkg['receiver_phone']) ?></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td class="font-bold no-border-top" style="width: 33.3%;">TTD Pengirim</td>
                <td class="font-bold no-border-top" style="width: 33.3%;">TTD Petugas</td>
                <td class="font-bold no-border-top" style="width: 33.3%;">TTD Penerima</td>
            </tr>
            <tr>
                <td style="height: 30px;"></td>
                <td style="height: 30px;"></td>
                <td style="height: 30px;"></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td class="font-bold no-border-top text-left" style="padding-left: 4px;">
                    Remarks : <?= htmlspecialchars($pkg['description'] ?? '') ?>
                </td>
            </tr>
        </table>
        
        <div class="footer-note">
            Lacak Paketmu Di "lanexgroup.com" | Terimakasih Telah Percaya Dengan Layanan Kami :)
        </div>
    </div>
    <?php if ($i < count($packages) - 1): ?><div class="page-break"></div><?php endif; ?>
    <?php endforeach; ?>
</body>
</html>
