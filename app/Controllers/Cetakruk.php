<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RukModel;
use App\Models\ProgramModel;
use App\Models\PenyusunModel;
use App\Models\KodebelanjaModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\Mcountdown;

class Cetakruk extends BaseController
{

    protected $rukModel;
    protected $programModel;
    protected $penyusunModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $mcountdown;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->rukModel = new RukModel();
        $this->programModel = new ProgramModel();
        $this->penyusunModel = new PenyusunModel();
        $this->kodebelanjaModel = new KodebelanjaModel();
        $this->uptModel = new UptModel();
        $this->usulanModel = new UsulanModel();
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        $pkm = 1;
        $prog = 1;
        $response = array();
        $tahun = $this->session->get('db_tahun');
        $data['data'] = array();
        $join = "(SELECT usul_belanja.id_sub, SUM(usul_belanja.harga_total) as harga_total FROM usul_belanja GROUP BY usul_belanja.id_sub) as b";
        $result = $this->rukModel->asArray()->select('ruk.id, ruk.id_menu, ruk.keterangan, ruk.tujuan, ruk.sasaran, ruk.target, ruk.tgjawab, ruk.sumberdaya, ruk.mitra, ruk.status, ruk.catatan, ruk.waktu, ruk.indikator, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=ruk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=ruk.id_menu')
            ->where(['ruk.id_pkm' => $pkm])
            ->findAll();

        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'controller'        => 'cetakruk',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer,
            'result'            => $result
        ];
        $unitlayanan = $this->uptModel->where('id', user()->puskesmas)->first();
        // return redirect()->to(base_url('billing'));
        if (in_groups('superadmin') == false and $unitlayanan->expire == null) {
            return redirect()->to(base_url('billing'));
        } else {
            if (!$this->session->get('db_tahun')) {
                return redirect()->to(base_url('pilihtahun'));
            } else {
                $data['tahun'] = $tahun;
                return view('cetakruk', $data);
            }
        }
    }

    public function tampil()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getPost('pkm');
        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th>Kegiatan</th>";
        $isitampil .= "<th>Rincian Kegiatan</th>";
        $isitampil .= "<th>Tujuan</th>";
        $isitampil .= "<th>Sasaran</th>";
        $isitampil .= "<th>Target</th>";
        $isitampil .= "<th>Penanggungjawab</th>";
        $isitampil .= "<th>Sumber daya</th>";
        $isitampil .= "<th>Mitra kerja</th>";
        $isitampil .= "<th>Waktu</th>";
        $isitampil .= "<th>Biaya</th>";
        $isitampil .= "<th>Indikator Kinerja</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join = "(SELECT usul_belanja.id_sub, SUM(usul_belanja.harga_total) as harga_total FROM usul_belanja where usul_belanja.tahun=" . $tahun . " GROUP BY usul_belanja.id_sub ) as b";
        $result = $this->rukModel->asArray()->select('ruk.id, ruk.id_menu, ruk.keterangan, ruk.tujuan, ruk.sasaran, ruk.target, ruk.tgjawab, ruk.sumberdaya, ruk.mitra, ruk.status, ruk.catatan, ruk.waktu, ruk.indikator, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=ruk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=ruk.id_menu')
            ->where([
                'ruk.id_pkm' => $pkm,
                'ruk.tahun' => $tahun
            ])
            ->findAll();
        $no = 1;
        $total = 0;
        foreach ($result as $row) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";

            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $row['keterangan'] . "</td>";
            $isitampil .= "<td>" . $row['tujuan'] . "</td>";
            $isitampil .= "<td>" . $row['sasaran'] . "</td>";
            $isitampil .= "<td>" . $row['target'] . "</td>";
            $isitampil .= "<td>" . $row['tgjawab'] . "</td>";
            $isitampil .= "<td>" . $row['sumberdaya'] . "</td>";
            $isitampil .= "<td>" . $row['mitra'] . "</td>";
            $isitampil .= "<td>" . $row['waktu'] . "</td>";
            $harga = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : 0;
            $isitampil .= "<td class='text-end'>" . $harga . "</td>";
            $isitampil .= "<td>" . $row['indikator'] . "</td>";
            $isitampil .= "</tr>";
            $total += $row['harga_total'];
        };
        $isitampil .= "<tr>";
        $isitampil .= "<td class='text-center' colspan='10'><b>Total</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($total, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td></td>";
        $isitampil .= "</tr>";
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $msg = [
            'data' => $isitampil,
            'puskesmas' => $puskesmas['pkm'],
            'tahun' => $tahun
        ];
        return $this->response->setJSON($msg);
    }

    public function cetak()
    {
        $pkm = $this->request->getVar('pkm');
        $tahun = $this->session->get('db_tahun');
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $data['data'] = array();
        $join = "(SELECT usul_belanja.id_sub, SUM(usul_belanja.harga_total) as harga_total FROM usul_belanja where usul_belanja.tahun=" . $tahun . " GROUP BY usul_belanja.id_sub) as b";
        $result = $this->rukModel->asArray()->select('ruk.id, ruk.id_menu, ruk.keterangan, ruk.tujuan, ruk.sasaran, ruk.target, ruk.tgjawab, ruk.sumberdaya, ruk.mitra, ruk.status, ruk.catatan, ruk.waktu, ruk.indikator, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=ruk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=ruk.id_menu')
            ->where(['ruk.id_pkm' => $pkm, 'ruk.tahun' => $tahun])
            ->findAll();

        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'result'            => $result,
            'puskesmas'         => $puskesmas['pkm'],
            'tahun' => $tahun
        ];

        echo view('cetak_ruk', $data);
    }
}