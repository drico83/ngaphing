<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RukModel;
use App\Models\SatuanModel;
use App\Models\RpkModel;
use App\Models\ProgramModel;
use App\Models\PenyusunModel;
use App\Models\KodebelanjaModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\UsulanRpkModel;
use App\Models\UsulanRpkpModel;
use App\Models\Mcountdown;

class Cetakraballp extends BaseController
{

    protected $rukModel;
    protected $satuanModel;
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
        $this->satuanModel = new SatuanModel();
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
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'controller'        => 'cetakraballp',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('cetakraballp', $data);
        }
    }

    public function tampil()
    {
        $pkm = $this->request->getVar('pkm');

        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2' style='text-align:center;'>No.</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2' style='text-align:center;'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>SATUAN</th>";
        $isitampil .= "<th rowspan='2' colspan='9' style='text-align:center;'>RINCIAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>HARGA SATUAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>SUB TOTAL</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>JUMLAH TOTAL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style='text-align:center;'>KODE REKENING</th>";
        $isitampil .= "<th style='text-align:center;'>DETAIL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rinci = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sum(usul_belanja_rpkp.harga_total) as harga_total')
            ->where('usul_belanja_rpkp.id_pkm', $pkm)
            ->groupBy('usul_belanja_rpkp.id_pkm')->findall();
        $nor = 65;

        foreach ($rinci as $key => $value) {
            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='18'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";


            $rincian = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpkp.harga_total) as harga_total')
                ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                ->where('usul_belanja_rpkp.id_pkm', $pkm)
                ->groupBy('sub_komponen.id_rincian')->findall();
            $nor = 65;

            foreach ($rincian as $key => $value) {
                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='18'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
                $isitampil .= "</tr>";

                $komponen = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_kom, komponen.nama_komponen, sum(usul_belanja_rpkp.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('komponen', 'komponen.id=sub_komponen.id_kom')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpkp.id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])
                    ->groupBy('sub_komponen.id_kom')->findall();
                $nox = 1;
                foreach ($komponen as $key => $value) {
                    $isitampil .= "<tr>";
                    $isitampil .= "<td colspan='18'><b>" . $nox++ . ". " . $value->nama_komponen . "</b></td>";
                    $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
                    $isitampil .= "</tr>";

                    $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
                    $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                        ->join($join, 'b.id_sub=rpk.id', 'left')
                        ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                        ->where([
                            'rpk.id_pkm' => $pkm,
                            'sub_komponen.id_kom' => $value->id_kom
                        ])->findAll();
                    $no = 97;
                    foreach ($result as $row) {
                        $harga_total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td><b>" . chr($no++) . ".</b></td>";
                        $isitampil .= "<td colspan='16'><b>" . $row['nama_subkomponen'] . "</b></td>";
                        $isitampil .= "<td style='text-align:right;'> <b>" . $harga_total . "</b></td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "</tr>";

                        $result2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kode_belanja.satuan, kodrek.kode')
                            ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpkp.id_belanja')
                            ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,

                                'usul_belanja_rpkp.id_sub' => $row['id']
                            ])->findAll();
                        $no2 = 1;
                        $perkalian = "";
                        $vol2 = "";



                        foreach ($result2 as $key => $value) {
                            $satuan1 = $this->satuanModel->where('id', $value->sat1)->first();
                            $sat1 = $satuan1->nama_satuan;
                            $vol2 = ($value->vol2 == 0) ? '-' : ' x ' . $value->vol2;
                            if ($value->sat2 == 0) {
                                $sat2 = '-';
                            } else {
                                $satuan2 = $this->satuanModel->where('id', $value->sat2)->first();
                                $sat2 = $satuan2->nama_satuan;
                            };

                            $vol3 = ($value->vol3 == 0) ? '-' : " x " . $value->vol3;
                            if ($value->sat3 == 0) {
                                $sat3 = '-';
                            } else {
                                $satuan3 = $this->satuanModel->where('id', $value->sat3)->first();
                                $sat3 = $satuan3->nama_satuan;
                            };
                            $vol4 = ($value->vol4 == 0) ? '-' : '<span> x ' . $value->vol4 . '</span>';
                            if ($value->sat4 == 0) {
                                $sat4 = '-';
                            } else {
                                $satuan4 = $this->satuanModel->where('id', $value->sat4)->first();
                                $sat4 = $satuan4->nama_satuan;
                            };
                            $isitampil .= "<tr>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $no2++ . ").</td>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $value->kode . "</td>";
                            $isitampil .= "<td>" . $value->nama_belanja . "</td>";
                            $isitampil .= "<td>" . $value->keterangan . "</td>";
                            $isitampil .= "<td>" . $value->satuan . "</td>";
                            $isitampil .= "<td>" . $value->vol1 . "</td>";
                            $isitampil .= "<td>" . $sat1 . "</td>";
                            $isitampil .= "<td>" . $vol2 . "</td>";
                            $isitampil .= "<td>" . $sat2 . "</td>";
                            $isitampil .= "<td>" . $vol3 . "</td>";
                            $isitampil .= "<td>" . $sat3 . "</td>";
                            $isitampil .= "<td>" . $vol4 . "</td>";
                            $isitampil .= "<td>" . $sat4 . "</td>";
                            $isitampil .= "<td> =" . $value->vol_total . "</td>";
                            $isitampil .= "<td style='text-align:right;'>" . number_format($value->harga, 0, ",", ".") . "</td>";
                            $isitampil .= "<td style='text-align:right;'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "</tr>";
                        };
                    };
                };
            };
        };

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $msg = [
            'data' => $isitampil,
            'puskesmas' => $puskesmas['pkm'],
            'pkm' => $puskesmas['id']
        ];

        return $this->response->setJSON($msg);
    }

    public function cetak()
    {
        $pkm = $this->request->getVar('pkm');

        $isitampil = "<table border=1'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2' style='text-align:center;'>No.</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2' style='text-align:center;'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>SATUAN</th>";
        $isitampil .= "<th rowspan='2' colspan='10' style='text-align:center;'>RINCIAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>HARGA SATUAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>SUB TOTAL</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>JUMLAH TOTAL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style='text-align:center;'>KODE REKENING</th>";
        $isitampil .= "<th style='text-align:center;'>DETAIL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rinci = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sum(usul_belanja_rpkp.harga_total) as harga_total')
            ->where('usul_belanja_rpkp.id_pkm', $pkm)
            ->groupBy('usul_belanja_rpkp.id_pkm')->findall();
        $nor = 1;

        foreach ($rinci as $key => $value) {
            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='7'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td colspan='10'></td>";
            $isitampil .= "<td></td>";
            $isitampil .= "<td></td>";
            $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total) . "</b></td>";
            $isitampil .= "</tr>";


            $rincian = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpkp.harga_total) as harga_total')
                ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                ->where('usul_belanja_rpkp.id_pkm', $pkm)
                ->groupBy('sub_komponen.id_rincian')->findall();
            $nor = 1;

            foreach ($rincian as $key => $value) {
                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='7'><b>" . $nor++ . ". " . $value->nama_rincian . "</b></td>";
                $isitampil .= "<td colspan='10'></td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total) . "</b></td>";
                $isitampil .= "</tr>";

                $komponen = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_kom, komponen.nama_komponen, sum(usul_belanja_rpkp.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('komponen', 'komponen.id=sub_komponen.id_kom')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpkp.id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])
                    ->groupBy('sub_komponen.id_kom')->findall();
                $nox = 1;
                foreach ($komponen as $key => $value) {
                    $isitampil .= "<tr>";
                    $isitampil .= "<td colspan='7'><b>" . $nox++ . ". " . $value->nama_komponen . "</b></td>";
                    $isitampil .= "<td colspan='10'></td>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total) . "</b></td>";
                    $isitampil .= "</tr>";

                    $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
                    $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                        ->join($join, 'b.id_sub=rpk.id', 'left')
                        ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                        ->where([
                            'rpk.id_pkm' => $pkm,
                            'sub_komponen.id_kom' => $value->id_kom
                        ])->findAll();
                    $no = 1;
                    foreach ($result as $row) {
                        $harga_total = ($row['harga_total'] != null) ? number_format($row['harga_total']) : '0';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td><b>" . $no++ . "</b></td>";
                        $isitampil .= "<td colspan='6'><b>" . $row['nama_subkomponen'] . "</b></td>";
                        $isitampil .= "<td colspan='10'></td>";
                        $isitampil .= "<td></td>";

                        $isitampil .= "<td style='text-align:right;'> <b>" . $harga_total . "</b></td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "</tr>";

                        $result2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kode_belanja.satuan, kodrek.kode')
                            ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpkp.id_belanja')
                            ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,

                                'usul_belanja_rpkp.id_sub' => $row['id']
                            ])->findAll();
                        $no2 = 1;
                        $perkalian = "";
                        $vol2 = "";



                        foreach ($result2 as $key => $value) {
                            $satuan1 = $this->satuanModel->where('id', $value->sat1)->first();
                            $sat1 = $satuan1->nama_satuan;
                            $vol2 = ($value->vol2 == 0) ? '-' : ' x ' . $value->vol2;
                            if ($value->sat2 == 0) {
                                $sat2 = '-';
                            } else {
                                $satuan2 = $this->satuanModel->where('id', $value->sat2)->first();
                                $sat2 = $satuan2->nama_satuan;
                            };

                            $vol3 = ($value->vol3 == 0) ? '-' : " x " . $value->vol3;
                            if ($value->sat3 == 0) {
                                $sat3 = '-';
                            } else {
                                $satuan3 = $this->satuanModel->where('id', $value->sat3)->first();
                                $sat3 = $satuan3->nama_satuan;
                            };
                            $vol4 = ($value->vol4 == 0) ? '-' : '<span> x ' . $value->vol4 . '</span>';
                            if ($value->sat4 == 0) {
                                $sat4 = '-';
                            } else {
                                $satuan4 = $this->satuanModel->where('id', $value->sat4)->first();
                                $sat4 = $satuan4->nama_satuan;
                            };
                            $isitampil .= "<tr>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $no2++ . "</td>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $value->kode . "</td>";
                            $isitampil .= "<td>" . $value->nama_belanja . "</td>";
                            $isitampil .= "<td>" . $value->keterangan . "</td>";
                            $isitampil .= "<td>" . $value->satuan . "</td>";
                            $isitampil .= "<td>" . $value->vol1 . "</td>";
                            $isitampil .= "<td>" . $sat1 . "</td>";
                            $isitampil .= "<td>" . $vol2 . "</td>";
                            $isitampil .= "<td>" . $sat2 . "</td>";
                            $isitampil .= "<td>" . $vol3 . "</td>";
                            $isitampil .= "<td>" . $sat3 . "</td>";
                            $isitampil .= "<td>" . $vol4 . "</td>";
                            $isitampil .= "<td>" . $sat4 . "</td>";
                            $isitampil .= "<td>=</td>";
                            $isitampil .= "<td> =" . $value->vol_total . "</td>";
                            $isitampil .= "<td style='text-align:right;'>" . number_format($value->harga) . "</td>";
                            $isitampil .= "<td style='text-align:right;'>" . number_format($value->harga_total) . "</td>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "</tr>";
                        };
                    };
                };
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $data = [
            'result' => $isitampil,
            'puskesmas' => $puskesmas,
        ];


        echo view('cetak_rab_all', $data);
    }

    public function expo()
    {
        $pkm = $this->request->getVar('pkm');

        $isitampil = "<table border=1'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2' style='text-align:center;'>No.</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2' style='text-align:center;'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>SATUAN</th>";
        $isitampil .= "<th rowspan='2' colspan='10' style='text-align:center;'>RINCIAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>HARGA SATUAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>SUB TOTAL</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>JUMLAH TOTAL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style='text-align:center;'>KODE REKENING</th>";
        $isitampil .= "<th style='text-align:center;'>DETAIL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rinci = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sum(usul_belanja_rpkp.harga_total) as harga_total')
            ->where('usul_belanja_rpkp.id_pkm', $pkm)
            ->groupBy('usul_belanja_rpkp.id_pkm')->findall();
        $nor = 1;

        foreach ($rinci as $key => $value) {
            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='7'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td colspan='10'></td>";
            $isitampil .= "<td></td>";
            $isitampil .= "<td></td>";
            $isitampil .= "<td style='text-align:right;'><b>" . $value->harga_total . "</b></td>";
            $isitampil .= "</tr>";


            $rincian = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpkp.harga_total) as harga_total')
                ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                ->where('usul_belanja_rpkp.id_pkm', $pkm)
                ->groupBy('sub_komponen.id_rincian')->findall();
            $nor = 1;

            foreach ($rincian as $key => $value) {
                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='7'><b>" . $nor++ . ". " . $value->nama_rincian . "</b></td>";
                $isitampil .= "<td colspan='10'></td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . $value->harga_total . "</b></td>";
                $isitampil .= "</tr>";

                $komponen = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_kom, komponen.nama_komponen, sum(usul_belanja_rpkp.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('komponen', 'komponen.id=sub_komponen.id_kom')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpkp.id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])
                    ->groupBy('sub_komponen.id_kom')->findall();
                $nox = 1;
                foreach ($komponen as $key => $value) {
                    $isitampil .= "<tr>";
                    $isitampil .= "<td colspan='7'><b>" . $nox++ . ". " . $value->nama_komponen . "</b></td>";
                    $isitampil .= "<td colspan='10'></td>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td style='text-align:right;'><b>" . $value->harga_total . "</b></td>";
                    $isitampil .= "</tr>";

                    $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
                    $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                        ->join($join, 'b.id_sub=rpk.id', 'left')
                        ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                        ->where([
                            'rpk.id_pkm' => $pkm,
                            'sub_komponen.id_kom' => $value->id_kom
                        ])->findAll();
                    $no = 1;
                    foreach ($result as $row) {
                        $isitampil .= "<tr>";
                        $isitampil .= "<td><b>" . $no++ . "</b></td>";
                        $isitampil .= "<td colspan='6'><b>" . $row['nama_subkomponen'] . "</b></td>";
                        $isitampil .= "<td colspan='10'></td>";
                        $isitampil .= "<td></td>";

                        $isitampil .= "<td style='text-align:right;'> <b>" . $row['harga_total'] . "</b></td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "</tr>";

                        $result2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kode_belanja.satuan, kodrek.kode')
                            ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpkp.id_belanja')
                            ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,

                                'usul_belanja_rpkp.id_sub' => $row['id']
                            ])->findAll();
                        $no2 = 1;
                        $perkalian = "";
                        $vol2 = "";



                        foreach ($result2 as $key => $value) {
                            $satuan1 = $this->satuanModel->where('id', $value->sat1)->first();
                            $sat1 = $satuan1->nama_satuan;
                            $vol2 = ($value->vol2 == 0) ? '-' : ' x ' . $value->vol2;
                            if ($value->sat2 == 0) {
                                $sat2 = '-';
                            } else {
                                $satuan2 = $this->satuanModel->where('id', $value->sat2)->first();
                                $sat2 = $satuan2->nama_satuan;
                            };

                            $vol3 = ($value->vol3 == 0) ? '-' : " x " . $value->vol3;
                            if ($value->sat3 == 0) {
                                $sat3 = '-';
                            } else {
                                $satuan3 = $this->satuanModel->where('id', $value->sat3)->first();
                                $sat3 = $satuan3->nama_satuan;
                            };
                            $vol4 = ($value->vol4 == 0) ? '-' : '<span> x ' . $value->vol4 . '</span>';
                            if ($value->sat4 == 0) {
                                $sat4 = '-';
                            } else {
                                $satuan4 = $this->satuanModel->where('id', $value->sat4)->first();
                                $sat4 = $satuan4->nama_satuan;
                            };
                            $isitampil .= "<tr>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $no2++ . "</td>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $value->kode . "</td>";
                            $isitampil .= "<td>" . $value->nama_belanja . "</td>";
                            $isitampil .= "<td>" . $value->keterangan . "</td>";
                            $isitampil .= "<td>" . $value->satuan . "</td>";
                            $isitampil .= "<td>" . $value->vol1 . "</td>";
                            $isitampil .= "<td>" . $sat1 . "</td>";
                            $isitampil .= "<td>" . $vol2 . "</td>";
                            $isitampil .= "<td>" . $sat2 . "</td>";
                            $isitampil .= "<td>" . $vol3 . "</td>";
                            $isitampil .= "<td>" . $sat3 . "</td>";
                            $isitampil .= "<td>" . $vol4 . "</td>";
                            $isitampil .= "<td>" . $sat4 . "</td>";
                            $isitampil .= "<td>=</td>";
                            $isitampil .= "<td> =" . $value->vol_total . "</td>";
                            $isitampil .= "<td style='text-align:right;'>" . $value->harga . "</td>";
                            $isitampil .= "<td style='text-align:right;'>" . $value->harga_total . "</td>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "</tr>";
                        };
                    };
                };
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $data = [
            'result' => $isitampil,
            'puskesmas' => $puskesmas,
        ];


        echo view('cetak_rab_all2', $data);
    }
}