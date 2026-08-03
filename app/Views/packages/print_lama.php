<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Lama</title>
    <style>
        @page { size: 100mm 150mm; margin: 0; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9px;
            background: #fff;
            width: 100mm;
        }

        /* Receipt border INSIDE body padding */
        .receipt {
            margin: 2mm;
            border: 1.5px solid #000;
            padding: 1.5mm;
            overflow: hidden;
            page-break-inside: avoid;
            width: calc(100mm - 4mm);
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 1px 2px; text-align: center; font-size: 8.5px; }
        .no-bt { border-top: none; }
        .no-bb { border-bottom: none; }
        .tl { text-align: left; }
        .bold { font-weight: bold; }

        /* Header row */
        .hdr-logo-td {
            width: 28%;
            border: none !important;
            border-right: 1.5px solid #000 !important;
            border-bottom: 1.5px solid #000 !important;
            text-align: center;
            padding: 2px !important;
        }
        .hdr-name-td {
            border: none !important;
            border-bottom: 1.5px solid #000 !important;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            padding: 3px 2px !important;
        }

        .resi-big { font-size: 12px; font-weight: bold; text-align: left; padding: 2px 3px !important; }
        .ttd-cell { height: 35px; }
        .footer-note {
            text-align: center;
            border-top: 1.5px solid #000;
            font-size: 7.5px;
            padding: 2px;
            word-break: break-word;
        }
    </style>
</head>
<body>
<?php foreach($packages as $i => $pkg): ?>
<?php if ($i > 0): ?><div style="page-break-after:always;height:0;overflow:hidden;"></div><?php endif; ?>
<div class="receipt">

    <!-- Header: Logo + Nama -->
    <table>
        <tr>
            <td class="hdr-logo-td">
                <?php
                    $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/lanex/public/assets/images/a.png';
                    if(!file_exists($logoPath)) $logoPath = dirname(dirname(dirname(__DIR__))) . '/public/assets/images/a.png';
                    if(file_exists($logoPath)) {
                        $imgType = pathinfo($logoPath, PATHINFO_EXTENSION);
                        $base64 = 'data:image/'.$imgType.';base64,'.base64_encode(file_get_contents($logoPath));
                        echo '<img src="'.$base64.'" style="max-height:22px; max-width:100%;">';
                    } else { echo '<strong>LANEXS</strong>'; }
                ?>
            </td>
            <td class="hdr-name-td">PT. LINTAS AREA NUSANTARA EXPRESS</td>
        </tr>
    </table>

    <!-- Resi + Tanggal + Origin/Dest -->
    <table>
        <tr>
            <td colspan="3" class="resi-big no-bt" style="width:50%;">
                <?= htmlspecialchars($pkg['resi']) ?>
            </td>
            <td colspan="2" class="no-bt" style="width:50%; padding:1px 2px; font-size:7.5px;">
                <div style="border-bottom:1px solid #000; font-weight:bold; padding-bottom:1px;">
                    Tgl : <?= date('Y-m-d', strtotime($pkg['created_at'])) ?>
                </div>
                <table style="width:100%; border:none; margin-top:1px;">
                    <tr>
                        <td style="width:50%; border:none; border-right:1px solid #000; font-weight:bold; font-size:7px;">ORIGIN</td>
                        <td style="width:50%; border:none; font-weight:bold; font-size:7px;">DESTINATION</td>
                    </tr>
                    <tr>
                        <td style="border:none; border-right:1px solid #000; font-size:7px; text-align:left;">
                            <?= htmlspecialchars($pkg['origin_city'] ?: ($pkg['branch_origin_city'] ?? ($pkg['origin_branch_name'] ?? '-'))) ?>
                        </td>
                        <td style="border:none; font-size:7px; text-align:left;">
                            <?= htmlspecialchars($pkg['destination_city'] ?: ($pkg['dest_city'] ?? ($pkg['branch_dest_city'] ?? ($pkg['dest_branch_name'] ?? '-')))) ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Kilo / Koli / Volume / Nama Barang -->
        <tr>
            <td class="bold">Kilo</td>
            <td class="bold">Koli</td>
            <td class="bold">Volume</td>
            <td rowspan="2" class="bold">Nama<br>Barang</td>
            <td rowspan="2"><?= htmlspecialchars($pkg['item_type'] ?: '-') ?></td>
        </tr>
        <tr>
            <td><?= (float)$pkg['weight'] ?> kg</td>
            <td><?= (int)$pkg['koli'] ?></td>
            <td><?= number_format(($pkg['length']*$pkg['width']*$pkg['height'])/1000000, 4) ?> m³</td>
        </tr>

        <!-- Jenis / Pembayaran / Service -->
        <tr>
            <td class="bold">Jenis<br>Kiriman</td>
            <td>Paket</td>
            <td class="bold">Pembayaran</td>
            <td><?= htmlspecialchars($pkg['payment_type']) ?></td>
            <td><strong>Service Via</strong><br><?= htmlspecialchars($pkg['service_name'] ?: 'Darat') ?></td>
        </tr>
    </table>

    <!-- Pengirim & Penerima -->
    <table>
        <tr>
            <td colspan="2" class="bold no-bt" style="width:50%;">Pengirim</td>
            <td colspan="2" class="bold no-bt" style="width:50%;">Penerima</td>
        </tr>
        <tr>
            <td class="bold" style="width:12%;">Nama</td>
            <td style="width:38%; text-align:left; font-size:8px; word-break:break-word;"><?= htmlspecialchars($pkg['sender_name']) ?></td>
            <td class="bold" style="width:12%;">Nama</td>
            <td style="width:38%; text-align:left; font-size:8px; word-break:break-word;"><?= htmlspecialchars($pkg['receiver_name']) ?></td>
        </tr>
        <tr>
            <td class="bold">Alamat</td>
            <td style="font-size:7px; text-align:left; word-break:break-word;"><?= htmlspecialchars($pkg['sender_address']) ?></td>
            <td class="bold">Alamat</td>
            <td style="font-size:7px; text-align:left; word-break:break-word;"><?= htmlspecialchars($pkg['receiver_address']) ?></td>
        </tr>
        <tr>
            <td class="bold">No.Telp</td>
            <td><?= htmlspecialchars($pkg['sender_phone']) ?></td>
            <td class="bold">No.Telp</td>
            <td><?= htmlspecialchars($pkg['receiver_phone']) ?></td>
        </tr>
    </table>

    <!-- TTD -->
    <table>
        <tr>
            <td class="bold no-bt" style="width:33.3%;">TTD Pengirim</td>
            <td class="bold no-bt" style="width:33.3%;">TTD Petugas</td>
            <td class="bold no-bt" style="width:33.3%;">TTD Penerima</td>
        </tr>
        <tr>
            <td class="ttd-cell"></td>
            <td class="ttd-cell"></td>
            <td class="ttd-cell"></td>
        </tr>
    </table>

    <!-- Remarks -->
    <table>
        <tr>
            <td class="bold no-bt tl" style="padding-left:4px; font-size:8px; word-break:break-word;">
                Remarks : <?= htmlspecialchars($pkg['description'] ?? '') ?>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer-note">
        Lacak Paketmu Di "lanexgroup.com" | Terimakasih Telah Percaya Dengan Layanan Kami :)
    </div>

</div>
<?php endforeach; ?>
</body>
</html>
