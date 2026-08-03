<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi Lama</title>
    <style>
        /* === XPRINTER B420 - 100mm x 150mm === */
        @page {
            size: 100mm 150mm;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9px;
            background: #fff;
        }

        .page-break { page-break-after: always; }

        /* Wrapper = persis satu halaman kertas.
           height/min-height/max-height dipaksa sama semua supaya tidak
           membesar/mengecil mengikuti isi konten. */
        .wrapper {
            width: 96mm;
            height: 150mm;
            min-height: 150mm;
            max-height: 150mm;
            padding: 2mm 3mm 1mm 3mm;
            overflow: hidden;
            border: none;
            margin: 0;
        }

        /* Border luar seluruh wrapper.
           position:relative supaya jadi anchor footer absolute di bawah,
           bukan pakai flex + margin-top:auto (sering tidak konsisten
           di berbagai browser/print-engine sehingga footer hilang/geser). */
        .inner {
            position: relative;
            width: 100%;
            height: 100%;
            border: 1.5px solid #000;
            overflow: hidden;
            padding-bottom: 10mm; /* ruang aman utk footer absolute */
        }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
            font-size: 8.5px;
            word-break: break-word;
        }
        .no-bt { border-top: none; }
        .no-bb { border-bottom: none; }
        .no-bl { border-left: none; }
        .no-br { border-right: none; }
        .tl { text-align: left; }
        .bold { font-weight: bold; }

        .hdr-logo td { border: none; border-bottom: 1.5px solid #000; padding: 2px; }
        .resi-big { font-size: 13px; font-weight: bold; padding: 2px 3px; }
        .ttd-cell { height: 40px; }

        .footer-note {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            text-align: center;
            border-top: 1.5px solid #000;
            font-size: 8px;
            padding: 2px;
            background: #fff;
        }
    </style>
</head>
<body>
<?php foreach($packages as $i => $pkg): ?>
<div class="wrapper">
<div class="inner">

    <!-- Header: Logo + Nama Perusahaan -->
    <table class="hdr-logo">
        <tr>
            <td style="width:28%; border-right:1.5px solid #000; text-align:center;">
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
            <td style="width:72%; font-size:12px; font-weight:bold; text-align:center;">
                PT. LINTAS AREA NUSANTARA EXPRESS
            </td>
        </tr>
    </table>

    <!-- Resi + Tgl + Origin/Dest -->
    <table>
        <tr>
            <td colspan="3" class="resi-big no-bt" style="width:50%; text-align:left;">
                <?= htmlspecialchars($pkg['resi']) ?>
            </td>
            <td colspan="2" class="no-bt" style="width:50%; padding:1px 2px;">
                <div style="border-bottom:1px solid #000; font-weight:bold; font-size:8px; padding-bottom:1px;">
                    Tgl : <?= date('Y-m-d', strtotime($pkg['created_at'])) ?>
                </div>
                <table style="width:100%; border:none; margin-top:1px;">
                    <tr>
                        <td style="width:50%; border:none; border-right:1px solid #000; font-weight:bold; font-size:7.5px;">ORIGIN</td>
                        <td style="width:50%; border:none; font-weight:bold; font-size:7.5px;">DESTINATION</td>
                    </tr>
                    <tr>
                        <td style="border:none; border-right:1px solid #000; font-size:7.5px;">
                            <?= htmlspecialchars($pkg['origin_city'] ?: ($pkg['branch_origin_city'] ?? ($pkg['origin_branch_name'] ?? '-'))) ?>
                        </td>
                        <td style="border:none; font-size:7.5px;">
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
            <td style="width:38%; text-align:left; font-size:8px;"><?= htmlspecialchars($pkg['sender_name']) ?></td>
            <td class="bold" style="width:12%;">Nama</td>
            <td style="width:38%; text-align:left; font-size:8px;"><?= htmlspecialchars($pkg['receiver_name']) ?></td>
        </tr>
        <tr>
            <td class="bold">Alamat</td>
            <td style="font-size:7.5px; text-align:left;"><?= htmlspecialchars($pkg['sender_address']) ?></td>
            <td class="bold">Alamat</td>
            <td style="font-size:7.5px; text-align:left;"><?= htmlspecialchars($pkg['receiver_address']) ?></td>
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
            <td class="bold no-bt tl" style="padding-left:4px;">
                Remarks : <?= htmlspecialchars($pkg['description'] ?? '') ?>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer-note">
        Lacak Paketmu Di "lanexgroup.com" | Terimakasih Telah Percaya Dengan Layanan Kami :)
    </div>

</div>
</div>
<?php if ($i < count($packages) - 1): ?><div class="page-break"></div><?php endif; ?>
<?php endforeach; ?>
</body>
</html>