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
use App\Models\Mcountdown;

class Realisasidpa extends BaseController
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
        $this->mcountdown = new Mcountdown();
        $this->session = session();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where('link', 'ruk')->first();
        $data = [
            'controller'        => 'realisasidpa',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('realisasidpa', $data);
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
        $pkm = $this->request->getVar('pkm');
        $rka = $this->request->getVar('prog');
        $datarka = $this->daftarrkaModel->asArray()->select('daftar_rka.id, daftar_rka.kode, daftar_rka.judul, prog.kode_prog,prog.nama_prog, kegiatan.kode_keg, kegiatan.nama_keg')->join('prog', 'daftar_rka.id_prog=prog.id')->join('kegiatan', 'daftar_rka.id_keg=kegiatan.id')->where('daftar_rka.id', $rka)->first();
        $result = $this->usulanrpkModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpk.*, sum(usul_belanja_rpk.harga_total) as hargatotal')
            ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->where([
                'usul_belanja_rpk.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpk.tahun' => $tahun,
            ])->groupBy('daftar_rka.kode')
            ->first();
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $isitampil = "<table width='100%' cellpadding='5' cellspacing='1' class='text_tengah text_15 table-bordered' class='td1'>";
        $isitampil .= "<tr>";
        $isitampil .= "<td align='center'><b>REALISASI ANGGARAN <br/>SATUAN KERJA PERANGKAT DAERAH</b></td>";
        $isitampil .= "<td align='center' rowspan='2'></td>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<td  align='center'>Pemerintah Kabupaten Garut  Tahun Anggaran 2022</td>";
        $isitampil .= "</tr>";
        $isitampil .= "</table>";
        $isitampil .= '<table width="100%" cellpadding="2" cellspacing="1">';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Urusan</td><td width="10" class="bord">:</td><td>1.02&nbsp;URUSAN PEMERINTAHAN WAJIB YANG BERKAITAN DENGAN PELAYANAN DASAR</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Unit Organisasi</td><td width="10">:</td><td>1.02.0.00.0.00.01.0000&nbsp;Dinas Kesehatan</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Sub Unit Organisasi</td><td width="10">:</td><td>1.02.0.00.0.00.01.0000&nbsp;Puskesmas ' . $puskesmas['pkm'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Program</td><td width="10">:</td><td>' . $datarka['kode_prog'] . '&nbsp;' . $datarka['nama_prog'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Kegiatan</td><td width="10">:</td><td>' . $datarka['kode_keg'] . '&nbsp;' . $datarka['nama_keg'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Sub Kegiatan</td><td width="10">:</td><td>' . $datarka['kode'] . '&nbsp;' . $datarka['judul'] . '</td>';
        $isitampil .= '</tr>';

        $isitampil .= '<tr valign="top">';
        // $harga_total = ($result['hargatotal'] != null) ? number_format($result['hargatotal'], 0, ",", ".") : 0;
        // $isitampil .= '<td width="130">Nilai Anggaran</td><td width="10">:</td><td>Rp. ' . $harga_total . '</td>';
        $isitampil .= '</tr>';


        $isitampil .= "</table>";

        $isitampil .= "<table class='table table-bordered table-striped' style='border-width: medium'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' class='text-center'>No.</th>";
        $isitampil .= "<th rowspan='2' class='text-center'>Rekening</th>";
        $isitampil .= "<th rowspan='2' class='text-center'>Jumlah Anggaran</th>";
        $isitampil .= "<th colspan='12' class='text-center'>Realisasi Bulanan</th>";
        $isitampil .= "<th rowspan='2' class='text-center'><b>Jumlah Realisasi</b></th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>Januari</th>";
        $isitampil .= "<th>Februari</th>";
        $isitampil .= "<th>Maret</th>";
        $isitampil .= "<th>April</th>";
        $isitampil .= "<th>Mei</th>";
        $isitampil .= "<th>Juni</th>";
        $isitampil .= "<th>Juli</th>";
        $isitampil .= "<th>Agustus</th>";
        $isitampil .= "<th>September</th>";
        $isitampil .= "<th>Oktober</th>";
        $isitampil .= "<th>November</th>";
        $isitampil .= "<th>Desember</th>";
        $isitampil .= "</tr>";

        $isitampil .= "</thead>";
        $isitampil .= "<tbody class='bord'>";

        $result1 = $this->usulanrpkModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpk.*, sum(usul_belanja_rpk.harga_total) as hargatotal,
        SUM(CASE WHEN realisasi.bulan =1 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b1,
        SUM(CASE WHEN realisasi.bulan =2 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b2,
        SUM(CASE WHEN realisasi.bulan =3 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b3,
        SUM(CASE WHEN realisasi.bulan =4 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b4,
        SUM(CASE WHEN realisasi.bulan =5 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b5,
        SUM(CASE WHEN realisasi.bulan =6 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b6,
        SUM(CASE WHEN realisasi.bulan =7 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b7,
        SUM(CASE WHEN realisasi.bulan =8 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b8,
        SUM(CASE WHEN realisasi.bulan =9 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b9,
        SUM(CASE WHEN realisasi.bulan =10 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b10,
        SUM(CASE WHEN realisasi.bulan =11 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b11,
        SUM(CASE WHEN realisasi.bulan =12 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b12,
        SUM(CASE WHEN realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS rtahun')
            ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'left')
            ->where([
                'usul_belanja_rpk.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpk.tahun' => $tahun,
            ])->groupBy('kodrek.kode')
            ->orderBy('kodrek.kode')->findAll();
        $no = 1;
        $jmlanggaran = 0;
        $jmlb1 = 0;
        $jmlb2 = 0;
        $jmlb3 = 0;
        $jmlb4 = 0;
        $jmlb5 = 0;
        $jmlb6 = 0;
        $jmlb7 = 0;
        $jmlb8 = 0;
        $jmlb9 = 0;
        $jmlb10 = 0;
        $jmlb11 = 0;
        $jmlb12 = 0;
        $jmltahun = 0;

        foreach ($result1 as $row1) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row1['kode'] . " " . $row1['nama_rekening'] . "</td>";
            $isitampil .= "<td align ='right' ><b>" . number_format($row1['hargatotal'], 0, ",", ".") . "</b></td>";
            $b1 = ($row1['b1'] != null) ? number_format($row1['b1'], 0, ",", ".") : 0;
            $b2 = ($row1['b2'] != null) ? number_format($row1['b2'], 0, ",", ".") : 0;
            $b3 = ($row1['b3'] != null) ? number_format($row1['b3'], 0, ",", ".") : 0;
            $b4 = ($row1['b4'] != null) ? number_format($row1['b4'], 0, ",", ".") : 0;
            $b5 = ($row1['b5'] != null) ? number_format($row1['b5'], 0, ",", ".") : 0;
            $b6 = ($row1['b6'] != null) ? number_format($row1['b6'], 0, ",", ".") : 0;
            $b7 = ($row1['b7'] != null) ? number_format($row1['b7'], 0, ",", ".") : 0;
            $b8 = ($row1['b8'] != null) ? number_format($row1['b8'], 0, ",", ".") : 0;
            $b9 = ($row1['b9'] != null) ? number_format($row1['b9'], 0, ",", ".") : 0;
            $b10 = ($row1['b10'] != null) ? number_format($row1['b10'], 0, ",", ".") : 0;
            $b11 = ($row1['b11'] != null) ? number_format($row1['b11'], 0, ",", ".") : 0;
            $b12 = ($row1['b12'] != null) ? number_format($row1['b12'], 0, ",", ".") : 0;
            $rtahun = ($row1['rtahun'] != null) ? number_format($row1['rtahun'], 0, ",", ".") : 0;

            $isitampil .= "<td class='text-end'>" . $b1 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b2 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b3 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b4 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b5 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b6 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b7 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b8 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b9 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b10 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b11 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b12 . "</td>";
            $isitampil .= "<td class='text-end'>" . $rtahun . "</td>";
            $isitampil .= "</tr>";
            $jmlanggaran = $jmlanggaran + $row1['hargatotal'];
            $jmlb1 = $jmlb1 + $row1['b1'];
            $jmlb2 = $jmlb2 + $row1['b2'];
            $jmlb3 = $jmlb3 + $row1['b3'];
            $jmlb4 = $jmlb4 + $row1['b4'];
            $jmlb5 = $jmlb5 + $row1['b5'];
            $jmlb6 = $jmlb6 + $row1['b6'];
            $jmlb7 = $jmlb7 + $row1['b7'];
            $jmlb8 = $jmlb8 + $row1['b8'];
            $jmlb9 = $jmlb9 + $row1['b9'];
            $jmlb10 = $jmlb10 + $row1['b10'];
            $jmlb11 = $jmlb11 + $row1['b11'];
            $jmlb12 = $jmlb12 + $row1['b12'];
            $jmltahun = $jmltahun + $row1['rtahun'];
        };

        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='2'>Jumlah</td>";

        $isitampil .= "<td class='text-end'><b>" . number_format($jmlanggaran, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb1, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb2, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb3, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb4, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb5, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb6, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb7, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb8, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb9, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb10, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb11, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb12, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmltahun, 0, ",", ".") . "</b></td>";

        $isitampil .= "</tr>";

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "<table width='100%'>";
        $isitampil .= '<tr>';
        $isitampil .= '<td class="kiri kanan atas bawah" width="350" valign="top">';
        $isitampil .= '&nbsp;';
        $isitampil .= '</td>';
        $isitampil .= '<td class="kiri kanan atas bawah" width="250" valign="top">
            <table width="100%" cellpadding="2" cellspacing="0" style="border: 0px">
                <tr><td class="text_tengah">Kabupaten Garut , Tanggal </td></tr>
                                                    <tr><td colspan="3" class="text_tengah text_15">Kepala&nbsp;Puskesmas</td></tr>
                <tr><td height="80">&nbsp;</td></tr>
                <tr><td class="text_tengah">' . $puskesmas['kapus'] . '</td></tr>
                <tr><td class="text_tengah">NIP. ' . $puskesmas['nip_kapus'] . '</td></tr>
                                                </table>
        </td>
    </tr>
    </table>';


        $msg = [
            'rka' => $datarka,
            'data' => $isitampil,
            'puskesmas' => $puskesmas['pkm']
        ];

        return $this->response->setJSON($msg);
    }


    public function cetak()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getVar('pkm');
        $rka = $this->request->getVar('prog');
        $datarka = $this->daftarrkaModel->asArray()->select('daftar_rka.id, daftar_rka.kode, daftar_rka.judul, prog.kode_prog,prog.nama_prog, kegiatan.kode_keg, kegiatan.nama_keg')->join('prog', 'daftar_rka.id_prog=prog.id')->join('kegiatan', 'daftar_rka.id_keg=kegiatan.id')->where('daftar_rka.id', $rka)->first();
        $result = $this->usulanrpkModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpk.*, sum(usul_belanja_rpk.harga_total) as hargatotal')
            ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->where([
                'usul_belanja_rpk.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpk.tahun' => $tahun,
            ])->groupBy('daftar_rka.kode')
            ->first();
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $isitampil = "<table width='100%' cellpadding='5' cellspacing='1' class='text_tengah text_15 table-bordered' class='td1'>";
        $isitampil .= "<tr>";
        $isitampil .= "<td align='center'><b>REALISASI ANGGARAN <br/>SATUAN KERJA PERANGKAT DAERAH</b></td>";
        $isitampil .= "<td align='center' rowspan='2'></td>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<td  align='center'>Pemerintah Kabupaten Garut  Tahun Anggaran 2022</td>";
        $isitampil .= "</tr>";
        $isitampil .= "</table>";
        $isitampil .= '<table width="100%" cellpadding="2" cellspacing="1">';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Urusan</td><td width="10" class="bord">:</td><td>1.02&nbsp;URUSAN PEMERINTAHAN WAJIB YANG BERKAITAN DENGAN PELAYANAN DASAR</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Unit Organisasi</td><td width="10">:</td><td>1.02.0.00.0.00.01.0000&nbsp;Dinas Kesehatan</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Sub Unit Organisasi</td><td width="10">:</td><td>1.02.0.00.0.00.01.0000&nbsp;Puskesmas ' . $puskesmas['pkm'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Program</td><td width="10">:</td><td>' . $datarka['kode_prog'] . '&nbsp;' . $datarka['nama_prog'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Kegiatan</td><td width="10">:</td><td>' . $datarka['kode_keg'] . '&nbsp;' . $datarka['nama_keg'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Sub Kegiatan</td><td width="10">:</td><td>' . $datarka['kode'] . '&nbsp;' . $datarka['judul'] . '</td>';
        $isitampil .= '</tr>';

        $isitampil .= '<tr valign="top">';
        // $harga_total = ($result['hargatotal'] != null) ? number_format($result['hargatotal'], 0, ",", ".") : 0;
        // $isitampil .= '<td width="130">Nilai Anggaran</td><td width="10">:</td><td>Rp. ' . $harga_total . '</td>';
        $isitampil .= '</tr>';


        $isitampil .= "</table>";

        $isitampil .= "<table class='table table-bordered table-striped' style='border-width: medium'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' class='text-center'>No.</th>";
        $isitampil .= "<th rowspan='2' class='text-center'>Rekening</th>";
        $isitampil .= "<th rowspan='2' class='text-center'>Jumlah Anggaran</th>";
        $isitampil .= "<th colspan='12' class='text-center'>Realisasi Bulanan</th>";
        $isitampil .= "<th rowspan='2' class='text-center'><b>Jumlah Realisasi</b></th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>Januari</th>";
        $isitampil .= "<th>Februari</th>";
        $isitampil .= "<th>Maret</th>";
        $isitampil .= "<th>April</th>";
        $isitampil .= "<th>Mei</th>";
        $isitampil .= "<th>Juni</th>";
        $isitampil .= "<th>Juli</th>";
        $isitampil .= "<th>Agustus</th>";
        $isitampil .= "<th>September</th>";
        $isitampil .= "<th>Oktober</th>";
        $isitampil .= "<th>November</th>";
        $isitampil .= "<th>Desember</th>";
        $isitampil .= "</tr>";

        $isitampil .= "</thead>";
        $isitampil .= "<tbody class='bord'>";

        $result1 = $this->usulanrpkModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpk.*, sum(usul_belanja_rpk.harga_total) as hargatotal,
        SUM(CASE WHEN realisasi.bulan =1 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b1,
        SUM(CASE WHEN realisasi.bulan =2 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b2,
        SUM(CASE WHEN realisasi.bulan =3 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b3,
        SUM(CASE WHEN realisasi.bulan =4 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b4,
        SUM(CASE WHEN realisasi.bulan =5 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b5,
        SUM(CASE WHEN realisasi.bulan =6 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b6,
        SUM(CASE WHEN realisasi.bulan =7 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b7,
        SUM(CASE WHEN realisasi.bulan =8 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b8,
        SUM(CASE WHEN realisasi.bulan =9 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b9,
        SUM(CASE WHEN realisasi.bulan =10 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b10,
        SUM(CASE WHEN realisasi.bulan =11 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b11,
        SUM(CASE WHEN realisasi.bulan =12 and realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS b12,
        SUM(CASE WHEN realisasi.tahun =' . $tahun . ' THEN realisasi.jumlah END) AS rtahun')
            ->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->join('realisasi', 'realisasi.id_rpk=usul_belanja_rpk.id', 'left')
            ->where([
                'usul_belanja_rpk.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpk.tahun' => $tahun,
            ])->groupBy('kodrek.kode')
            ->orderBy('kodrek.kode')->findAll();
        $no = 1;
        $jmlanggaran = 0;
        $jmlb1 = 0;
        $jmlb2 = 0;
        $jmlb3 = 0;
        $jmlb4 = 0;
        $jmlb5 = 0;
        $jmlb6 = 0;
        $jmlb7 = 0;
        $jmlb8 = 0;
        $jmlb9 = 0;
        $jmlb10 = 0;
        $jmlb11 = 0;
        $jmlb12 = 0;
        $jmltahun = 0;

        foreach ($result1 as $row1) {

            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row1['kode'] . " " . $row1['nama_rekening'] . "</td>";
            $isitampil .= "<td align ='right' ><b>" . number_format($row1['hargatotal'], 0, ",", ".") . "</b></td>";
            $b1 = ($row1['b1'] != null) ? number_format($row1['b1'], 0, ",", ".") : 0;
            $b2 = ($row1['b2'] != null) ? number_format($row1['b2'], 0, ",", ".") : 0;
            $b3 = ($row1['b3'] != null) ? number_format($row1['b3'], 0, ",", ".") : 0;
            $b4 = ($row1['b4'] != null) ? number_format($row1['b4'], 0, ",", ".") : 0;
            $b5 = ($row1['b5'] != null) ? number_format($row1['b5'], 0, ",", ".") : 0;
            $b6 = ($row1['b6'] != null) ? number_format($row1['b6'], 0, ",", ".") : 0;
            $b7 = ($row1['b7'] != null) ? number_format($row1['b7'], 0, ",", ".") : 0;
            $b8 = ($row1['b8'] != null) ? number_format($row1['b8'], 0, ",", ".") : 0;
            $b9 = ($row1['b9'] != null) ? number_format($row1['b9'], 0, ",", ".") : 0;
            $b10 = ($row1['b10'] != null) ? number_format($row1['b10'], 0, ",", ".") : 0;
            $b11 = ($row1['b11'] != null) ? number_format($row1['b11'], 0, ",", ".") : 0;
            $b12 = ($row1['b12'] != null) ? number_format($row1['b12'], 0, ",", ".") : 0;
            $rtahun = ($row1['rtahun'] != null) ? number_format($row1['rtahun'], 0, ",", ".") : 0;

            $isitampil .= "<td class='text-end'>" . $b1 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b2 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b3 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b4 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b5 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b6 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b7 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b8 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b9 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b10 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b11 . "</td>";
            $isitampil .= "<td class='text-end'>" . $b12 . "</td>";
            $isitampil .= "<td class='text-end'>" . $rtahun . "</td>";
            $isitampil .= "</tr>";
            $jmlanggaran = $jmlanggaran + $row1['hargatotal'];
            $jmlb1 = $jmlb1 + $row1['b1'];
            $jmlb2 = $jmlb2 + $row1['b2'];
            $jmlb3 = $jmlb3 + $row1['b3'];
            $jmlb4 = $jmlb4 + $row1['b4'];
            $jmlb5 = $jmlb5 + $row1['b5'];
            $jmlb6 = $jmlb6 + $row1['b6'];
            $jmlb7 = $jmlb7 + $row1['b7'];
            $jmlb8 = $jmlb8 + $row1['b8'];
            $jmlb9 = $jmlb9 + $row1['b9'];
            $jmlb10 = $jmlb10 + $row1['b10'];
            $jmlb11 = $jmlb11 + $row1['b11'];
            $jmlb12 = $jmlb12 + $row1['b12'];
            $jmltahun = $jmltahun + $row1['rtahun'];
        };

        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='2'>Jumlah</td>";

        $isitampil .= "<td class='text-end'><b>" . number_format($jmlanggaran, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb1, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb2, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb3, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb4, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb5, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb6, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb7, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb8, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb9, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb10, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb11, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmlb12, 0, ",", ".") . "</b></td>";
        $isitampil .= "<td class='text-end'><b>" . number_format($jmltahun, 0, ",", ".") . "</b></td>";

        $isitampil .= "</tr>";

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "<table width='100%'>";
        $isitampil .= '<tr>';
        $isitampil .= '<td class="kiri kanan atas bawah" width="350" valign="top">';
        $isitampil .= '&nbsp;';
        $isitampil .= '</td>';
        $isitampil .= '<td class="kiri kanan atas bawah" width="250" valign="top">
            <table width="100%" cellpadding="2" cellspacing="0" style="border: 0px">
                <tr><td class="text_tengah">Kabupaten Garut , Tanggal </td></tr>
                                                    <tr><td colspan="3" class="text_tengah text_15">Kepala&nbsp;Puskesmas</td></tr>
                <tr><td height="80">&nbsp;</td></tr>
                <tr><td class="text_tengah">' . $puskesmas['kapus'] . '</td></tr>
                <tr><td class="text_tengah">NIP. ' . $puskesmas['nip_kapus'] . '</td></tr>
                                                </table>
        </td>
    </tr>
    </table>';


        $data = [
            'rka' => $datarka,
            'result' => $isitampil,
            'puskesmas' => $puskesmas['pkm']
        ];

        echo view('cetak/cetak_realisasidpa', $data);
    }
}