<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\BukuKeuangan;

class BukuKeuanganController extends BaseController
{
    private function calcRow(array $row): array
    {
        $inv  = (float) $row['tagihan_invoice'];
        $vend = (float) $row['vendor'];
        $ops  = (float) $row['operasional'];
        $pph  = (bool)  $row['is_pph'];
        $ppn  = (bool)  $row['is_ppn'];

        // Cadangan biaya operasional = 1% dari tagihan invoice
        $cadangan = $inv * 0.01;

        // PPH 23 = 2% dari tagihan invoice (jika ada)
        $pph_val = $pph ? ($inv * 0.02) : 0;

        // PPN 1.1% dari tagihan invoice (jika ada)
        $ppn_val = $ppn ? ($inv * 0.011) : 0;

        // Harga Pokok
        $harga_pokok = $vend + $ops + $cadangan + $pph_val + $ppn_val;

        // Profit Sebelum Pajak
        $profit_sblm = $inv - $harga_pokok;

        // PPh Badan 22%
        $pph_badan = $profit_sblm * 0.22;

        // Profit Setelah Pajak
        $profit_stlh = $profit_sblm - $pph_badan;

        // Pembagian
        $komisaris = $profit_stlh * 0.50;
        $direktur  = $profit_stlh * 0.20;
        $marketing = $profit_stlh * 0.20;
        $profit    = $profit_stlh * 0.10;

        $row['cadangan']     = $cadangan;
        $row['pph_val']      = $pph_val;
        $row['ppn_val']      = $ppn_val;
        $row['harga_pokok']  = $harga_pokok;
        $row['profit_sblm']  = $profit_sblm;
        $row['pph_badan']    = $pph_badan;
        $row['profit_stlh']  = $profit_stlh;
        $row['komisaris']    = $komisaris;
        $row['direktur']     = $direktur;
        $row['marketing']    = $marketing;
        $row['profit']       = $profit;

        return $row;
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }

        $model  = new BukuKeuangan();
        $rows   = $model->getAll();

        // Kalkulasi tiap baris
        $rows = array_map([$this, 'calcRow'], $rows);

        // Summary totals
        $totals = [
            'tagihan_invoice' => array_sum(array_column($rows, 'tagihan_invoice')),
            'vendor'          => array_sum(array_column($rows, 'vendor')),
            'operasional'     => array_sum(array_column($rows, 'operasional')),
            'cadangan'        => array_sum(array_column($rows, 'cadangan')),
            'pph_val'         => array_sum(array_column($rows, 'pph_val')),
            'ppn_val'         => array_sum(array_column($rows, 'ppn_val')),
            'harga_pokok'     => array_sum(array_column($rows, 'harga_pokok')),
            'profit_sblm'     => array_sum(array_column($rows, 'profit_sblm')),
            'pph_badan'       => array_sum(array_column($rows, 'pph_badan')),
            'profit_stlh'     => array_sum(array_column($rows, 'profit_stlh')),
            'komisaris'       => array_sum(array_column($rows, 'komisaris')),
            'direktur'        => array_sum(array_column($rows, 'direktur')),
            'marketing'       => array_sum(array_column($rows, 'marketing')),
            'profit'          => array_sum(array_column($rows, 'profit')),
        ];

        $this->view('buku_keuangan/index', [
            'rows'   => $rows,
            'totals' => $totals,
        ]);
    }

    public function store(Request $request)
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }

        $data = [
            'tanggal'          => $request->post('tanggal'),
            'no_invoice'       => trim($request->post('no_invoice')),
            'customer'         => trim($request->post('customer')),
            'tujuan'           => trim($request->post('tujuan')),
            'tagihan_invoice'  => (float) str_replace([',', '.'], ['', '.'], $request->post('tagihan_invoice') ?? 0),
            'vendor'           => (float) str_replace([',', '.'], ['', '.'], $request->post('vendor') ?? 0),
            'operasional'      => (float) str_replace([',', '.'], ['', '.'], $request->post('operasional') ?? 0),
            'is_pph'           => $request->post('is_pph') == '1' ? 1 : 0,
            'is_ppn'           => $request->post('is_ppn') == '1' ? 1 : 0,
        ];

        if (empty($data['tanggal']) || empty($data['no_invoice']) || empty($data['customer'])) {
            $_SESSION['error'] = 'Data tidak lengkap. Tanggal, No Invoice, dan Customer wajib diisi.';
            Response::redirect('/buku-keuangan');
        }

        $model = new BukuKeuangan();
        $model->create($data);
        $_SESSION['success'] = 'Data berhasil ditambahkan.';
        Response::redirect('/buku-keuangan');
    }

    public function update(Request $request, int $id)
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }

        $data = [
            'tanggal'          => $request->post('tanggal'),
            'no_invoice'       => trim($request->post('no_invoice')),
            'customer'         => trim($request->post('customer')),
            'tujuan'           => trim($request->post('tujuan')),
            'tagihan_invoice'  => (float) str_replace([',', '.'], ['', '.'], $request->post('tagihan_invoice') ?? 0),
            'vendor'           => (float) str_replace([',', '.'], ['', '.'], $request->post('vendor') ?? 0),
            'operasional'      => (float) str_replace([',', '.'], ['', '.'], $request->post('operasional') ?? 0),
            'is_pph'           => $request->post('is_pph') == '1' ? 1 : 0,
            'is_ppn'           => $request->post('is_ppn') == '1' ? 1 : 0,
        ];

        $model = new BukuKeuangan();
        $model->updateRecord($id, $data);
        $_SESSION['success'] = 'Data berhasil diperbarui.';
        Response::redirect('/buku-keuangan');
    }

    public function destroy(Request $request, int $id)
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }

        $model = new BukuKeuangan();
        $model->deleteRecord($id);
        $_SESSION['success'] = 'Data berhasil dihapus.';
        Response::redirect('/buku-keuangan');
    }
}
