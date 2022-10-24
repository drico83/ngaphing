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

class Cetakkak extends BaseController
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
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'controller'        => 'cetakkak',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];
        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('cetakkak', $data);
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


    public function tampil()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');
        $prog = $this->request->getVar('prog');
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $program = $this->programModel->asArray()->where('id', $prog)->first();
        $isitampil = "<h4><b>A. LATAR BELAKANG</b></h4>";
        $isitampil .= "<h5>1. Dasar Hukum</h5>";
        $isitampil .= "<p>a. Peraturan Menteri Kesehatan No. 44 tahun 2016 Tentang Manajemen Puskesmas</p>";
        $isitampil .= "<p>b. Peraturan Menteri Kesehatan No. 43 tahun 2019 Tentang Pusat Kesehatan Masyarakat</p>";
        $isitampil .= "<p>c. Surat Sekretariat Jenderal Kementerian Kesehatan Nomor : PR.01.01/I/18370/2021 Perihal Penyampaian Rincian Kegiatan DAK Non Fisik Bidang Kesehatan Tahun 2022</p>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h5>2. Gambaran Umum</h5>";
        $isitampil .= "<p>Kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " untuk program " . $program['nama_program'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Uraian</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk WHERE usul_belanja_rpk.tahun=" . $tahun . "  GROUP BY usul_belanja_rpk.id_sub) as b";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
                'rpk.tahun' => $tahun,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;
        foreach ($result as $row) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td> Adalah kegiatan " . $row['keterangan'] . " yang bertujuan untuk " . $row['tujuan'] . "</td>";
            $isitampil .= "</tr>";
        }
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "</br";
        $isitampil .= "<h4><b>B. PENERIMA MANFAAT</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " untuk program " . $program['nama_program'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Penerima Manfaat</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $no = 1;
        foreach ($result as $row) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $row['target'] . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "</br";
        $isitampil .= "<h4><b>C. STRATEGI PENCAPAIAN KELUARAN</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " untuk program " . $program['nama_program'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Output</th>";
        $isitampil .= "<th style='text-align: center;'>Metode Pelaksanaan</th>";
        $isitampil .= "<th style='text-align: center;'>Tahapan Pelaksanaan</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join2 = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk WHERE usul_belanja_rpk.tahun=" . $tahun . "  GROUP BY usul_belanja_rpk.id_sub) as b";
        $result2 = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, ruk.indikator,ruk.mitra,ruk.waktu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join2, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->join('ruk', 'ruk.id=rpk.id_ruk')
            ->where([
                'rpk.id_pkm' => $pkm,
                'rpk.tahun' => $tahun,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;
        foreach ($result2 as $row2) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row2['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $row2['indikator'] . "</td>";
            $isitampil .= "<td>Swakelola</td>";
            $isitampil .= "<td> Dilaksanakan oleh :" . $row2['tgjawab'] . "</br> Mitra Kerja :" . $row2['mitra'] . "</br> Waktu :" . $row2['waktu'] . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "</br";
        $isitampil .= "<h4><b>D. KURUN WAKTU PENCAPAIAN</b></h4>";
        $isitampil .= "<p>Kegiatan ini dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " dari Bulan Januari 2022 s.d. Bulan Desember 2022</p>";
        $isitampil .= "<h4><b>E. KURUN WAKTU PENCAPAIAN</b></h4>";
        $isitampil .= "<p>Biaya yang diperlukan untuk pencapaian keluaran Bantuan Operasional Kesehatan Puskesmas dengan kebutuhan per rincian menu
        kegiatan sebagai berikut:
         </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Kebutuhan Biaya</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $no = 1;
        $biaya_bongkar = array();
        $total_biaya = 0;
        foreach ($result as $row) {
            $biaya_bongkar[] = $row['harga_total'];
            $total_biaya = array_sum($biaya_bongkar);
            $total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $total . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='2' style='text-align: center;'><b>Total</b></td>";
        $isitampil .= "<td> <b>" . number_format($total_biaya, 0, ",", ".") . "</b></td>";
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
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getPost('idpkm');
        $prog = $this->request->getPost('program');
        $tanggal = $this->request->getPost('tanggal');
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $program = $this->programModel->asArray()->where('id', $prog)->first();
        $isitampil = "<h4><b>A. LATAR BELAKANG</b></h4>";
        $isitampil .= "<h5>1. Dasar Hukum</h5>";
        $isitampil .= "<p>a. Peraturan Menteri Kesehatan No. 44 tahun 2016 Tentang Manajemen Puskesmas</p>";
        $isitampil .= "<p>b. Peraturan Menteri Kesehatan No. 43 tahun 2019 Tentang Pusat Kesehatan Masyarakat</p>";
        $isitampil .= "<p>c. Surat Sekretariat Jenderal Kementerian Kesehatan Nomor : PR.01.01/I/18370/2021 Perihal Penyampaian Rincian Kegiatan DAK Non Fisik Bidang Kesehatan Tahun 2022</p>";
        $isitampil .= "</br>";
        $isitampil .= "</br>";
        $isitampil .= "<h5>2. Gambaran Umum</h5>";
        $isitampil .= "<p>Kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " untuk program " . $program['nama_program'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table id='data_table3' class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Uraian</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk WHERE usul_belanja_rpk.tahun=" . $tahun . "  GROUP BY usul_belanja_rpk.id_sub) as b";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
                'rpk.tahun' => $tahun,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;
        foreach ($result as $row) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td> Adalah kegiatan " . $row['keterangan'] . " yang bertujuan untuk " . $row['tujuan'] . "</td>";
            $isitampil .= "</tr>";
        }
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "</br";
        $isitampil .= "<h4><b>B. PENERIMA MANFAAT</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " untuk program " . $program['nama_program'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th>Penerima Manfaat</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $no = 1;
        foreach ($result as $row) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $row['target'] . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "</br";
        $isitampil .= "<h4><b>C. STRATEGI PENCAPAIAN KELUARAN</b></h4>";
        $isitampil .= "<p>Penerima manfaat merupakan sasaran kegiatan yang akan dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " untuk program " . $program['nama_program'] . " adalah sebagai berikut : </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Output</th>";
        $isitampil .= "<th style='text-align: center;'>Metode Pelaksanaan</th>";
        $isitampil .= "<th style='text-align: center;'>Tahapan Pelaksanaan</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $join2 = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk WHERE usul_belanja_rpk.tahun=" . $tahun . "  GROUP BY usul_belanja_rpk.id_sub) as b";
        $result2 = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, ruk.indikator,ruk.mitra,ruk.waktu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join2, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->join('ruk', 'ruk.id=rpk.id_ruk')
            ->where([
                'rpk.id_pkm' => $pkm,
                'rpk.tahun' => $tahun,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;
        foreach ($result2 as $row2) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row2['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $row2['indikator'] . "</td>";
            $isitampil .= "<td>Swakelola</td>";
            $isitampil .= "<td> Dilaksanakan oleh :" . $row2['tgjawab'] . "</br> Mitra Kerja :" . $row2['mitra'] . "</br> Waktu :" . $row2['waktu'] . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "</br";
        $isitampil .= "<h4><b>D. KURUN WAKTU PENCAPAIAN</b></h4>";
        $isitampil .= "<p>Kegiatan ini dilaksanakan di Puskesmas " . $puskesmas['pkm'] . " dari Bulan Januari 2022 s.d. Bulan Desember 2022</p>";
        $isitampil .= "</br";
        $isitampil .= "<h4><b>E. KURUN WAKTU PENCAPAIAN</b></h4>";
        $isitampil .= "<p>Biaya yang diperlukan untuk pencapaian keluaran Bantuan Operasional Kesehatan Puskesmas dengan kebutuhan per rincian menu
        kegiatan sebagai berikut:
         </p>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Kebutuhan Biaya</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $no = 1;
        $biaya_bongkar = array();
        $total_biaya = 0;
        foreach ($result as $row) {
            $biaya_bongkar[] = $row['harga_total'];
            $total_biaya = array_sum($biaya_bongkar);
            $total = ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0';
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . $total . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='2' style='text-align: center;'><b>Total</b></td>";
        $isitampil .= "<td> <b>" . number_format($total_biaya, 0, ",", ".") . "</b></td>";
        $isitampil .= "</tr>";
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";

        $isitampil .= "<p>Rincian Anggaran Biaya (RAB) terlampir</p>";
        $data = [
            'result'        => $isitampil,
            'puskesmas'     => $puskesmas['pkm'],
            'program'       => $program['nama_program'],
            'tahun'         => $tahun,
            'tanggal'         => $tanggal,
        ];


        echo view('cetak_kak', $data);
    }
}