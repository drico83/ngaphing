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
use App\Models\RealisasiModel;
use App\Models\Mcountdown;

class Realisasi extends BaseController
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
    protected $realisasiModel;
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
        $this->realisasiModel = new RealisasiModel();
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where(['link' => 'belanja2', 'tahun' => $tahun])->first();
        $data = [
            'controller'        => 'realisasi',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            if (in_groups('admin')) {
                $data['user'] = 'admin';
            } else {
                $data['user'] = 'user';
            };
            $data['tahun'] = $tahun;
            return view('realisasi', $data);
        }
    }

    public function tampil()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');
        $bln = $this->request->getVar('bulan');
        $bulan = 'b' . $bln;
        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2' style='text-align:center;'>No.</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2' style='text-align:center;'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>RPK 1 TAHUN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>RPK BULAN INI</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>Jumlah Realisasi</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>Aksi</th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style='text-align:center;'>KODE REKENING</th>";
        $isitampil .= "<th style='text-align:center;'>DETAIL</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rinci = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sum(usul_belanja_rpk.harga_total) as harga_total, sum(usul_belanja_rpk.' . $bulan . ') as harga_bulan')
            ->where('usul_belanja_rpk.id_pkm', $pkm)
            ->groupBy('usul_belanja_rpk.id_pkm')->findall();
        $nor = 65;

        foreach ($rinci as $key => $value) {
            $total = $this->rpkModel->select('rpk.id_pkm, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan=' . $bln . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->where(['id_pkm' => $pkm])->groupBy('rpk.id_pkm')->first();

            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='6'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_bulan, 0, ",", ".") . "</b></td>";
            $atotal = ($total->jumlah2 != null) ? number_format($total->jumlah2, 0, ",", ".") : 0;
            $isitampil .= "<td class='text-end'><b>" . $atotal . "</b></td>";
            $isitampil .= "<td></td>";
            $isitampil .= "</td>";


            $rincian = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpk.harga_total) as harga_total, sum(usul_belanja_rpk.' . $bulan . ') as harga_bulan')
                ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                ->where('usul_belanja_rpk.id_pkm', $pkm)
                ->groupBy('sub_komponen.id_rincian')->findall();
            $nor = 65;

            foreach ($rincian as $key => $value) {
                $rincian = $this->rpkModel->select('sub_komponen.id_rincian, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan=' . $bln . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])->groupBy('sub_komponen.id_rincian')->first();

                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='6'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
                $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_bulan, 0, ",", ".") . "</b></td>";
                $arincian = ($rincian->jumlah2 != null) ? number_format($rincian->jumlah2, 0, ",", ".") : 0;
                $isitampil .= "<td class='text-end'><b>" . $arincian . "</b></td>";
                $isitampil .= "<td></td>";
                $isitampil .= "</tr>";

                $komponen = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_kom, komponen.nama_komponen, sum(usul_belanja_rpk.harga_total) as harga_total, sum(usul_belanja_rpk.' . $bulan . ') as harga_bulan')
                    ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('komponen', 'komponen.id=sub_komponen.id_kom')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpk.id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])
                    ->groupBy('sub_komponen.id_kom')->findall();
                $nox = 1;
                foreach ($komponen as $key => $value) {

                    $rpk = $this->rpkModel->select('sub_komponen.id_kom, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan=' . $bln . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_kom' => $value->id_kom])->groupBy('sub_komponen.id_kom')->first();


                    $isitampil .= "<tr>";
                    $isitampil .= "<td colspan='6'><b>" . $nox++ . ". " . $value->nama_komponen . "</b></td>";
                    $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
                    $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_bulan, 0, ",", ".") . "</b></td>";
                    $arpk = ($rpk->jumlah2 != null) ? number_format($rpk->jumlah2, 0, ",", ".") : 0;
                    $isitampil .= "<td class='text-end'><b>" . $arpk . "</b></td>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "</tr>";

                    $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total, sum(usul_belanja_rpk." . $bulan . ") as harga_bulan FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
                    $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total, b.harga_bulan')
                        ->join($join, 'b.id_sub=rpk.id', 'left')
                        ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                        ->where([
                            'rpk.id_pkm' => $pkm,
                            'sub_komponen.id_kom' => $value->id_kom
                        ])->findAll();
                    $no = 97;
                    foreach ($result as $row) {


                        $realisasi = $this->realisasiModel->select('usul_belanja_rpk.id_sub, sum(realisasi.jumlah) as jumlah')->join('usul_belanja_rpk', 'usul_belanja_rpk.id=realisasi.id_rpk')->where(['usul_belanja_rpk.id_sub' => $row['id'], 'realisasi.bulan' => $bln, 'realisasi.tahun' => $tahun])->groupBy('usul_belanja_rpk.id_sub')->first();
                        $isitampil .= "<tr>";
                        $isitampil .= "<td><b>" . chr($no++) . ".</b></td>";
                        $isitampil .= "<td colspan='5'><b>" . $row['nama_subkomponen'] . "</b></td>";
                        $harga_total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
                        $isitampil .= "<td style='text-align:right;'> <b>" . $harga_total . "</b></td>";
                        $harga_bulan = ($row['harga_bulan'] != null) ? number_format($row['harga_bulan'], 0, ",", ".") : '0';
                        $isitampil .= "<td class='text-end'><b>" . $harga_bulan . "</b></td>";
                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;
                        $isitampil .= "<td class='text-end'><b>" . $arealisasi . "</b></td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "</tr>";

                        $result2 = $this->usulanrpkModel->select('usul_belanja_rpk.*, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kode_belanja.satuan, kodrek.kode')
                            ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpk.id_belanja')
                            ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,

                                'usul_belanja_rpk.id_sub' => $row['id']
                            ])->findAll();
                        $no2 = 1;
                        $perkalian = "";
                        $vol2 = "";



                        foreach ($result2 as $key => $value) {


                            $isitampil .= "<tr>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $no2++ . ").</td>";
                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $value->kode . "</td>";
                            $isitampil .= "<td>" . $value->nama_belanja . "</td>";
                            $isitampil .= "<td>" . $value->keterangan . "</td>";
                            $isitampil .= "<td style='text-align:right;'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                            $isitampil .= "<td class='text-end'>" . number_format($value->$bulan, 0, ",", ".") . "</td>";
                            $realisasi = $this->realisasiModel->where(['id_rpk' => $value->id, 'bulan' => $bln])->first();
                            if ($value->$bulan == 0) {
                                $isitampil .= "<td></td>";
                                $isitampil .= "<td></td>";
                            } else {
                                if ($realisasi == null) {
                                    $isitampil .= "<td>0</td>";
                                    $isitampil .= "<td><button class='btn btn-primary' onclick='save(" . $value->id . ")'>Input Pengajuan / Realisasi</button></td>";
                                } else {
                                    $isitampil .= "<td class='text-end'>" . number_format($realisasi->jumlah, 0, ",", ".") . "</td>";
                                    $isitampil .= "<td><button class='btn btn-success' onclick='edit(" . $realisasi->id . ")'>Edit Pengajuan / Realisasi</button></td>";
                                };
                            }


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


    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('id');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->realisasiModel->where('id', $id)->first();

            return $this->response->setJSON($data);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function getrekening()
    {
        $response = array();
        $bln = $this->request->getVar('bulan');
        $bulan = 'b' . $bln;
        $id = $this->request->getPost('id');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $result2 = $this->usulanrpkModel->select('usul_belanja_rpk.*, usul_belanja_rpk.' . $bulan . ' as bulanan, kode_belanja.nama_belanja, kode_belanja.satuan, kode_belanja.harga, kode_belanja.satuan, kodrek.kode')
                ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpk.id_belanja')
                ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
                ->where([

                    'usul_belanja_rpk.id' => $id
                ])->first();

            return $this->response->setJSON($data);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {
        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['id_rpk'] = $this->request->getPost('id_rpk');
        $fields['bulan'] = $this->request->getPost('bln');
        $fields['tahun'] = $this->request->getPost('tahun');
        $fields['jumlah'] = $this->request->getPost('jumlah');


        $this->validation->setRules([
            'id_rpk' => ['label' => 'Id rpk', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
            'bulan' => ['label' => 'Bulan', 'rules' => 'required|numeric|min_length[0]|max_length[2]'],
            'tahun' => ['label' => 'Tahun', 'rules' => 'required|numeric|min_length[0]|max_length[4]'],
            'jumlah' => ['label' => 'Jumlah Pengajuan / Realisasi', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

        } else {

            $hitung = $this->realisasiModel->where(['id_rpk' => $fields['id_rpk'], 'bulan' => $fields['bulan'], 'tahun' => $fields['tahun']])->countAllResults();

            if ($hitung == 0) {
                if ($this->realisasiModel->insert($fields)) {

                    $response['success'] = true;
                    $response['messages'] = lang("App.insert-success");
                } else {

                    $response['success'] = false;
                    $response['messages'] = lang("App.insert-error");
                }
            } else {
                $response['success'] = false;
                $response['messages'] = "Gagal Menambahkan, Data sudah ada";
            }
        }

        return $this->response->setJSON($response);
    }

    public function edit()
    {
        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['id_rpk'] = $this->request->getPost('id_rpk');
        $fields['bulan'] = $this->request->getPost('bln');
        $fields['tahun'] = $this->request->getPost('tahun');
        $fields['jumlah'] = $this->request->getPost('jumlah');


        $this->validation->setRules([
            'id_rpk' => ['label' => 'Id rpk', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
            'bulan' => ['label' => 'Bulan', 'rules' => 'required|numeric|min_length[0]|max_length[2]'],
            'tahun' => ['label' => 'Tahun', 'rules' => 'required|numeric|min_length[0]|max_length[4]'],
            'jumlah' => ['label' => 'Jumlah Pengajuan / Realisasi', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

        } else {

            if ($this->realisasiModel->update($fields['id'], $fields)) {

                $response['success'] = true;
                $response['messages'] = lang("App.update-success");
            } else {

                $response['success'] = false;
                $response['messages'] = lang("App.update-error");
            }
        }

        return $this->response->setJSON($response);
    }
}