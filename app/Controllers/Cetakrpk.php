<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RukModel;
use App\Models\RpkModel;
use App\Models\ProgramModel;
use App\Models\PenyusunModel;
use App\Models\KodebelanjaModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\Mcountdown;

class Cetakrpk extends BaseController
{

    protected $rukModel;
    protected $rpkModel;
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
        $this->rpkModel = new RpkModel();
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
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where('link', 'rpk')->first();
        $data = [
            'controller'        => 'cetakrpk',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('cetakrpk', $data);
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
        $isitampil .= "<th>Jadwal Pelaksanaan</th>";
        $isitampil .= "<th>Lokasi Pelaksanaan</th>";
        $isitampil .= "<th>Anggaran Murni</th>";
        $isitampil .= "<th>Anggaran Perubahan</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk WHERE usul_belanja_rpk.tahun=" . $tahun . " GROUP BY usul_belanja_rpk.id_sub) as b";
        $join2 = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total2 FROM usul_belanja_rpkp WHERE usul_belanja_rpkp.tahun=" . $tahun . " GROUP BY usul_belanja_rpkp.id_sub) as c";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.jadwal, rpk.lokasi, rpk.status, rpk.catatan, sub_komponen.nama_subkomponen, b.harga_total, c.harga_total2')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join($join2, 'c.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where(['rpk.id_pkm' => $pkm, 'rpk.tahun' => $tahun])
            ->findAll();
        $no = 1;
        foreach ($result as $row) {
            $total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
            $total2 = ($row['harga_total2'] != null) ? number_format($row['harga_total2'], 0, ",", ".") : '0';

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";

            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $row['keterangan'] . "</td>";
            $isitampil .= "<td>" . $row['tujuan'] . "</td>";
            $isitampil .= "<td>" . $row['sasaran'] . "</td>";
            $isitampil .= "<td>" . $row['target'] . "</td>";
            $isitampil .= "<td>" . $row['tgjawab'] . "</td>";
            $isitampil .= "<td>" . $row['jadwal'] . "</td>";
            $isitampil .= "<td>" . $row['lokasi'] . "</td>";
            $isitampil .= "<td>" . $total . "</td>";
            $isitampil .= "<td>" . $total2 . "</td>";
            $isitampil .= "</tr>";
        };

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $msg = [
            'data' => $isitampil,
            'puskesmas' => $puskesmas['pkm']
        ];
        return $this->response->setJSON($msg);
    }

    public function cetak()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getPost('idpkm');
        $tanggal = $this->request->getPost('tanggal');
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
        $isitampil .= "<th>Jadwal Pelaksanaan</th>";
        $isitampil .= "<th>Lokasi Pelaksanaan</th>";
        $isitampil .= "<th>Anggaran</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk WHERE usul_belanja_rpk.tahun=" . $tahun . " GROUP BY usul_belanja_rpk.id_sub) as b";
        $join2 = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total2 FROM usul_belanja_rpkp WHERE usul_belanja_rpkp.tahun=" . $tahun . " GROUP BY usul_belanja_rpkp.id_sub) as c";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.jadwal, rpk.lokasi, rpk.status, rpk.catatan, sub_komponen.nama_subkomponen, b.harga_total, c.harga_total2')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join($join2, 'c.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where(['rpk.id_pkm' => $pkm, 'rpk.tahun' => $tahun])
            ->findAll();
        $no = 1;
        foreach ($result as $row) {
            $total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
            $total2 = ($row['harga_total2'] != null) ? number_format($row['harga_total2'], 0, ",", ".") : '0';

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";

            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $row['keterangan'] . "</td>";
            $isitampil .= "<td>" . $row['tujuan'] . "</td>";
            $isitampil .= "<td>" . $row['sasaran'] . "</td>";
            $isitampil .= "<td>" . $row['target'] . "</td>";
            $isitampil .= "<td>" . $row['tgjawab'] . "</td>";
            $isitampil .= "<td>" . $row['jadwal'] . "</td>";
            $isitampil .= "<td>" . $row['lokasi'] . "</td>";
            $isitampil .= "<td>" . $total . "</td>";
            $isitampil .= "<td>" . $total2 . "</td>";
            $isitampil .= "</tr>";
        };

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $data = [
            'result'            => $isitampil,
            'puskesmas'         => $puskesmas,
            'tahun'             => $tahun,
            'tanggal'             => $tanggal,
        ];

        echo view('cetak_rpk', $data);
    }
}