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
use App\Models\DaftarrkaModel;
use App\Models\Mcountdown;

class Cetakrkap extends BaseController
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
        $this->usulanrpkpModel = new UsulanRpkpModel();
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
            'controller'        => 'cetakrkap',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];
        // dd($result);
        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('cetak/cetakrkap', $data);
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
            $isidata = "<option>--Pilih Nama Sub Kegiatan--</option>";
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
        $datarka = $this->daftarrkaModel->asArray()->select('id, kode, judul')->where('id', $rka)->first();
        $result = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
            ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->where([
                'usul_belanja_rpkp.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpkp.tahun' => $tahun,
            ])->groupBy('daftar_rka.kode')
            ->first();
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $isitampil = "<table width='100%' cellpadding='5' cellspacing='1' class='text_tengah text_15 table-bordered' class='td1'>";
        $isitampil .= "<tr>";
        $isitampil .= "<td align='center'>RINCIAN BELANJA PERUBAHAN SUB KEGIATAN<br/>SATUAN KERJA PERANGKAT DAERAH</td>";
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
        $isitampil .= '<td width="130">Program</td><td width="10">:</td><td>1.02.05&nbsp;PROGRAM PEMENUHAN UPAYA KESEHATAN PERORANGAN DAN UPAYA KESEHATAN MASYARAKAT</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Kegiatan</td><td width="10">:</td><td>1.02.05.2.01&nbsp;Penyediaan Fasilitas Pelayanan Kesehatan untuk UKM dan UKP Kewenangan Daerah Kabupaten/Kota</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Sub Kegiatan</td><td width="10">:</td><td>1.02.05.2.01.01&nbsp;' . $datarka['judul'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Sumber Pendanaan</td><td width="10">:</td>';
        $isitampil .= '<td>';
        $isitampil .= '<table width="100%">';
        $isitampil .= '<tr valign="top">';
        $isitampil .= '<td>PENDAPATAN ASLI DAERAH (PAD)</td>';
        $isitampil .= '</tr>';
        $isitampil .= '</table>';
        $isitampil .= '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Lokasi Kegiatan</td><td width="10">:</td>';
        $isitampil .= '<td>';
        $isitampil .= '<table width="100%">';
        $isitampil .= '<tr valign="top">';
        $isitampil .= '<td>Kab. Garut, Wilayah Kerja Puskesmas ' . $puskesmas['pkm'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '</table>';
        $isitampil .= '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Waktu Pelaksanaan</td><td width="10">:</td><td> Januari s.d. Desember </td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Jumlah 2021</td><td width="10">:</td><td>Rp. 0</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr valign="top">';
        $isitampil .= '<td width="130">Jumlah 2022</td><td width="10">:</td><td>Rp. ' . number_format($result['hargatotal'], 0, ",", ".") . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130">Jumlah 2023</td><td width="10">:</td><td>Rp. 0</td>';
        $isitampil .= '</tr>';
        $isitampil .= '</table>';
        $isitampil .= '<table width="100%" class="text_tengah text_15 table-bordered">';
        $isitampil .= '<tr>';
        $isitampil .= '<td class="atas kanan bawah kiri text_15 text_tengah" colspan="2">';
        $isitampil .= '<table width="100%" cellpadding="5" cellspacing="0" >';
        $isitampil .= '<tr><td>Indikator &amp; Tolok Ukur Kinerja Kegiatan</td></tr>';
        $isitampil .= '</table>';
        $isitampil .= '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td class="kiri kanan atas bawah" colspan="2">';

        $isitampil .= '<table width="100%" class="text_tengah text_15 table-bordered">';
        $isitampil .= '<tr>';
        $isitampil .= '<tr><td class="text_tengah">Indikator &amp; Tolok Ukur Kinerja Kegiatan</td></tr>';
        $isitampil .= '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td class="kiri kanan atas bawah" colspan="2">';
        $isitampil .= '<table width="100%" cellpadding="5" cellspacing="2">';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="text_tengah kiri atas kanan bawah">Indikator</td>';
        $isitampil .= '<td class="text_tengah kiri atas kanan bawah">Tolok Ukur Kinerja</td>';
        $isitampil .= '<td width="123" class="text_tengah kiri atas kanan bawah">Target Kinerja</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Capaian Kegiatan</td>';
        $isitampil .= '<td>Persentase Cakupan PHBS di Lima Tatanan</td>';
        $isitampil .= '<td>65&nbsp;%</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Masukan</td>';

        $isitampil .= '<td width="495">Dana yang dibutuhkan</td>';
        $isitampil .= '<td width="495">Rp. ' . number_format($result['hargatotal'], 0, ",", ".") . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Keluaran</td>';
        $isitampil .= '<td>Persentase puskesmas dengan minimal 80 % posyandu aktif</td>';
        $isitampil .= '<td>100&nbsp;%</td>';
        $isitampil .= '</tr>';

        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Hasil</td>';
        $isitampil .= '<td>Persentase cakupan PHBS di lima tatanan</td>';

        $isitampil .= '<td>65&nbsp;%</td>';

        $isitampil .= '</table>';

        $isitampil .= '<tr>';
        $isitampil .= '<td width="150" colspan="2">Kelompok Sasaran Kegiatan : Lintas Sektor dan Pemangku Kebijakan Tingkat Kabupaten</td>
    </tr>';

        $isitampil .= "</table>";

        $isitampil .= "<table class='table table-bordered table-striped' style='border-width: medium'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' style=text-align: center'>Kode Rekening</th>";
        $isitampil .= "<th rowspan='2'>Uraian</th>";
        $isitampil .= "<th colspan='4' align='center'>Rincian Perhitungan</th>";
        $isitampil .= "<th rowspan='2'>Jumlah</th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>Koefisien</th>";
        $isitampil .= "<th>Satuan</th>";
        $isitampil .= "<th>Harga</th>";
        $isitampil .= "<th>PPN</th>";
        $isitampil .= "</tr>";

        $isitampil .= "</thead>";
        $isitampil .= "<tbody class='bord'>";

        $result = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
            ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->where([
                'usul_belanja_rpkp.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpkp.tahun' => $tahun,
            ])->groupBy('daftar_rka.kode')
            ->findAll();
        $no = 1;
        foreach ($result as $row) {

            $isitampil .= "<tr>";
            $isitampil .= "<td><b>5</b></td>";
            $isitampil .= "<td colspan='5'><b>BELANJA DAERAH</b></td>";
            $isitampil .= "<td align ='right' ><b>" . number_format($row['hargatotal'], 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $isitampil .= "<tr>";
            $isitampil .= "<td><b>5.1.</b></td>";
            $isitampil .= "<td colspan='5'><b>BELANJA OPERASI</b></td>";
            $isitampil .= "<td align ='right' ><b>" . number_format($row['hargatotal'], 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $isitampil .= "<tr>";
            $isitampil .= "<td><b>5.1.02</b></td>";
            $isitampil .= "<td colspan='5'><b>Belanja Barang dan Jasa</b></td>";
            $isitampil .= "<td align ='right' ><b>" . number_format($row['hargatotal'], 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";


            $result1 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
                ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                ->where([
                    'usul_belanja_rpkp.id_pkm' => $pkm,
                    'sub_komponen.id_rka' => $rka,
                    'usul_belanja_rpkp.tahun' => $tahun,
                ])->groupBy('kodrek.kode')
                ->orderBy('kodrek.kode')->findAll();
            $no = 1;
            foreach ($result1 as $row1) {

                $isitampil .= "<tr>";

                $isitampil .= "<td><b>" . $row1['kode'] . "</b></td>";
                $isitampil .= "<td colspan='5'><b>" . $row1['nama_rekening'] . "</b></td>";
                $isitampil .= "<td align ='right' ><b>" . number_format($row1['hargatotal'], 0, ",", ".") . "</b></td>";
                $isitampil .= "</tr>";

                $result2 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom, sub_komponen.id AS idsub, kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
                    ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                    ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                    ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                    ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                    ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                    ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                    ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                    ->where([
                        'usul_belanja_rpkp.id_pkm' => $pkm,
                        'sub_komponen.id_rka' => $rka,
                        'kodrek.kode' => $row1['kode'],
                        'usul_belanja_rpkp.tahun' => $tahun,

                    ])->groupBy('sub_komponen.id_kom')
                    ->orderBy('sub_komponen.nama_subkomponen')->findAll();
                $no = 1;
                foreach ($result2 as $row2) {

                    $isitampil .= "<tr>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td colspan='5'><b>[#] " . $row2['nama_komponen'] . "</b></td>";
                    $isitampil .= "<td align ='right' ><b>" . number_format($row2['hargatotal'], 0, ",", ".") . "</b></td>";
                    $isitampil .= "</tr>";

                    $result3 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom, sub_komponen.id AS idsub, kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
                        ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                        ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                        ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                        ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                        ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                        ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                        ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                        ->where([
                            'usul_belanja_rpkp.id_pkm' => $pkm,
                            'sub_komponen.id_rka' => $rka,
                            'kodrek.kode' => $row1['kode'],
                            'sub_komponen.id_kom' => $row2['id_kom'],
                            'usul_belanja_rpkp.tahun' => $tahun,

                        ])->groupBy('sub_komponen.id')
                        ->orderBy('sub_komponen.nama_subkomponen')->findAll();
                    $no = 1;
                    foreach ($result3 as $row3) {

                        $isitampil .= "<tr>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "<td colspan='5'>[-] " . $row3['nama_subkomponen'] . "</td>";
                        $isitampil .= "<td align ='right' >" . number_format($row3['hargatotal'], 0, ",", ".") . "</td>";
                        $isitampil .= "</tr>";

                        $result4 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id AS idsub, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal, sum(usul_belanja_rpkp.vol_total) as voltotal')
                            ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                            ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id_rka' => $rka,
                                'kodrek.kode' => $row1['kode'],
                                'sub_komponen.id_kom' => $row2['id_kom'],
                                'sub_komponen.id' => $row3['idsub'],
                                'usul_belanja_rpkp.tahun' => $tahun,
                            ])->groupBy('usul_belanja_rpkp.id_belanja')
                            ->orderBy('kodrek.kode')->findAll();
                        $no = 1;
                        foreach ($result4 as $row4) {

                            $isitampil .= "<tr>";

                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $row4['nama_belanja'] . "</td>";
                            $isitampil .= "<td>" . $row4['voltotal'] . "</td>";
                            $isitampil .= "<td>" . $row4['satuan'] . "</td>";
                            $isitampil .= "<td align ='right'>" . number_format($row4['harga'], 0, ",", ".") . "</td>";
                            $isitampil .= "<td>0</td>";
                            $isitampil .= "<td align ='right' >" . number_format($row4['hargatotal'], 0, ",", ".") . "</td>";
                            $isitampil .= "</tr>";
                        }
                    }
                }
            }
        };



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
                                                    <tr><td colspan="3" class="text_tengah text_15">Kepala&nbsp;Dinas Kesehatan</td></tr>
                <tr><td height="80">&nbsp;</td></tr>
                <tr><td class="text_tengah">dr. H. Maskut Farid</td></tr>
                <tr><td class="text_tengah">NIP. 196706251998031004</td></tr>
                                                </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr><td width="160" class="kiri atas bawah">Keterangan</td><td width="10" class="atas bawah">:</td><td class="atas bawah kanan">&nbsp;</td></tr>
                <tr><td width="160" class="kiri bawah">Tanggal Pembahasan</td><td width="10" class="bawah">:</td><td class="bawah kanan">&nbsp;</td></tr>
                <tr><td width="160" class="kiri bawah">Catatan Hasil Pembahasan</td><td width="10" class="bawah">:</td><td class="bawah kanan">&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">1.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">2.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">3.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">4.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">5.&nbsp;</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr><td colspan="5" class="kiri kanan atas bawah text_tengah">Tim Anggaran Pemerintah Daerah</td></tr>
                <tr class="text_tengah">
                    <td width="10" class="kiri kanan bawah">No.</td>
                    <td class="kanan bawah">Nama</td>
                    <td width="120" class="bawah kanan">NIP</td>
                    <td width="150" class="bawah kanan">Jabatan</td>
                    <td width="100" class="bawah kanan">Tanda Tangan</td>
                </tr>
                                                                        </table>
        </td>
    </tr> </table>';


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
        $datarka = $this->daftarrkaModel->asArray()->select('id, kode, judul')->where('id', $rka)->first();

        $result = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
            ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->where([
                'usul_belanja_rpkp.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpkp.tahun' => $tahun,
            ])->groupBy('daftar_rka.kode')
            ->first();

        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $isitampil = "<table width='100%' cellpadding='5' cellspacing='1' class='text_tengah text_15 table-bordered'>";
        $isitampil .= "<tr>";
        $isitampil .= "<td class='text_tengah text_blok'>RINCIAN BELANJA PERUBAHAN SUB KEGIATAN<br/>SATUAN KERJA PERANGKAT DAERAH</td>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<td  class='text_tengah text_blok'>Pemerintah Kabupaten Garut  Tahun Anggaran 2022</td>";
        $isitampil .= "</tr>";
        $isitampil .= "</table>";
        $isitampil .= '<table width="100%" cellpadding="2" cellspacing="1" >';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Urusan</td><td width="10" class="bord">:</td><td class="bord">1.02&nbsp;URUSAN PEMERINTAHAN WAJIB YANG BERKAITAN DENGAN PELAYANAN DASAR</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Unit Organisasi</td><td width="10" class="bord">:</td><td class="bord">1.02.0.00.0.00.01.0000&nbsp;Dinas Kesehatan</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Sub Unit Organisasi</td><td width="10" class="bord">:</td><td class="bord">1.02.0.00.0.00.01.0000&nbsp;Puskesmas ' . $puskesmas['pkm'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Program</td><td width="10" class="bord">:</td><td class="bord">1.02.05&nbsp;PROGRAM PEMENUHAN UPAYA KESEHATAN PERORANGAN DAN UPAYA KESEHATAN MASYARAKAT</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Kegiatan</td><td width="10" class="bord">:</td><td class="bord">1.02.05.2.01&nbsp;Penyediaan Fasilitas Pelayanan Kesehatan untuk UKM dan UKP Kewenangan Daerah Kabupaten/Kota</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Sub Kegiatan</td><td width="10" class="bord">:</td><td class="bord">1.02.05.2.01.01&nbsp;' . $datarka['judul'] . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Sumber Pendanaan</td><td width="10" class="bord">:</td>';
        $isitampil .= '<td class="bord">PENDAPATAN ASLI DAERAH (PAD)</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Lokasi Kegiatan</td><td width="10" class="bord">:</td>';
        $isitampil .= '<td class="bord">Kab. Garut, Wilayah Kerja Puskesmas ' . $puskesmas['pkm'] . '</td>';
        $isitampil .= '</tr>';

        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Waktu Pelaksanaan</td><td width="10" class="bord">:</td><td class="bord"> Januari s.d. Desember </td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Jumlah 2021</td><td width="10" class="bord">:</td><td class="bord">Rp. 0</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr valign="top">';
        $isitampil .= '<td width="130" class="bord">Jumlah 2022</td><td width="10" class="bord">:</td><td class="bord">Rp. ' . number_format($result['hargatotal'], 0, ",", ".") . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="bord">Jumlah 2023</td><td width="10" class="bord">:</td><td class="bord">Rp. 0</td>';
        $isitampil .= '</tr>';
        $isitampil .= '</table>';

        $isitampil .= '<table width="100%" class="text_tengah text_15 table-bordered">';
        $isitampil .= '<tr>';
        $isitampil .= '<tr><td class="text_tengah">Indikator &amp; Tolok Ukur Kinerja Kegiatan</td></tr>';
        $isitampil .= '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td class="kiri kanan atas bawah" colspan="2">';
        $isitampil .= '<table width="100%" cellpadding="5" cellspacing="2">';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="text_tengah kiri atas kanan bawah">Indikator</td>';
        $isitampil .= '<td class="text_tengah kiri atas kanan bawah">Tolok Ukur Kinerja</td>';
        $isitampil .= '<td width="123" class="text_tengah kiri atas kanan bawah">Target Kinerja</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Capaian Kegiatan</td>';
        $isitampil .= '<td>Persentase Cakupan PHBS di Lima Tatanan</td>';
        $isitampil .= '<td>65&nbsp;%</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Masukan</td>';

        $isitampil .= '<td width="495">Dana yang dibutuhkan</td>';
        $isitampil .= '<td width="495">Rp. ' . number_format($result['hargatotal'], 0, ",", ".") . '</td>';
        $isitampil .= '</tr>';
        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Keluaran</td>';
        $isitampil .= '<td>Persentase puskesmas dengan minimal 80 % posyandu aktif</td>';
        $isitampil .= '<td>100&nbsp;%</td>';
        $isitampil .= '</tr>';

        $isitampil .= '<tr>';
        $isitampil .= '<td width="130" class="kiri kanan atas bawah">Hasil</td>';
        $isitampil .= '<td>Persentase cakupan PHBS di lima tatanan</td>';

        $isitampil .= '<td>65&nbsp;%</td>';

        $isitampil .= '</table>';

        $isitampil .= '<tr>';
        $isitampil .= '<td width="150" colspan="2">Kelompok Sasaran Kegiatan : Lintas Sektor dan Pemangku Kebijakan Tingkat Kabupaten</td>
    </tr>';

        $isitampil .= "</table>";

        $isitampil .= "<table class='table table-bordered table-striped' style='border-width: medium'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th rowspan='2' style=text-align: center'>Kode Rekening</th>";
        $isitampil .= "<th rowspan='2'>Uraian</th>";
        $isitampil .= "<th colspan='4' align='center'>Rincian Perhitungan</th>";
        $isitampil .= "<th rowspan='2'>Jumlah</th>";
        $isitampil .= "</tr>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>Koefisien</th>";
        $isitampil .= "<th>Satuan</th>";
        $isitampil .= "<th>Harga</th>";
        $isitampil .= "<th>PPN</th>";
        $isitampil .= "</tr>";

        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";

        $result = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
            ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
            ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
            ->where([
                'usul_belanja_rpkp.id_pkm' => $pkm,
                'sub_komponen.id_rka' => $rka,
                'usul_belanja_rpkp.tahun' => $tahun,
            ])->groupBy('daftar_rka.kode')
            ->findAll();
        $no = 1;
        foreach ($result as $row) {

            $isitampil .= "<tr>";
            $isitampil .= "<td><b>5</b></td>";
            $isitampil .= "<td colspan='5'><b>BELANJA DAERAH</b></td>";
            $isitampil .= "<td class='text_kanan'><b>" . number_format($row['hargatotal'], 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $isitampil .= "<tr>";
            $isitampil .= "<td><b>5.1.</b></td>";
            $isitampil .= "<td colspan='5'><b>BELANJA OPERASI</b></td>";
            $isitampil .= "<td class='text_kanan'><b>" . number_format($row['hargatotal'], 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";

            $isitampil .= "<tr>";
            $isitampil .= "<td><b>5.1.02</b></td>";
            $isitampil .= "<td colspan='5'><b>Belanja Barang dan Jasa</b></td>";
            $isitampil .= "<td class='text_kanan'><b>" . number_format($row['hargatotal'], 0, ",", ".") . "</b></td>";
            $isitampil .= "</tr>";


            $result1 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
                ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                ->where([
                    'usul_belanja_rpkp.id_pkm' => $pkm,
                    'sub_komponen.id_rka' => $rka,
                    'usul_belanja_rpkp.tahun' => $tahun,
                ])->groupBy('kodrek.kode')
                ->orderBy('kodrek.kode')->findAll();
            $no = 1;
            foreach ($result1 as $row1) {

                $isitampil .= "<tr>";

                $isitampil .= "<td><b>" . $row1['kode'] . "</b></td>";
                $isitampil .= "<td colspan='5'><b>" . $row1['nama_rekening'] . "</b></td>";
                $isitampil .= "<td class='text_kanan'><b>" . number_format($row1['hargatotal'], 0, ",", ".") . "</b></td>";
                $isitampil .= "</tr>";

                $result2 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom, sub_komponen.id AS idsub, kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
                    ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                    ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                    ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                    ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                    ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                    ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                    ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                    ->where([
                        'usul_belanja_rpkp.id_pkm' => $pkm,
                        'sub_komponen.id_rka' => $rka,
                        'kodrek.kode' => $row1['kode'],
                        'usul_belanja_rpkp.tahun' => $tahun,

                    ])->groupBy('sub_komponen.id_kom')
                    ->orderBy('sub_komponen.nama_subkomponen')->findAll();
                $no = 1;
                foreach ($result2 as $row2) {

                    $isitampil .= "<tr>";
                    $isitampil .= "<td></td>";
                    $isitampil .= "<td colspan='5'><b>[#] " . $row2['nama_komponen'] . "</b></td>";
                    $isitampil .= "<td class='text_kanan'><b>" . number_format($row2['hargatotal'], 0, ",", ".") . "</b></td>";
                    $isitampil .= "</tr>";

                    $result3 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom, sub_komponen.id AS idsub, kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal')
                        ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                        ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                        ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                        ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                        ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                        ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                        ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                        ->where([
                            'usul_belanja_rpkp.id_pkm' => $pkm,
                            'sub_komponen.id_rka' => $rka,
                            'kodrek.kode' => $row1['kode'],
                            'sub_komponen.id_kom' => $row2['id_kom'],
                            'usul_belanja_rpkp.tahun' => $tahun,

                        ])->groupBy('sub_komponen.id')
                        ->orderBy('sub_komponen.nama_subkomponen')->findAll();
                    $no = 1;
                    foreach ($result3 as $row3) {

                        $isitampil .= "<tr>";
                        $isitampil .= "<td></td>";
                        $isitampil .= "<td colspan='5'>[-] " . $row3['nama_subkomponen'] . "</td>";
                        $isitampil .= "<td class='text_kanan'>" . number_format($row3['hargatotal'], 0, ",", ".") . "</td>";
                        $isitampil .= "</tr>";

                        $result4 = $this->usulanrpkpModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id AS idsub, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpkp.*, sum(usul_belanja_rpkp.harga_total) as hargatotal, sum(usul_belanja_rpkp.vol_total) as voltotal')
                            ->join('kode_belanja', 'usul_belanja_rpkp.id_belanja = kode_belanja.id', 'left')
                            ->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
                            ->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'left')
                            ->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
                            ->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
                            ->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
                            ->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
                            ->where([
                                'usul_belanja_rpkp.id_pkm' => $pkm,
                                'sub_komponen.id_rka' => $rka,
                                'kodrek.kode' => $row1['kode'],
                                'sub_komponen.id_kom' => $row2['id_kom'],
                                'sub_komponen.id' => $row3['idsub'],
                                'usul_belanja_rpkp.tahun' => $tahun,
                            ])->groupBy('usul_belanja_rpkp.id_belanja')
                            ->orderBy('usul_belanja_rpkp.id_belanja')->findAll();
                        $no = 1;
                        foreach ($result4 as $row4) {

                            $isitampil .= "<tr>";

                            $isitampil .= "<td></td>";
                            $isitampil .= "<td>" . $row4['nama_belanja'] . "</td>";
                            $isitampil .= "<td>" . $row4['voltotal'] . "</td>";
                            $isitampil .= "<td>" . $row4['satuan'] . "</td>";
                            $isitampil .= "<td class='text_kanan'>" . number_format($row4['harga'], 0, ",", ".") . "</td>";
                            $isitampil .= "<td>0</td>";
                            $isitampil .= "<td class='text_kanan'>" . number_format($row4['hargatotal'], 0, ",", ".") . "</td>";
                            $isitampil .= "</tr>";
                        }
                    }
                }
            }
        }

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= '&nbsp;';

        $isitampil .= "<table width='100%'>";
        $isitampil .= '<tr>';
        $isitampil .= '<td class="kiri kanan atas bawah" width="350" valign="top">';
        $isitampil .= '&nbsp;';
        $isitampil .= '</td>';
        $isitampil .= '<td class="kiri kanan atas bawah" width="250" valign="top">
            <table width="100%" cellpadding="2" cellspacing="0" >
                <tr><td class="text_tengah bord">Kabupaten Garut, Tanggal </td></tr>
                <tr><td colspan="3" class="text_tengah text_15 bord">Kepala&nbsp;Dinas Kesehatan</td></tr>
                <tr><td class="bord" height="80">&nbsp;</td></tr>
                <tr><td class="text_tengah bord">dr. H. Maskut Farid</td></tr>
                <tr><td class="text_tengah bord">NIP. 196706251998031004</td></tr>
                                                </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr><td width="160" class="kiri atas bawah">Keterangan</td><td width="10" class="atas bawah">:</td><td class="atas bawah kanan">&nbsp;</td></tr>
                <tr><td width="160" class="kiri bawah">Tanggal Pembahasan</td><td width="10" class="bawah">:</td><td class="bawah kanan">&nbsp;</td></tr>
                <tr><td width="160" class="kiri bawah">Catatan Hasil Pembahasan</td><td width="10" class="bawah">:</td><td class="bawah kanan">&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">1.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">2.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">3.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">4.&nbsp;</td></tr>
                <tr><td class="kiri bawah kanan" colspan="3">5.&nbsp;</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr><td colspan="5" class="kiri kanan atas bawah text_tengah">Tim Anggaran Pemerintah Daerah</td></tr>
                <tr class="text_tengah">
                    <td width="10" class="kiri kanan bawah">No.</td>
                    <td class="kanan bawah">Nama</td>
                    <td width="120" class="bawah kanan">NIP</td>
                    <td width="150" class="bawah kanan">Jabatan</td>
                    <td width="100" class="bawah kanan">Tanda Tangan</td>
                </tr>
                                                                        </table>
        </td>
    </tr> </table>';

        $data = [
            'rka' => $datarka,
            'result' => $isitampil,
            'puskesmas' => $puskesmas['pkm']
        ];


        echo view('cetak/cetak_rkap', $data);
    }
}