<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RukModel;
use App\Models\DakungModel;
use App\Models\RpkModel;
use App\Models\ProgramModel;
use App\Models\PenyusunModel;
use App\Models\KodebelanjaModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\UsulanRpkModel;
use App\Models\Mcountdown;

class Cetakba extends BaseController
{

    protected $rukModel;
    protected $dakungModel;
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
        $this->dakungModel = new DakungModel();
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
            'controller'        => 'cetakba',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];
        // dd($result);
        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('cetakba', $data);
        }
    }

    public function getupt()
    {
        $upt = $this->request->getPost('upt');
        if ($upt) {
            $datarka = $this->uptModel->select('id, pkm')->where('id', $upt)->findAll();
            $isidata = "";
        } else {
            $datarka = $this->uptModel->select('id, pkm')->findAll();
            $isidata = "<option>--Pilih Nama UPT--</option>";
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
        $prog = $this->request->getVar('prog');
        $pptk = $this->request->getVar('pptk');
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $program = $this->programModel->asArray()->where('id', $prog)->first();

        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;

        $isitampil = "<h4>A. USULAN KEGIATAN</h4>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Kebutuhan Biaya</th>";
        $isitampil .= "<th style='text-align: center;'>Catatan Verifikasi Usulan</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $no = 1;
        $biaya_bongkar = array();
        $total_biaya = 0;
        foreach ($result as $row) {
            $biaya_bongkar[] = $row['harga_total'];
            $total_biaya = array_sum($biaya_bongkar);
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . number_format($row['harga_total']) . "</td>";
            $isitampil .= "<td>" . $row['status'] . " " . $row['catatan'] . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='2' style='text-align: center;'><b>Total</b></td>";
        $isitampil .= "<td> <b>" . number_format($total_biaya) . "</b></td>";
        $isitampil .= "<td></td>";
        $isitampil .= "</tr>";
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "<h4>B. DATA DUKUNG USULAN</h4>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Jenis Data Dukung</th>";
        $isitampil .= "<th style='text-align: center;'>Ada</th>";
        $isitampil .= "<th style='text-align: center;'>Tidak Ada</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $datadukung =
            $no = 1;
        $datadukung = $this->dakungModel->where('id_prog', $prog)->findAll();
        foreach ($datadukung as $key => $value) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $value->dakung . "</td>";
            $isitampil .= "<td></td>";
            $isitampil .= "<td></td>";
            $isitampil .= "</tr>";
        }

        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
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
        $pptk = $this->request->getVar('pptk');
        $nip = $this->request->getVar('nip');
        $puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
        $program = $this->programModel->asArray()->where('id', $prog)->first();

        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $result = $this->rpkModel->asArray()->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab, rpk.status, rpk.catatan, rpk.jadwal, sub_komponen.nama_subkomponen, b.harga_total')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')
            ->where([
                'rpk.id_pkm' => $pkm,
                'sub_komponen.id_prog' => $prog
            ])->findAll();
        $no = 1;

        $isitampil = "<h4>A. USULAN KEGIATAN</h4>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Rincian Menu / Komponen</th>";
        $isitampil .= "<th style='text-align: center;'>Kebutuhan Biaya</th>";
        $isitampil .= "<th style='text-align: center;'>Catatan Verifikasi Usulan</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $no = 1;
        $biaya_bongkar = array();
        $total_biaya = 0;
        foreach ($result as $row) {
            $biaya_bongkar[] = $row['harga_total'];
            $total_biaya = array_sum($biaya_bongkar);
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['nama_subkomponen'] . "</td>";
            $isitampil .= "<td>" . number_format($row['harga_total']) . "</td>";
            $isitampil .= "<td>" . $row['status'] . " " . $row['catatan'] . "</td>";
            $isitampil .= "</tr>";
        };
        $isitampil .= "<tr>";
        $isitampil .= "<td colspan='2' style='text-align: center;'><b>Total</b></td>";
        $isitampil .= "<td> <b>" . number_format($total_biaya) . "</b></td>";
        $isitampil .= "<td></td>";
        $isitampil .= "</tr>";
        $isitampil .= "</tbody>";
        $isitampil .= "</table>";
        $isitampil .= "</br";
        $isitampil .= "<h4>B. DATA DUKUNG USULAN</h4>";
        $isitampil .= "<table class='table table-bordered table-striped'>";
        $isitampil .= "<thead>";
        $isitampil .= "<tr>";
        $isitampil .= "<th>No.</th>";
        $isitampil .= "<th style='text-align: center;'>Jenis Data Dukung</th>";
        $isitampil .= "<th style='text-align: center;'>Ada</th>";
        $isitampil .= "<th style='text-align: center;'>Tidak Ada</th>";
        $isitampil .= "</tr>";
        $isitampil .= "</thead>";
        $isitampil .= "<tbody>";
        $no = 1;
        $datadukung = $this->dakungModel->asArray()->where('id_prog', $prog)->findAll();
        foreach ($datadukung as $row) {
            $isitampil .= "<tr>";
            $isitampil .= "<td>" . $no++ . "</td>";
            $isitampil .= "<td>" . $row['dakung'] . "</td>";
            $isitampil .= "<td></td>";
            $isitampil .= "<td></td>";
            $isitampil .= "</tr>";
        };

        // $isitampil .= "</tbody>";
        // $isitampil .= "</table>";
        // $isitampil .= "</br";

        $data = [
            'result' => $isitampil,
            'puskesmas' => $puskesmas['pkm'],
            'program' => $program['nama_program'],
            'pptk' => $pptk,
            'nip' => $nip,
        ];


        echo view('cetak_ba', $data);
    }
}