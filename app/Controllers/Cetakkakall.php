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
use App\Models\Mcountdown;

class Cetakkakall extends BaseController
{

    protected $rukModel;
    protected $rpkModel;
    protected $programModel;
    protected $penyusunModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $usulanrpkModel;
    protected $mcountdown;
    protected $validation;

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
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'controller'        => 'cetakkakall',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];
        // dd($result);
        return view('cetakkakall', $data);
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


    public function tampil()
    {
        $pkm = $this->request->getVar('pkm');
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();

        $isitampil = "<h4><b>A. LATAR BELAKANG</b></h4>";
        $isitampil .= "<h5>1. Dasar Hukum</h5>";
        $isitampil .= "<p>a. Peraturan Menteri Kesehatan No. 44 tahun 2016 Tentang Manajemen Puskesmas</p>";
        $isitampil .= "<p>b. Peraturan Menteri Kesehatan No. 43 tahun 2019 Tentang Pusat Kesehatan Masyarakat</p>";
        $isitampil .= "<p>c. Surat Sekretariat Jenderal Kementerian Kesehatan Nomor : PR.01.01/I/18370/2021 Perihal Penyampaian Rincian Kegiatan DAK Non Fisik Bidang Kesehatan Tahun 2022</p>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h5>2. Gambaran Umum</h5>";
        $isitampil .= "<p>Kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Uraian</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rincian = $this->rpkModel->select('sub_komponen.id_rincian, rincian.nama_rincian')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
            ->where('rpk.id_pkm', $pkm)->groupBy('sub_komponen.id_rincian')->findall();
        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='3'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,
                ])->findAll();
            $no = 1;
            foreach ($result as $row) {
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
                $isitampil .= "<td> Adalah kegiatan " . $row['keterangan'] . " yang bertujuan untuk " . $row['tujuan'] . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h4><b>B. PENERIMA MANFAAT</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Penerima Manfaat</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='3'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,
                ])->findAll();
            $no = 1;
            foreach ($result as $row) {
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
                $isitampil .= "<td>" . $row['target'] . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h4><b>C. STRATEGI PENCAPAIAN KELUARAN</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Output</th>";
        $isitampil .= "<th style='text-align: center;'>Metode Pelaksanaan</th>";
        $isitampil .= "<th style='text-align: center;'>Tahapan Pelaksanaan</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='5'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join2 = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result2 = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, ruk.indikator,ruk.mitra,ruk.waktu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join2, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('ruk', 'ruk.id=rpk.id_ruk')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,

                ])->findAll();
            $no = 1;
            foreach ($result2 as $row2) {
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row2['nama_subkomponen'] . "</td>";
                $isitampil .= "<td>" . $row2['indikator'] . "</td>";
                $isitampil .= "<td>Swakelola</td>";
                $isitampil .= "<td> Dilaksanakan oleh :" . $row2['tgjawab'] . "</br> Mitra Kerja :" . $row2['mitra'] . "</br> Waktu :" . $row2['waktu'] . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h4><b>D. KURUN WAKTU PENCAPAIAN</b></h4>";
        $isitampil .= "<p>Kegiatan ini dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " dari Bulan Januari 2022 s.d. Bulan Desember 2022</p>";
        $isitampil .= "<h4><b>E. BIAYA YANG DIPERLUKAN</b></h4>";
        $isitampil .= "<p>Biaya yang diperlukan untuk pencapaian keluaran Bantuan Operasional Kesehatan Puskesmas dengan kebutuhan per rincian menu
        kegiatan sebagai berikut:
         </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Kebutuhan Biaya</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";


        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='3'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,
                ])->findAll();
            $no = 1;
            $biaya_bongkar = array();
            $total_biaya = 0;
            foreach ($result as $row) {

                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
                $isitampil .= "<td style='text-align:right;'>" . number_format($row['harga_total']) . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
            ])->findAll();
        foreach ($result as $row) {
            $biaya_bongkar[] = $row['harga_total'];
            $total_biaya = array_sum($biaya_bongkar);
        };

        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='3' style='text-align: center;'><b>Total</b></td>";
        $isitampil .= "<td style='text-align:right;'> <b>" . number_format($total_biaya) . "</b></td>";
        $isitampil .= "</tr>";
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";

        $isitampil .= "<p>Rincian Anggaran Biaya (RAB) terlampir</p>";
        $msg = [
            'data' => $isitampil,
            'puskesmas' => $puskesmas['pkm']
        ];

        return $this->response->setJSON($msg);
    }

    public function cetak()
    {
        $pkm = $this->request->getVar('pkm');
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();

        $isitampil = "<h4><b>A. LATAR BELAKANG</b></h4>";
        $isitampil .= "<h5>1. Dasar Hukum</h5>";
        $isitampil .= "<p>a. Peraturan Menteri Kesehatan No. 44 tahun 2016 Tentang Manajemen Puskesmas</p>";
        $isitampil .= "<p>b. Peraturan Menteri Kesehatan No. 43 tahun 2019 Tentang Pusat Kesehatan Masyarakat</p>";
        $isitampil .= "<p>c. Surat Sekretariat Jenderal Kementerian Kesehatan Nomor : PR.01.01/I/18370/2021 Perihal Penyampaian Rincian Kegiatan DAK Non Fisik Bidang Kesehatan Tahun 2022</p>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h5>2. Gambaran Umum</h5>";
        $isitampil .= "<p>Kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Uraian</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $rincian = $this->rpkModel->select('sub_komponen.id_rincian, rincian.nama_rincian')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->join('rincian', 'rincian.id=sub_komponen.id_rincian')
            ->where('rpk.id_pkm', $pkm)->groupBy('sub_komponen.id_rincian')->findall();
        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='3'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,
                ])->findAll();
            $no = 1;
            foreach ($result as $row) {
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
                $isitampil .= "<td> Adalah kegiatan " . $row['keterangan'] . " yang bertujuan untuk " . $row['tujuan'] . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h4><b>B. PENERIMA MANFAAT</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Penerima Manfaat</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='3'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,
                ])->findAll();
            $no = 1;
            foreach ($result as $row) {
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
                $isitampil .= "<td>" . $row['target'] . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h4><b>C. STRATEGI PENCAPAIAN KELUARAN</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Output</th>";
        $isitampil .= "<th style='text-align: center;'>Metode Pelaksanaan</th>";
        $isitampil .= "<th style='text-align: center;'>Tahapan Pelaksanaan</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='5'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join2 = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result2 = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, ruk.indikator,ruk.mitra,ruk.waktu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join2, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->join('ruk', 'ruk.id=rpk.id_ruk')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,

                ])->findAll();
            $no = 1;
            foreach ($result2 as $row2) {
                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row2['nama_subkomponen'] . "</td>";
                $isitampil .= "<td>" . $row2['indikator'] . "</td>";
                $isitampil .= "<td>Swakelola</td>";
                $isitampil .= "<td> Dilaksanakan oleh :" . $row2['tgjawab'] . "</br> Mitra Kerja :" . $row2['mitra'] . "</br> Waktu :" . $row2['waktu'] . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h4><b>D. KURUN WAKTU PENCAPAIAN</b></h4>";
        $isitampil .= "<p>Kegiatan ini dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " dari Bulan Januari 2022 s.d. Bulan Desember 2022</p>";
        $isitampil .= "<h4><b>E. BIAYA YANG DIPERLUKAN</b></h4>";
        $isitampil .= "<p>Biaya yang diperlukan untuk pencapaian keluaran Bantuan Operasional Kesehatan Puskesmas dengan kebutuhan per rincian menu
        kegiatan sebagai berikut:
         </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th colspan='2'>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Kebutuhan Biaya</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";


        $nox = 1;
        foreach ($rincian as $key => $value) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $nox++ . "</td>";
            $isitampil .= "<td colspan='3'><b>" . $value->nama_rincian . "</b></td>";
            $isitampil .= "</tr>";

            $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
            $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
                ->join($join, 'b.id_sub=rpk.id', 'left')
                ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
                ->where([
                    'rpk.id_pkm' => $pkm,
                    'sub_komponen.id_rincian' => $value->id_rincian,
                ])->findAll();
            $no = 1;
            $biaya_bongkar = array();
            $total_biaya = 0;
            foreach ($result as $row) {

                $isitampil .= "<tr>";
                $isitampil .= "<td></td>";
                $isitampil .= "<td>" . $no++ . "</td>";
                $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
                $isitampil .= "<td style='text-align:right;'>" . number_format($row['harga_total']) . "</td>";
                $isitampil .= "</tr>";
            };
        };
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
            ])->findAll();
        foreach ($result as $row) {
            $biaya_bongkar[] = $row['harga_total'];
            $total_biaya = array_sum($biaya_bongkar);
        };

        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='3' style='text-align: center;'><b>Total</b></td>";
        $isitampil .= "<td style='text-align:right;'> <b>" . number_format($total_biaya) . "</b></td>";
        $isitampil .= "</tr>";
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";

        $isitampil .= "<p>Rincian Anggaran Biaya (RAB) terlampir</p>";
        $data = [
            'result' => $isitampil,
            'puskesmas' => $puskesmas,
        ];


        echo view('cetak_kak_all', $data);
    }
}