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

class Rrealisasi extends BaseController
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
            'controller'        => 'rrealisasi',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {

            $data['tahun'] = $tahun;
            return view('rrealisasi', $data);
        }
    }

    public function tampil()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');
        $bln = $this->request->getVar('bulan');
        $bln2 = $this->request->getVar('bulan2');
        $bulan = 'b' . $bln;
        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2' style='text-align:center;'>No.</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2' style='text-align:center;'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>RPK 1 TAHUN</th>";

        $isitampil .= "<th rowspan='2' style='text-align:center;'>Jumlah Realisasi</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>Sisa Anggaran</th>";
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
            $total = $this->rpkModel->select('rpk.id_pkm, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->where(['id_pkm' => $pkm])->groupBy('rpk.id_pkm')->first();

            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='6'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


            if ($total->jumlah2 != null) {
                $isitampil .= "<td class='text-end'><b>" . number_format($total->jumlah2, 0, ",", ".") . "</b></td>";
                $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $total->jumlah2, 0, ",", ".") . "</b></td>";
            } else {
                $isitampil .= "<td class='text-end'><b>0</b></td>";
                $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            }

            $isitampil .= "</tr>";


            $rincian = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpk.harga_total) as harga_total, sum(usul_belanja_rpk.' . $bulan . ') as harga_bulan')
                ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                ->where('usul_belanja_rpk.id_pkm', $pkm)
                ->groupBy('sub_komponen.id_rincian')->findall();
            $nor = 65;

            foreach ($rincian as $key => $value) {
                $rincian = $this->rpkModel->select('sub_komponen.id_rincian, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])->groupBy('sub_komponen.id_rincian')->first();

                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='6'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


                if ($rincian->jumlah2 != null) {
                    $isitampil .= "<td class='text-end'><b>" . number_format($rincian->jumlah2, 0, ",", ".") . "</b></td>";
                    $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $rincian->jumlah2, 0, ",", ".") . "</b></td>";
                } else {
                    $isitampil .= "<td class='text-end'><b>0</b></td>";
                    $isitampil .= "<td class='text-end'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                }


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

                    $rpk = $this->rpkModel->select('sub_komponen.id_kom, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_kom' => $value->id_kom])->groupBy('sub_komponen.id_kom')->first();


                    $isitampil .= "<tr>";
                    $isitampil .= "<td colspan='6'><b>" . $nox++ . ". " . $value->nama_komponen . "</b></td>";
                    $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


                    if ($rpk->jumlah2 != null) {
                        $isitampil .= "<td class='text-end'><b>" . number_format($rpk->jumlah2, 0, ",", ".") . "</b></td>";
                        $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $rpk->jumlah2, 0, ",", ".") . "</b></td>";
                    } else {
                        $isitampil .= "<td class='text-end'><b>0</b></td>";
                        $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
                    };
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


                        $realisasi = $this->realisasiModel->select('usul_belanja_rpk.id_sub, sum(realisasi.jumlah) as jumlah')->join('usul_belanja_rpk', 'usul_belanja_rpk.id=realisasi.id_rpk')->where(['usul_belanja_rpk.id_sub' => $row['id'], 'realisasi.bulan >=' => $bln, 'realisasi.bulan <=' => $bln2, 'realisasi.tahun' => $tahun])->groupBy('usul_belanja_rpk.id_sub')->first();
                        $isitampil .= "<tr>";
                        $isitampil .= "<td><b>" . chr($no++) . ".</b></td>";
                        $isitampil .= "<td colspan='5'><b>" . $row['nama_subkomponen'] . "</b></td>";
                        $harga_total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
                        $isitampil .= "<td style='text-align:right;'> <b>" . $harga_total . "</b></td>";


                        $arealisasi = ($realisasi != null) ?: 0;
                        if ($realisasi != null) {
                            $isitampil .= "<td class='text-end'><b>" . number_format($realisasi->jumlah, 0, ",", ".") . "</b></td>";
                            $isitampil .= "<td class='text-end'><b>" . number_format($row['harga_total'] - $realisasi->jumlah, 0, ",", ".") . "</b></td>";
                        } else {
                            $isitampil .= "<td class='text-end'><b>0</b></td>";
                            $isitampil .= "<td class='text-end'><b>" . $harga_total . "</b></td>";
                        };

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

                            $realisasi = $this->realisasiModel->select('sum(jumlah) as jumlah')->where([
                                'id_rpk' => $value->id,
                                'bulan>=' => $bln,
                                'bulan<=' => $bln2,
                                'tahun' => $tahun
                            ])
                                ->first();

                            if ($realisasi->jumlah != null) {
                                $isitampil .= "<td class='text-end'>" . number_format($realisasi->jumlah, 0, ",", ".") . "</td>";
                                $isitampil .= "<td class='text-end'>" . number_format($value->harga_total - $realisasi->jumlah, 0, ",", ".") . "</td>";
                            } else {
                                $isitampil .= "<td class='text-end'>0</td>";
                                $isitampil .= "<td class='text-end'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                            };



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
            'bulan' => $bln,
            'bulan2' => $bln2,
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
        $bln = $this->request->getVar('bulan');
        $bln2 = $this->request->getVar('bulan2');
        $bulan = 'b' . $bln;
        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2' style='text-align:center;'>No.</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2' style='text-align:center;'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>RPK 1 TAHUN</th>";

        $isitampil .= "<th rowspan='2' style='text-align:center;'>Jumlah Realisasi</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>Sisa Anggaran</th>";
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
            $total = $this->rpkModel->select('rpk.id_pkm, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->where(['id_pkm' => $pkm])->groupBy('rpk.id_pkm')->first();

            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='6'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


            if ($total->jumlah2 != null) {
                $isitampil .= "<td class='text-end'><b>" . number_format($total->jumlah2, 0, ",", ".") . "</b></td>";
                $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $total->jumlah2, 0, ",", ".") . "</b></td>";
            } else {
                $isitampil .= "<td class='text-end'><b>0</b></td>";
                $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            }

            $isitampil .= "</tr>";


            $rincian = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpk.harga_total) as harga_total, sum(usul_belanja_rpk.' . $bulan . ') as harga_bulan')
                ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                ->where('usul_belanja_rpk.id_pkm', $pkm)
                ->groupBy('sub_komponen.id_rincian')->findall();
            $nor = 65;

            foreach ($rincian as $key => $value) {
                $rincian = $this->rpkModel->select('sub_komponen.id_rincian, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])->groupBy('sub_komponen.id_rincian')->first();

                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='6'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


                if ($rincian->jumlah2 != null) {
                    $isitampil .= "<td class='text-end'><b>" . number_format($rincian->jumlah2, 0, ",", ".") . "</b></td>";
                    $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $rincian->jumlah2, 0, ",", ".") . "</b></td>";
                } else {
                    $isitampil .= "<td class='text-end'><b>0</b></td>";
                    $isitampil .= "<td class='text-end'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                }


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

                    $rpk = $this->rpkModel->select('sub_komponen.id_kom, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_kom' => $value->id_kom])->groupBy('sub_komponen.id_kom')->first();


                    $isitampil .= "<tr>";
                    $isitampil .= "<td colspan='6'><b>" . $nox++ . ". " . $value->nama_komponen . "</b></td>";
                    $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


                    if ($rpk->jumlah2 != null) {
                        $isitampil .= "<td class='text-end'><b>" . number_format($rpk->jumlah2, 0, ",", ".") . "</b></td>";
                        $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $rpk->jumlah2, 0, ",", ".") . "</b></td>";
                    } else {
                        $isitampil .= "<td class='text-end'><b>0</b></td>";
                        $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
                    };
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


                        $realisasi = $this->realisasiModel->select('usul_belanja_rpk.id_sub, sum(realisasi.jumlah) as jumlah')->join('usul_belanja_rpk', 'usul_belanja_rpk.id=realisasi.id_rpk')->where(['usul_belanja_rpk.id_sub' => $row['id'], 'realisasi.bulan >=' => $bln, 'realisasi.bulan <=' => $bln2, 'realisasi.tahun' => $tahun])->groupBy('usul_belanja_rpk.id_sub')->first();
                        $isitampil .= "<tr>";
                        $isitampil .= "<td><b>" . chr($no++) . ".</b></td>";
                        $isitampil .= "<td colspan='5'><b>" . $row['nama_subkomponen'] . "</b></td>";
                        $harga_total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
                        $isitampil .= "<td style='text-align:right;'> <b>" . $harga_total . "</b></td>";


                        $arealisasi = ($realisasi != null) ?: 0;
                        if ($realisasi != null) {
                            $isitampil .= "<td class='text-end'><b>" . number_format($realisasi->jumlah, 0, ",", ".") . "</b></td>";
                            $isitampil .= "<td class='text-end'><b>" . number_format($row['harga_total'] - $realisasi->jumlah, 0, ",", ".") . "</b></td>";
                        } else {
                            $isitampil .= "<td class='text-end'><b>0</b></td>";
                            $isitampil .= "<td class='text-end'><b>" . $harga_total . "</b></td>";
                        };

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

                            $realisasi = $this->realisasiModel->select('sum(jumlah) as jumlah')->where([
                                'id_rpk' => $value->id,
                                'bulan>=' => $bln,
                                'bulan<=' => $bln2,
                                'tahun' => $tahun
                            ])
                                ->first();

                            if ($realisasi->jumlah != null) {
                                $isitampil .= "<td class='text-end'>" . number_format($realisasi->jumlah, 0, ",", ".") . "</td>";
                                $isitampil .= "<td class='text-end'>" . number_format($value->harga_total - $realisasi->jumlah, 0, ",", ".") . "</td>";
                            } else {
                                $isitampil .= "<td class='text-end'>0</td>";
                                $isitampil .= "<td class='text-end'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                            };



                            $isitampil .= "</tr>";
                        };
                    };
                };
            };
        };

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $bul = array("Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $data = [
            'bulan1'    => $bul[$bln - 1],
            'bulan2'    => $bul[$bln2 - 1],
            'tahun'     => $tahun,
            'result'    => $isitampil,
            'puskesmas' => $puskesmas['pkm'],
            'pkm'       => $puskesmas['id']
        ];

        echo view('cetak/cetak_realisasi', $data);
    }

    public function expo()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');
        $bln = $this->request->getVar('bulan');
        $bln2 = $this->request->getVar('bulan2');
        $bulan = 'b' . $bln;
        $isitampil = "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' colspan='2' style='text-align:center;'>No.</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>KOMPONEN RINCIAN MENU KEGIATAN </th>";
        $isitampil .= "<th colspan='2' style='text-align:center;'>KOMPONEN PEMBIAYAAN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>URAIAN KOMPONEN</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>RPK 1 TAHUN</th>";

        $isitampil .= "<th rowspan='2' style='text-align:center;'>Jumlah Realisasi</th>";
        $isitampil .= "<th rowspan='2' style='text-align:center;'>Sisa Anggaran</th>";
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
            $total = $this->rpkModel->select('rpk.id_pkm, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->where(['id_pkm' => $pkm])->groupBy('rpk.id_pkm')->first();

            $isitampil .= "<tr>";
            $isitampil .= "<td colspan='6'><b>PAGU INDIKATIF</b></td>";
            $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


            if ($total->jumlah2 != null) {
                $isitampil .= "<td class='text-end'><b>" . number_format($total->jumlah2, 0, ",", ".") . "</b></td>";
                $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $total->jumlah2, 0, ",", ".") . "</b></td>";
            } else {
                $isitampil .= "<td class='text-end'><b>0</b></td>";
                $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
            }

            $isitampil .= "</tr>";


            $rincian = $this->usulanrpkModel->select('usul_belanja_rpk.id_pkm,sub_komponen.id_rincian, rincian.nama_rincian, sum(usul_belanja_rpk.harga_total) as harga_total, sum(usul_belanja_rpk.' . $bulan . ') as harga_bulan')
                ->join('rpk', 'rpk.id=usul_belanja_rpk.id_sub')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
                ->where('usul_belanja_rpk.id_pkm', $pkm)
                ->groupBy('sub_komponen.id_rincian')->findall();
            $nor = 65;

            foreach ($rincian as $key => $value) {
                $rincian = $this->rpkModel->select('sub_komponen.id_rincian, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_rincian' => $value->id_rincian])->groupBy('sub_komponen.id_rincian')->first();

                $isitampil .= "<tr>";
                $isitampil .= "<td colspan='6'><b>" . chr($nor++) . ". " . $value->nama_rincian . "</b></td>";
                $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


                if ($rincian->jumlah2 != null) {
                    $isitampil .= "<td class='text-end'><b>" . number_format($rincian->jumlah2, 0, ",", ".") . "</b></td>";
                    $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $rincian->jumlah2, 0, ",", ".") . "</b></td>";
                } else {
                    $isitampil .= "<td class='text-end'><b>0</b></td>";
                    $isitampil .= "<td class='text-end'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                }


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

                    $rpk = $this->rpkModel->select('sub_komponen.id_kom, sum((select sum(jumlah) from realisasi JOIN usul_belanja_rpk ON usul_belanja_rpk.id=realisasi.id_rpk WHERE rpk.id=usul_belanja_rpk.id_sub AND realisasi.bulan >=' . $bln . ' AND realisasi.bulan <=' . $bln2 . ' AND realisasi.tahun=' . $tahun . ' GROUP BY usul_belanja_rpk.id_sub)) as jumlah2')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['id_pkm' => $pkm, 'sub_komponen.id_kom' => $value->id_kom])->groupBy('sub_komponen.id_kom')->first();


                    $isitampil .= "<tr>";
                    $isitampil .= "<td colspan='6'><b>" . $nox++ . ". " . $value->nama_komponen . "</b></td>";
                    $isitampil .= "<td style='text-align:right;'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";


                    if ($rpk->jumlah2 != null) {
                        $isitampil .= "<td class='text-end'><b>" . number_format($rpk->jumlah2, 0, ",", ".") . "</b></td>";
                        $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total - $rpk->jumlah2, 0, ",", ".") . "</b></td>";
                    } else {
                        $isitampil .= "<td class='text-end'><b>0</b></td>";
                        $isitampil .= "<td class='text-end'><b>" . number_format($value->harga_total, 0, ",", ".") . "</b></td>";
                    };
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


                        $realisasi = $this->realisasiModel->select('usul_belanja_rpk.id_sub, sum(realisasi.jumlah) as jumlah')->join('usul_belanja_rpk', 'usul_belanja_rpk.id=realisasi.id_rpk')->where(['usul_belanja_rpk.id_sub' => $row['id'], 'realisasi.bulan >=' => $bln, 'realisasi.bulan <=' => $bln2, 'realisasi.tahun' => $tahun])->groupBy('usul_belanja_rpk.id_sub')->first();
                        $isitampil .= "<tr>";
                        $isitampil .= "<td><b>" . chr($no++) . ".</b></td>";
                        $isitampil .= "<td colspan='5'><b>" . $row['nama_subkomponen'] . "</b></td>";
                        $harga_total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
                        $isitampil .= "<td style='text-align:right;'> <b>" . $harga_total . "</b></td>";


                        $arealisasi = ($realisasi != null) ?: 0;
                        if ($realisasi != null) {
                            $isitampil .= "<td class='text-end'><b>" . number_format($realisasi->jumlah, 0, ",", ".") . "</b></td>";
                            $isitampil .= "<td class='text-end'><b>" . number_format($row['harga_total'] - $realisasi->jumlah, 0, ",", ".") . "</b></td>";
                        } else {
                            $isitampil .= "<td class='text-end'><b>0</b></td>";
                            $isitampil .= "<td class='text-end'><b>" . $harga_total . "</b></td>";
                        };

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

                            $realisasi = $this->realisasiModel->select('sum(jumlah) as jumlah')->where([
                                'id_rpk' => $value->id,
                                'bulan>=' => $bln,
                                'bulan<=' => $bln2,
                                'tahun' => $tahun
                            ])
                                ->first();

                            if ($realisasi->jumlah != null) {
                                $isitampil .= "<td class='text-end'>" . number_format($realisasi->jumlah, 0, ",", ".") . "</td>";
                                $isitampil .= "<td class='text-end'>" . number_format($value->harga_total - $realisasi->jumlah, 0, ",", ".") . "</td>";
                            } else {
                                $isitampil .= "<td class='text-end'>0</td>";
                                $isitampil .= "<td class='text-end'>" . number_format($value->harga_total, 0, ",", ".") . "</td>";
                            };



                            $isitampil .= "</tr>";
                        };
                    };
                };
            };
        };

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $bul = array("Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $data = [
            'bulan1'    => $bul[$bln - 1],
            'bulan2'    => $bul[$bln2 - 1],
            'tahun'     => $tahun,
            'result'    => $isitampil,
            'puskesmas' => $puskesmas['pkm'],
            'pkm'       => $puskesmas['id']
        ];

        echo view('cetak/cetak_xrealisasi', $data);
    }
}