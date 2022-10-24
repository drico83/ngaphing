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
use App\Models\DaftarrkaModel;
use App\Models\KegiatanModel;
use App\Models\Mcountdown;

class Cetaksub extends BaseController
{

    protected $rukModel;
    protected $rpkModel;
    protected $programModel;
    protected $penyusunModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $usulanrpkModel;
    protected $daftarrkaModel;
    protected $mcountdown;
    protected $validation;
    protected $kegiatanModel;
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
        $this->daftarrkaModel = new DaftarrkaModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->mcountdown = new Mcountdown();
        $this->session = session();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'controller'        => 'cetaksub',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];
        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('cetaksub', $data);
        }
    }

    public function getupt()
    {
        $upt = $this->request->getPost('upt');
        if ($upt) {
            $datarka = $this->uptModel->select('id, pkm')->where('id', $upt)->findAll();
            $isidata = "";
        } else {
            if (in_groups('admin') || in_groups('programmer')) {
                $datarka = $this->uptModel->select('id, pkm')->findAll();
                $isidata = "<option>--Pilih Nama UPT--</option>";
            } else {
                $datarka = $this->uptModel->select('id, pkm')->where('id', user()->puskesmas)->findAll();
                $isidata = "<option>--Pilih Nama UPT--</option>";
            }
        }

        $data['data'] = array();

        foreach ($datarka as $key => $value) {
            $isidata .= '	<option value="' . $value->id . '">' . $value->pkm . '</option>';
        };

        $msg = [
            'data' => $isidata
        ];
        return $this->response->setJSON($msg);
    }

    public function getrka()
    {
        $rka = $this->request->getPost('rka');
        if ($rka) {
            $datarka = $this->daftarrkaModel->select('id, kode, judul')->where('id', $rka)->findAll();
            $isidata = "";
        } else {

            $datarka = $this->daftarrkaModel->select('id, kode, judul')->findAll();
            $isidata = "<option>--Pilih Nama UPT--</option>";
        }

        $data['data'] = array();

        foreach ($datarka as $key => $value) {
            $isidata .= '	<option value="' . $value->id . '">' . $value->kode . ' | ' . $value->judul . '</option>';
        };

        $msg = [
            'data' => $isidata
        ];
        return $this->response->setJSON($msg);
    }



    public function tampil()
    {
        $tahun = $this->session->get('db_tahun');
        $isitampil = "<table class='table table-bordered table-striped' style='border-width: medium'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style=text-align: center'>No.</th>";
        $isitampil .= "<th style=text-align: center'>Kode Sub Kegiatan</th>";
        $isitampil .= "<th align='center'>Program</th>";
        $isitampil .= "<th align='center'>Kegiatan</th>";
        $isitampil .= "<th align='center'>Sub Kegiatan</th>";
        $isitampil .= "<th align='center'>Kinerja</th>";
        $isitampil .= "<th align='center'>Indikator</th>";
        $isitampil .= "<th align='center'>Jumlah</th>";
        $isitampil .= "<th align='center'>Cetak Lembar Verifikasi</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        if (in_groups('admin') || in_groups('programmer')) {
            $puskesmas = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, sum(usul_belanja_rpk.harga_total) as hargatotal, ')->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')->where('usul_belanja_rpk.tahun', $tahun)->groupBy('usul_belanja_rpk.id_pkm')->findAll();
        } else {
            $puskesmas = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, sum(usul_belanja_rpk.harga_total) as hargatotal, ')->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')->where(['usul_belanja_rpk.tahun' => $tahun, 'usul_belanja_rpk.id_pkm' => user()->puskesmas])->groupBy('usul_belanja_rpk.id_pkm')->findAll();
        };


        $no = 1;
        foreach ($puskesmas as $rowpkm) {
            $isitampil .= "<tr>";
            $isitampil .= "<td><b>" . $no++ . ".</b></td>";
            $isitampil .= "<td colspan='6'><b>Puskesmas " . $rowpkm['pkm'] . "</b></td>";
            $isitampil .= "<td align ='right' ><b>" . $rowpkm['hargatotal'] . "</b></td>";
            $isitampil .= "</tr>";

            if (in_groups('admin') || in_groups('programmer')) {
                $program = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_keg, daftar_rka.id_prog, kegiatan.kode_keg as kode_keg, kegiatan.nama_keg as nama_keg, prog.kode_prog as kode_prog, prog.nama_prog as nama_prog, sum(usul_belanja_rpk.harga_total) as hargatotal')
                    ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                    ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                    ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                    ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                    ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                    ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                    ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                    ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                    ->join('prog', 'prog.id=daftar_rka.id_prog', 'left')
                    ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                    ->where([
                        'usul_belanja_rpk.tahun' => $tahun,
                        'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm']
                    ])
                    ->groupBy('daftar_rka.id_prog')
                    ->findAll();
            } else {
                $program = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_keg, daftar_rka.id_prog, kegiatan.kode_keg as kode_keg, kegiatan.nama_keg as nama_keg, prog.kode_prog as kode_prog, prog.nama_prog as nama_prog, sum(usul_belanja_rpk.harga_total) as hargatotal')
                    ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                    ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                    ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                    ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                    ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                    ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                    ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                    ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                    ->join('prog', 'prog.id=daftar_rka.id_prog', 'left')
                    ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                    ->where([
                        'usul_belanja_rpk.tahun' => $tahun,
                        'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm'],
                        'usul_belanja_rpk.id_pkm' => user()->puskesmas
                    ])
                    ->groupBy('daftar_rka.id_prog')
                    ->findAll();
            };



            $nox = 65;
            foreach ($program as $rowprog) {

                $isitampil .= "<tr>";
                $isitampil .= "<td><b>" . chr($nox++) . "</b></td>";
                $isitampil .= "<td><b>" . $rowprog['kode_prog'] . "</b></td>";
                $isitampil .= "<td colspan='5'><b>" . $rowprog['nama_prog'] . "</b></td>";
                $isitampil .= "<td align ='right' ><b>" . $rowprog['hargatotal'] . "</b></td>";
                $isitampil .= "<td></td>";
                $isitampil .= "</tr>";

                if (in_groups('admin') || in_groups('programmer')) {
                    $kegiatan = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_prog, daftar_rka.id_keg, kegiatan.kode_keg as kode_keg, kegiatan.nama_keg as nama_keg, prog.kode_prog as kode_prog, prog.nama_prog as nama_prog, sum(usul_belanja_rpk.harga_total) as hargatotal')
                        ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                        ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                        ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                        ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                        ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                        ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                        ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                        ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                        ->join('prog', 'prog.id=daftar_rka.id_prog', 'left')
                        ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                        ->where([
                            'usul_belanja_rpk.tahun' => $tahun,
                            'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm'],
                            'daftar_rka.id_prog' => $rowprog['id_prog']
                        ])
                        ->groupBy('daftar_rka.id_keg')
                        ->findAll();
                } else {
                    $kegiatan = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_prog, daftar_rka.id_keg, kegiatan.kode_keg as kode_keg, kegiatan.nama_keg as nama_keg, prog.kode_prog as kode_prog, prog.nama_prog as nama_prog, sum(usul_belanja_rpk.harga_total) as hargatotal')
                        ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                        ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                        ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                        ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                        ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                        ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                        ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                        ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                        ->join('prog', 'prog.id=daftar_rka.id_prog', 'left')
                        ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                        ->where([
                            'usul_belanja_rpk.tahun' => $tahun,
                            'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm'],
                            'daftar_rka.id_prog' => $rowprog['id_prog'],
                            'usul_belanja_rpk.id_pkm' => user()->puskesmas
                        ])
                        ->groupBy('daftar_rka.id_keg')
                        ->findAll();
                };



                $nor = 1;
                foreach ($kegiatan as $rowkeg) {

                    $isitampil .= "<tr>";
                    $isitampil .= "<td><i>" . $nor++ . ").</i></td>";
                    $isitampil .= "<td><i>" . $rowkeg['kode_keg'] . "</i></td>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td colspan='4'><i>" . $rowkeg['nama_keg'] . "</i></td>";
                    $isitampil .= "<td align ='right' > <i>" . $rowkeg['hargatotal'] . "</i></td>";
                    $isitampil .= "<td><a href='" . base_url() . "/cetaksub/cetak/" . $rowkeg['id_keg'] . "-" . $rowpkm['id_pkm'] . "' target='_blank' class='btn btn-warning'>Cetak</a></td>";
                    $isitampil .= "</tr>";

                    if (in_groups('admin') || in_groups('programmer')) {
                        $sub = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_keg, daftar_rka.kode as kode_rka, daftar_rka.judul, daftar_rka.kinerja,  daftar_rka.indikator, sum(usul_belanja_rpk.harga_total) as hargatotal')
                            ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                            ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                            ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                            ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                            ->where([
                                'usul_belanja_rpk.tahun' => $tahun,
                                'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm'],
                                'daftar_rka.id_keg' => $rowkeg['id_keg'],
                                'daftar_rka.id_prog' => $rowprog['id_prog']
                            ])
                            ->groupBy('sub_komponen.id_rka')
                            ->findAll();
                    } else {
                        $sub = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_keg, daftar_rka.kode as kode_rka, daftar_rka.judul, daftar_rka.kinerja,  daftar_rka.indikator, sum(usul_belanja_rpk.harga_total) as hargatotal')
                            ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                            ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                            ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                            ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                            ->where([
                                'usul_belanja_rpk.tahun' => $tahun,
                                'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm'],
                                'daftar_rka.id_keg' => $rowkeg['id_keg'],
                                'daftar_rka.id_prog' => $rowprog['id_prog'],
                                'usul_belanja_rpk.id_pkm' => user()->puskesmas
                            ])
                            ->groupBy('sub_komponen.id_rka')
                            ->findAll();
                    };



                    $nos = 97;
                    foreach ($sub as $rowsub) {

                        $isitampil .= "<tr>";
                        $isitampil .= "<td align ='right'>" . chr($nos++) . ".</td>";
                        $isitampil .= "<td>" . $rowsub['kode_rka'] . "</td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "<td>" . $rowsub['judul'] . "</td>";
                        $isitampil .= "<td>" . $rowsub['kinerja'] . "</td>";
                        $isitampil .= "<td>" . $rowsub['indikator'] . "</td>";
                        $isitampil .= "<td align ='right' >" . $rowsub['hargatotal'] . "</td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "</tr>";
                    }
                }
            }
        }


        $isitampil .= "</tbody>";
        $isitampil .= "</table>";

        $msg = [

            'data' => $isitampil,

        ];

        return $this->response->setJSON($msg);
    }

    public function expo()
    {
        $tahun = $this->session->get('db_tahun');
        $isitampil = "<table class='table table-bordered table-striped' style='border-width: medium'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style=text-align: center'>No.</th>";
        $isitampil .= "<th style=text-align: center'>Kode Sub Kegiatan</th>";
        $isitampil .= "<th align='center'>Program</th>";
        $isitampil .= "<th align='center'>Kegiatan</th>";
        $isitampil .= "<th align='center'>Sub Kegiatan</th>";
        $isitampil .= "<th align='center'>Kinerja</th>";
        $isitampil .= "<th align='center'>Indikator</th>";
        $isitampil .= "<th align='center'>Jumlah</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $puskesmas = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, sum(usul_belanja_rpk.harga_total) as hargatotal, ')->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')->where('usul_belanja_rpk.tahun', $tahun)->groupBy('usul_belanja_rpk.id_pkm')->findAll();

        $no = 1;
        foreach ($puskesmas as $rowpkm) {
            $isitampil .= "<tr>";
            $isitampil .= "<td><b>" . $no++ . ".</b></td>";
            $isitampil .= "<td colspan='6'><b>Puskesmas " . $rowpkm['pkm'] . "</b></td>";
            $isitampil .= "<td align ='right' ><b>" . number_format($rowpkm['hargatotal'], 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $program = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_keg, daftar_rka.id_prog, kegiatan.kode_keg as kode_keg, kegiatan.nama_keg as nama_keg, prog.kode_prog as kode_prog, prog.nama_prog as nama_prog, sum(usul_belanja_rpk.harga_total) as hargatotal')
                ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                ->join('prog', 'prog.id=daftar_rka.id_prog', 'left')
                ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                ->where([
                    'usul_belanja_rpk.tahun' => $tahun,
                    'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm']
                ])
                ->groupBy('daftar_rka.id_prog')
                ->findAll();

            $nox = 65;
            foreach ($program as $rowprog) {

                $isitampil .= "<tr>";
                $isitampil .= "<td><b>" . chr($nox++) . "</b></td>";
                $isitampil .= "<td><b>" . $rowprog['kode_prog'] . "</b></td>";
                $isitampil .= "<td colspan='5'><b>" . $rowprog['nama_prog'] . "</b></td>";
                $isitampil .= "<td align ='right' ><b>" . number_format($rowprog['hargatotal'], 0, ",", ".") . "</b></td>";
                $isitampil .= "</tr>";


                $kegiatan = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_prog, daftar_rka.id_keg, kegiatan.kode_keg as kode_keg, kegiatan.nama_keg as nama_keg, prog.kode_prog as kode_prog, prog.nama_prog as nama_prog, sum(usul_belanja_rpk.harga_total) as hargatotal')
                    ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                    ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                    ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                    ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                    ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                    ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                    ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                    ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                    ->join('prog', 'prog.id=daftar_rka.id_prog', 'left')
                    ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                    ->where([
                        'usul_belanja_rpk.tahun' => $tahun,
                        'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm'],
                        'daftar_rka.id_prog' => $rowprog['id_prog']
                    ])
                    ->groupBy('daftar_rka.id_keg')
                    ->findAll();

                $nor = 1;
                foreach ($kegiatan as $rowkeg) {

                    $isitampil .= "<tr>";
                    $isitampil .= "<td><i>" . $nor++ . ").</i></td>";
                    $isitampil .= "<td><i>" . $rowkeg['kode_keg'] . "</i></td>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td colspan='4'><i>" . $rowkeg['nama_keg'] . "</i></td>";
                    $isitampil .= "<td align ='right' > <i>" . number_format($rowkeg['hargatotal'], 0, ",", ".") . "</i></td>";
                    $isitampil .= "</tr>";

                    $sub = $this->usulanrpkModel->asArray()->select('usul_belanja_rpk.id_pkm, upt.pkm, daftar_rka.id_keg, daftar_rka.kode as kode_rka, daftar_rka.judul, daftar_rka.kinerja,  daftar_rka.indikator, sum(usul_belanja_rpk.harga_total) as hargatotal')
                        ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
                        ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                        ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
                        ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                        ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                        ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                        ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                        ->join('kegiatan', 'kegiatan.id=daftar_rka.id_keg', 'left')
                        ->join('upt', 'upt.id=usul_belanja_rpk.id_pkm', 'left')
                        ->where([
                            'usul_belanja_rpk.tahun' => $tahun,
                            'usul_belanja_rpk.id_pkm' => $rowpkm['id_pkm'],
                            'daftar_rka.id_keg' => $rowkeg['id_keg'],
                            'daftar_rka.id_prog' => $rowprog['id_prog']
                        ])
                        ->groupBy('sub_komponen.id_rka')
                        ->findAll();

                    $nos = 97;
                    foreach ($sub as $rowsub) {

                        $isitampil .= "<tr>";
                        $isitampil .= "<td align ='right'>" . chr($nos++) . ".</td>";
                        $isitampil .= "<td>" . $rowsub['kode_rka'] . "</td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "<td>" . $rowsub['judul'] . "</td>";
                        $isitampil .= "<td>" . $rowsub['kinerja'] . "</td>";
                        $isitampil .= "<td>" . $rowsub['indikator'] . "</td>";
                        $isitampil .= "<td align ='right' >" . number_format($rowsub['hargatotal'], 0, ",", ".") . "</td>";
                        $isitampil .= "</tr>";
                    }
                }
            }
        }
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";

        $data = [

            'result' => $isitampil,
            'tahun' => $tahun

        ];


        echo view('cetak_sub', $data);
    }



    public function cetak($id)
    {
        $data = explode('-', $id);
        $pkm = $this->uptModel->where('id', $data[1])->first();
        $keg = $this->kegiatanModel->select('kegiatan.kode_keg, kegiatan.nama_keg, prog.kode_prog, prog.nama_prog')->join('prog', 'kegiatan.kode_prog=prog.id')->where(['kegiatan.id' => $data[0]])->first();
        $isitampil = $id;
        $data = [
            'pkm' => $pkm,
            'keg' => $keg,
            'result' => $isitampil

        ];
        echo view('cetak_verif', $data);
    }
}