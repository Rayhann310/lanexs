<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Lama</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; }
        .page-break { page-break-after: always; }
        .wrapper { width: 100%; border: 2px solid #000; box-sizing: border-box; margin-bottom: 20px; }
        .header { display: flex; border-bottom: 2px solid #000; }
        .logo { width: 30%; border-right: 2px solid #000; text-align: center; padding: 5px; }
        .logo img { max-height: 50px; }
        .company-name { width: 70%; text-align: center; font-weight: bold; font-size: 14px; padding-top: 15px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .no-border-top { border-top: none; }
        .no-border-bottom { border-bottom: none; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        .resi-header { font-size: 14px; font-weight: bold; }
        
        .footer-note { text-align: center; border-top: 2px solid #000; font-size: 10px; padding: 5px; background: #fff; }
    </style>
</head>
<body>
    <?php foreach($packages as $i => $pkg): ?>
    <div class="wrapper">
        <table class="no-border-top no-border-bottom">
            <tr>
                <td style="width: 30%; border: none; border-right: 2px solid #000; border-bottom: 2px solid #000; padding: 5px;">
                    <?php 
                        $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/lanex/public/assets/images/a.png';
                        if(!file_exists($logoPath)) $logoPath = dirname(dirname(dirname(__DIR__))) . '/public/assets/images/a.png';
                        if(file_exists($logoPath)) {
                            $imgType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $imgData = file_get_contents($logoPath);
                            $base64 = 'data:image/' . $imgType . ';base64,' . base64_encode($imgData);
                            echo '<img src="'.$base64.'" style="max-height:50px;">';
                        } else {
                            echo "<strong>LANEXS</strong>";
                        }
                    ?>
                </td>
                <td style="width: 70%; border: none; border-bottom: 2px solid #000; font-size: 16px; font-weight: bold;">
                    PT. LINTAS AREA NUSANTARA
                </td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td colspan="3" class="resi-header no-border-top" style="width: 50%;">
                    <?= htmlspecialchars($pkg['resi']) ?>
                </td>
                <td colspan="2" class="no-border-top" style="width: 50%;">
                    <div style="border-bottom: 1px solid #000; padding-bottom: 2px; font-weight: bold;">
                        Tgl Kirim : <?= date('Y-m-d', strtotime($pkg['created_at'])) ?>
                    </div>
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td style="width: 50%; border: none; border-right: 1px solid #000; font-weight: bold;">ORIGIN</td>
                            <td style="width: 50%; border: none; font-weight: bold;">DESTINATION</td>
                        </tr>
                        <tr>
                            <td style="border: none; border-right: 1px solid #000;">
                                <?= htmlspecialchars($pkg['origin_city'] ?: ($pkg['origin_branch_name'] ?? '-')) ?>
                            </td>
                            <td style="border: none;">
                                <?= htmlspecialchars($pkg['dest_city'] ?: ($pkg['dest_branch_name'] ?? '-')) ?>
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
                <td class="font-bold" style="width: 15%;">Nama</td>
                <td style="width: 35%;"><?= htmlspecialchars($pkg['sender_name']) ?></td>
                <td class="font-bold" style="width: 15%;">Nama</td>
                <td style="width: 35%;"><?= htmlspecialchars($pkg['receiver_name']) ?></td>
            </tr>
            <tr>
                <td class="font-bold">Alamat</td>
                <td style="font-size: 10px;"><?= htmlspecialchars($pkg['sender_address']) ?></td>
                <td class="font-bold">Alamat</td>
                <td style="font-size: 10px;"><?= htmlspecialchars($pkg['receiver_address']) ?></td>
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
                <td style="height: 60px;"></td>
                <td style="height: 60px;"></td>
                <td style="height: 60px;"></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td class="font-bold no-border-top text-left" style="padding-left: 10px;">
                    Remarks : <?= htmlspecialchars($pkg['description'] ?? '') ?>
                </td>
            </tr>
        </table>
        
        <div class="footer-note">
            Lacak Paketmu Di "lanexgroup.com"<br>
            Terimakasih Telah Percaya Dengan Layanan Kami :)
        </div>
    </div>
    <?php if ($i < count($packages) - 1): ?><div class="page-break"></div><?php endif; ?>
    <?php endforeach; ?>
</body>
</html>
