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
use App\Models\UsulanRpkModel;
use App\Models\UsulanRpkpModel;
use App\Models\Mcountdown;

class Cetakrabp extends BaseController
{

    protected $rukModel;
    protected $rpkModel;
    protected $programModel;
    protected $penyusunModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $usulanrpkModel;
    protected $usulanrpkpModel;
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
        $this->usulanrpkModel = new UsulanRpkModel();
        $this->usulanrpkpModel = new UsulanRpkpModel();
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        $pkm = $this->request->getVar('pkm');
        $prog = $this->request->getVar('prog');
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
            'controller'        => 'cetakrabp',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer,
            'result'            => $result
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('cetakrabp', $data);
        }
    }

    public function tampil()
    {
        $pkm = $this->request->getVar('pkm');
        $prog = $this->request->getVar('prog');
        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2'>No.</th>";
        $isitampil .= "<th rowspan='2'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2'>SATUAN</th>";
        $isitampil .= "<th rowspan='2'>ANGGARAN MURNI</th>";
        $isitampil .= "<th colspan='4'>ANGGARAN PERUBAHAN</th>";

        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>KODE REKENING</th>";
        $isitampil .= "<th>DETAIL</th>";
        $isitampil .= "<th>RINCIAN</th>";
        $isitampil .= "<th>HARGA SATUAN</th>";
        $isitampil .= "<th>SUB TOTAL</th>";
        $isitampil .= "<th>JUMLAH TOTAL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
        $join2 = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total2 FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as c";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total, c.harga_total2')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join($join2, 'c.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;
        foreach ($result as $row) {
            $hargatotal = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : "0";
            $hargatotal2 = ($row['harga_total2'] != null) ? number_format($row['harga_total2'], 0, ",", ".") : "0";
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td colspan='6'>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $hargatotal2 . "</td>";
            $isitampil .= "<td colspan='3'></td>";
            $isitampil .= "<td>" . $hargatotal . "</td>";
            $isitampil .= "</tr>";

            $result2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kodrek.kode')
                ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpkp.id_belanja')
                ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                ->where([
                    'usul_belanja_rpkp.id_pkm' => $pkm,
                    'usul_belanja_rpkp.id_program' => $prog,
                    'usul_belanja_rpkp.id_sub' => $row['id']
                ])->findAll();
            $result3 = $this->usulanrpkModel->select('usul_belanja_rpk.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kodrek.kode')
                ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpk.id_belanja')
                ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                ->where([
                    'usul_belanja_rpk.id_pkm' => $pkm,
                    'usul_belanja_rpk.id_program' => $prog,
                    'usul_belanja_rpk.id_sub' => $row['id']
                ])->findAll();
            $no2 = 1;
            foreach ($result2 as $key => $value) {
                $sat1 = "";
                $sat2 = "";
                $sat3 = "";
                $sat4 = "";
                if ($value->vol2 == "") {
                    $sat1 = $value->sat1;
                } else {
                    $sat1 = " x ";
                };
                if ($value->vol3 == "") {
                    $sat2 = $value->sat2;
                } else {
                    $sat2 = " x ";
                };
                if ($value->vol4 == "") {
                    $sat3 = $value->sat3;
                } else {
                    $sat3 = " x ";
                };

                // $anggaranmurni = ($result3 != null) ? $result3->harga_total : 0;
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no2++ . "</td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $value->kode . "</td>";
                $isitampil .= "<td>" . $value->nama_belanja . "</td>";
                $isitampil .= "<td>" . $value->keterangan . "</td>";
                $isitampil .= "<td>" . $value->satuan . "</td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $value->vol1 . $sat1 . $value->vol2 . $sat2 . $value->vol3 . $sat3 . $value->vol4 . $value->sat4 . "=" . $value->vol_total . "</td>";
                $isitampil .= "<td>" . number_format($value->harga, 0, ",", ".") . "</td>";
                $isitampil .= "<td>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                $isitampil .= "<td></td>";
                $isitampil .= "</tr>";
                $result3 = "";
            };
        }
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
        $pkm = $this->request->getVar('pkm');
        $prog = $this->request->getVar('prog');
        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2'>No.</th>";
        $isitampil .= "<th rowspan='2'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2'>SATUAN</th>";
        $isitampil .= "<th rowspan='2'>ANGGARAN MURNI</th>";
        $isitampil .= "<th colspan='4'>ANGGARAN PERUBAHAN</th>";

        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>KODE REKENING</th>";
        $isitampil .= "<th>DETAIL</th>";
        $isitampil .= "<th>RINCIAN</th>";
        $isitampil .= "<th>HARGA SATUAN</th>";
        $isitampil .= "<th>SUB TOTAL</th>";
        $isitampil .= "<th>JUMLAH TOTAL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
        $join2 = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total2 FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as c";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total, c.harga_total2')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join($join2, 'c.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;
        foreach ($result as $row) {
            $hargatotal = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : "0";
            $hargatotal2 = ($row['harga_total2'] != null) ? number_format($row['harga_total2'], 0, ",", ".") : "0";
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td colspan='6'>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $hargatotal2 . "</td>";
            $isitampil .= "<td colspan='3'></td>";
            $isitampil .= "<td>" . $hargatotal . "</td>";
            $isitampil .= "</tr>";

            $result2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kodrek.kode')
                ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpkp.id_belanja')
                ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                ->where([
                    'usul_belanja_rpkp.id_pkm' => $pkm,
                    'usul_belanja_rpkp.id_program' => $prog,
                    'usul_belanja_rpkp.id_sub' => $row['id']
                ])->findAll();
            $result3 = $this->usulanrpkModel->select('usul_belanja_rpk.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kodrek.kode')
                ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpk.id_belanja')
                ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                ->where([
                    'usul_belanja_rpk.id_pkm' => $pkm,
                    'usul_belanja_rpk.id_program' => $prog,
                    'usul_belanja_rpk.id_sub' => $row['id']
                ])->findAll();
            $no2 = 1;
            foreach ($result2 as $key => $value) {
                $sat1 = "";
                $sat2 = "";
                $sat3 = "";
                $sat4 = "";
                if ($value->vol2 == "") {
                    $sat1 = $value->sat1;
                } else {
                    $sat1 = " x ";
                };
                if ($value->vol3 == "") {
                    $sat2 = $value->sat2;
                } else {
                    $sat2 = " x ";
                };
                if ($value->vol4 == "") {
                    $sat3 = $value->sat3;
                } else {
                    $sat3 = " x ";
                };

                // $anggaranmurni = ($result3 != null) ? $result3->harga_total : 0;
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no2++ . "</td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $value->kode . "</td>";
                $isitampil .= "<td>" . $value->nama_belanja . "</td>";
                $isitampil .= "<td>" . $value->keterangan . "</td>";
                $isitampil .= "<td>" . $value->satuan . "</td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $value->vol1 . $sat1 . $value->vol2 . $sat2 . $value->vol3 . $sat3 . $value->vol4 . $value->sat4 . "=" . $value->vol_total . "</td>";
                $isitampil .= "<td>" . number_format($value->harga, 0, ",", ".") . "</td>";
                $isitampil .= "<td>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                $isitampil .= "<td></td>";
                $isitampil .= "</tr>";
                $result3 = "";
            };
        }
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";

        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $program = $this->programModel->asArray()->where('id', $prog)->first();
        $data = [
            'result' => $isitampil,
            'puskesmas' => $puskesmas['pkm'],
            'program' => $program['nama_program'],
        ];


        echo view('cetak_rabp', $data);
    }
}