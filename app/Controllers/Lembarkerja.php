<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RukModel;
use App\Models\SatuanModel;
use App\Models\RpkModel;
use App\Models\ProgramModel;
use App\Models\SubkomponenModel;
use App\Models\KomponenModel;
use App\Models\PenyusunModel;
use App\Models\KodebelanjaModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\UsulanRpkModel;
use App\Models\UsulanRpkpModel;
use App\Models\RincianModel;
use App\Models\Mcountdown;

class Lembarkerja extends BaseController
{

    protected $rukModel;
    protected $satuanModel;
    protected $rpkModel;
    protected $programModel;
    protected $penyusunModel;
    protected $subkomponenModel;
    protected $komponenModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $usulanrpkModel;
    protected $usulanrpkpModel;
    protected $rincianModel;
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
        $this->subkomponenModel = new SubkomponenModel();
        $this->komponenModel = new KomponenModel();
        $this->kodebelanjaModel = new KodebelanjaModel();
        $this->uptModel = new UptModel();
        $this->usulanModel = new UsulanModel();
        $this->usulanrpkModel = new UsulanRpkModel();
        $this->usulanrpkpModel = new UsulanRpkpModel();
        $this->rincianModel = new RincianModel();
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'controller'        => 'lembarkerja',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('lembarkerja', $data);
        }
    }

    public function tampil()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');

        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style='text-align:center;'>No.</th>";
        $isitampil .= "<th style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th style='text-align:center;'>ANGGARAN MURNI</th>";
        $isitampil .= "<th style='text-align:center;'>ANGGARAN PERUBAHAN</th>";
        $isitampil .= "<th style='text-align:center;'>REALISASI ANGGARAN</th>";
        $isitampil .= "<th style='text-align:center;'>% REALISASI</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rinci = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sum(usul_belanja_rpk.harga_total) as harga_total')
            ->where('usul_belanja_rpk.id_pkm', $pkm)
            ->groupBy('usul_belanja_rpk.id_pkm')->findall();

        $nor = 65;

        foreach ($rinci as $key => $value) {
            $rinci2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sum(usul_belanja_rpkp.harga_total) as harga_total')

                ->where('usul_belanja_rpkp.id_pkm', $pkm)
                ->groupBy('usul_belanja_rpkp.id_pkm')->first();

            $real1 = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'left')
                ->where('usul_belanja_rpk.id_pkm', $pkm)
                ->groupBy('usul_belanja_rpk.id_pkm')->first();

            $pagu = $value->harga_total;
            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='2'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($rinci2->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($real1->jumlah, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format(($real1->jumlah / $value->harga_total) * 100, 2, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $rincian = $this->rincianModel->where('tahun', $tahun)->findAll();
            foreach ($rincian as $key => $value) {

                $lookrincian = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpk.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpk.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();

                $lookreal = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(realisasi.jumlah) as jumlah')
                    ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                    ->where(['usul_belanja_rpk.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();

                $lookrincianp = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpkp.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpkp.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();


                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='2'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";

                $murni = ($lookrincian != null) ? number_format($lookrincian->harga_total, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $murni . "</b></td>";
                $perubahan = ($lookrincianp != null) ? number_format($lookrincianp->harga_total, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $perubahan . "</b></td>";

                $realisasi = ($lookreal != null) ? number_format($lookreal->jumlah, 0, ",", ".") : 0;
                $persen1 = ($lookreal != null) ? number_format(($lookreal->jumlah / $pagu) * 100, 2, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $realisasi . "</b></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . $persen1 . "</b></td>";
                $isitampil .= "</tr>";

                $nu = 1;
                if ($nor == 69) {
                    $style2 = '';
                    $sub = $this->subkomponenModel->where(['id_rincian' => 4, 'tahun' => $tahun])->findAll();
                    foreach ($sub as $key => $value2) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;
                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;
                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;
                        $persen2 = ($realisasi != null) ? number_format(($realisasi->jumlah / $pagu) * 100, 2, ",", ".") : 0;

                        $style2 = ($aperubahan < $amurni) ? ' text-danger' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style2 . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style2 . "'>" . $value2->nama_subkomponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style2 . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style2 . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style2 . "'>" . $arealisasi . "</td>";
                        $isitampil .= "<td class='text-end" . $style2 . "'>" . $persen2 . "</td>";
                        $isitampil .= "</tr>";
                    };
                } elseif ($nor == 76) {
                    $style3 = '';
                    $sub = $this->subkomponenModel->where(['id_rincian' => 11, 'tahun' => $tahun])->findAll();
                    foreach ($sub as $key => $value2) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;

                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;

                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;
                        $persen3 = ($realisasi != null) ? number_format(($realisasi->jumlah / $pagu) * 100, 0, ",", ".") : 0;

                        $style3 = ($aperubahan < $amurni) ? ' text-primary' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style3 . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style3 . "'>" . $value2->nama_subkomponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style3 . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style3 . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style3 . "'>" . $arealisasi . "</td>";
                        $isitampil .= "<td class='text-end" . $style3 . "'>" . $persen3 . "</td>";
                        $isitampil .= "</tr>";
                    };
                } else {
                    $style = '';
                    $kom = $this->komponenModel->select('distinct(komponen.id), komponen.nama_komponen')->join('sub_komponen', 'sub_komponen.id_kom=komponen.id', 'left')->where('sub_komponen.id_rincian', $value->id)->findAll();
                    foreach ($kom as $key => $value3) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id_kom, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;

                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id_kom, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;

                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id_kom, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;
                        $persen4 = ($realisasi != null) ? number_format(($realisasi->jumlah / $pagu) * 100, 0, ",", ".") : 0;

                        $style = ($aperubahan < $amurni) ? ' text-danger' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style . "'>" . $value3->nama_komponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $arealisasi . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $persen4 . "</td>";
                        $isitampil .= "</tr>";
                    };
                }
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
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');

        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style='text-align:center;'>No.</th>";
        $isitampil .= "<th style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th style='text-align:center;'>ANGGARAN MURNI</th>";
        $isitampil .= "<th style='text-align:center;'>ANGGARAN PERUBAHAN</th>";
        $isitampil .= "<th style='text-align:center;'>REALISASI ANGGARAN</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rinci = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sum(usul_belanja_rpk.harga_total) as harga_total')
            ->where('usul_belanja_rpk.id_pkm', $pkm)
            ->groupBy('usul_belanja_rpk.id_pkm')->findall();

        $nor = 65;

        foreach ($rinci as $key => $value) {
            $rinci2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sum(usul_belanja_rpkp.harga_total) as harga_total')

                ->where('usul_belanja_rpkp.id_pkm', $pkm)
                ->groupBy('usul_belanja_rpkp.id_pkm')->first();

            $real1 = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'left')
                ->where('usul_belanja_rpk.id_pkm', $pkm)
                ->groupBy('usul_belanja_rpk.id_pkm')->first();

            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='2'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($rinci2->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($real1->jumlah, 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $rincian = $this->rincianModel->findAll();
            foreach ($rincian as $key => $value) {

                $lookrincian = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpk.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpk.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();

                $lookreal = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(realisasi.jumlah) as jumlah')
                    ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                    ->where(['usul_belanja_rpk.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();

                $lookrincianp = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpkp.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpkp.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();


                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='2'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";

                $murni = ($lookrincian != null) ? number_format($lookrincian->harga_total, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $murni . "</b></td>";
                $perubahan = ($lookrincianp != null) ? number_format($lookrincianp->harga_total, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $perubahan . "</b></td>";

                $realisasi = ($lookreal != null) ? number_format($lookreal->jumlah, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $realisasi . "</b></td>";
                $isitampil .= "</tr>";

                $nu = 1;
                if ($nor == 69) {

                    $sub = $this->subkomponenModel->where(['id_rincian' => 4, 'tahun' => $tahun])->findAll();
                    foreach ($sub as $key => $value2) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;
                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;
                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;

                        $style = ($aperubahan < $amurni) ? ' text-danger' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style . "'>" . $value2->nama_subkomponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $arealisasi . "</td>";
                        $isitampil .= "</tr>";
                    };
                } elseif ($nor == 76) {
                    $sub = $this->subkomponenModel->where(['id_rincian' => 11, 'tahun' => $tahun])->findAll();
                    foreach ($sub as $key => $value2) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;

                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;

                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;

                        $style = ($aperubahan < $amurni) ? ' text-info' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style . "'>" . $value2->nama_subkomponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $arealisasi . "</td>";
                        $isitampil .= "</tr>";
                    };
                } else {
                    $kom = $this->komponenModel->select('distinct(komponen.id), komponen.nama_komponen')->join('sub_komponen', 'sub_komponen.id_kom=komponen.id', 'left')->where('sub_komponen.id_rincian', $value->id)->findAll();
                    foreach ($kom as $key => $value3) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id_kom, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;

                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id_kom, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;

                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id_kom, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;

                        $style = ($aperubahan < $amurni) ? ' text-danger' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style . "'>" . $value3->nama_komponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $arealisasi . "</td>";
                        $isitampil .= "</tr>";
                    };
                }
            };
        };

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $data = [
            'result' => $isitampil,
            'puskesmas' => $puskesmas,
        ];


        echo view('cetak_lk', $data);
    }

    public function expo()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');

        $isitampil = "<table style='border:1;'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th style='text-align:center;'>No.</th>";
        $isitampil .= "<th style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th style='text-align:center;'>ANGGARAN MURNI</th>";
        $isitampil .= "<th style='text-align:center;'>ANGGARAN PERUBAHAN</th>";
        $isitampil .= "<th style='text-align:center;'>REALISASI ANGGARAN</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rinci = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sum(usul_belanja_rpk.harga_total) as harga_total')
            ->where('usul_belanja_rpk.id_pkm', $pkm)
            ->groupBy('usul_belanja_rpk.id_pkm')->findall();

        $nor = 65;

        foreach ($rinci as $key => $value) {
            $rinci2 = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sum(usul_belanja_rpkp.harga_total) as harga_total')

                ->where('usul_belanja_rpkp.id_pkm', $pkm)
                ->groupBy('usul_belanja_rpkp.id_pkm')->first();

            $real1 = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'left')
                ->where('usul_belanja_rpk.id_pkm', $pkm)
                ->groupBy('usul_belanja_rpk.id_pkm')->first();

            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='2'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($rinci2->harga_total, 0, ",", ".") . "</b></td>";
            $isitampil .= "<td style='text-align:right;'> <b>" . number_format($real1->jumlah, 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $rincian = $this->rincianModel->findAll();
            foreach ($rincian as $key => $value) {

                $lookrincian = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpk.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpk.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();

                $lookreal = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(realisasi.jumlah) as jumlah')
                    ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                    ->where(['usul_belanja_rpk.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();

                $lookrincianp = $this->usulanrpkpModel->select('usul_belanja_rpkp.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpkp.harga_total) as harga_total')
                    ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                    ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                    ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                    ->where(['usul_belanja_rpkp.id_pkm' => $pkm, 'rincian.id' => $value->id])
                    ->groupBy('sub_komponen.id_rincian')->first();


                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='2'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";

                $murni = ($lookrincian != null) ? number_format($lookrincian->harga_total, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $murni . "</b></td>";
                $perubahan = ($lookrincianp != null) ? number_format($lookrincianp->harga_total, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $perubahan . "</b></td>";

                $realisasi = ($lookreal != null) ? number_format($lookreal->jumlah, 0, ",", ".") : 0;
                $isitampil .= "<td style='text-align:right;'><b>" . $realisasi . "</b></td>";
                $isitampil .= "</tr>";

                $nu = 1;
                if ($nor == 69) {

                    $sub = $this->subkomponenModel->where(['id_rincian' => 4, 'tahun' => $tahun])->findAll();
                    foreach ($sub as $key => $value2) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;
                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;
                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();

                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;

                        $style = ($aperubahan < $amurni) ? ' text-danger' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style . "'>" . $value2->nama_subkomponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $arealisasi . "</td>";
                        $isitampil .= "</tr>";
                    };
                } elseif ($nor == 76) {
                    $sub = $this->subkomponenModel->where(['id_rincian' => 11, 'tahun' => $tahun])->findAll();
                    foreach ($sub as $key => $value2) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;

                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;

                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id' => $value2->id
                            ])
                            ->groupBy('sub_komponen.id')
                            ->first();
                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;

                        $style = ($aperubahan < $amurni) ? ' text-danger' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style . "'>" . $value2->nama_subkomponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $arealisasi . "</td>";
                        $isitampil .= "</tr>";
                    };
                } else {
                    $kom = $this->komponenModel->select('distinct(komponen.id), komponen.nama_komponen')->join('sub_komponen', 'sub_komponen.id_kom=komponen.id', 'left')->where('sub_komponen.id_rincian', $value->id)->findAll();
                    foreach ($kom as $key => $value3) {
                        $murni = $this->usulanrpkModel->select('sub_komponen.id_kom, sum(usul_belanja_rpk.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $amurni = ($murni != null) ? number_format($murni->harga_total, 0, ",", ".") : 0;

                        $perubahan = $this->usulanrpkpModel->select('sub_komponen.id_kom, sum(usul_belanja_rpkp.harga_total) as harga_total')
                            ->join('rpk', 'rpk.id=usul_belanja_rpkp.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $aperubahan = ($perubahan != null) ? number_format($perubahan->harga_total, 0, ",", ".") : 0;

                        $realisasi = $this->usulanrpkModel->select('sub_komponen.id_kom, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah')
                            ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'right')
                            ->where([
                                'usul_belanja_rpk.id_pkm' => $pkm,
                                'sub_komponen.id_kom' => $value3->id
                            ])
                            ->groupBy('sub_komponen.id_kom')
                            ->first();
                        $arealisasi = ($realisasi != null) ? number_format($realisasi->jumlah, 0, ",", ".") : 0;

                        $style = ($aperubahan < $amurni) ? ' text-danger' : '';
                        $isitampil .= "<tr>";
                        $isitampil .= "<td class='" . $style . "'>" . $nu++ . ".</td>";
                        $isitampil .= "<td class='" . $style . "'>" . $value3->nama_komponen . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $amurni . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $aperubahan . "</td>";
                        $isitampil .= "<td class='text-end" . $style . "'>" . $arealisasi . "</td>";
                        $isitampil .= "</tr>";
                    };
                }
            };
        };

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $data = [
            'result' => $isitampil,
            'puskesmas' => $puskesmas,
        ];


        echo view('cetak_lk2', $data);
    }
}