<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\UsulanModel;
use App\Models\HistoryModel;
use App\Models\UptModel;
use App\Models\UsulanRpkModel;

class Rsubkomponen extends BaseController
{

    protected $usulanModel;
    protected $historyModel;
    protected $uptModel;
    protected $usulanrpkModel;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->usulanModel = new UsulanModel();
        $this->usulanrpkModel = new UsulanRpkModel();
        $this->historyModel = new HistoryModel();
        $this->uptModel = new UptModel();
        $this->session = session();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $data = [
            'controller'        => 'rsubkomponen',
            'title'             => 'Daftar Rincian Usulan Per Puskesmas'
        ];
        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('bok/rsubkomponen', $data);
        }
    }

    public function dt()
    {
        // $rincian = $this->request->getPost('id');
        // // if ($rincian == "") {
        // //     $rincian = 1;
        // // };
        // $dt = new Datatables();

        // $join = "(SELECT usul_belanja.id_pkm, SUM(usul_belanja.harga_total) as harga_total FROM usul_belanja LEFT JOIN ruk ON ruk.id=usul_belanja.id_sub LEFT JOIN sub_komponen ON sub_komponen.id=ruk.id_menu  WHERE sub_komponen.id=" . $rincian . " GROUP BY usul_belanja.id_pkm) as b";
        // $dt->table('upt');
        // $dt->select('upt.id, upt.pkm, b.harga_total');
        // $dt->join($join, 'b.id_pkm=upt.id', 'left');
        // return $dt->draw();
    }

    public function getAll()
    {

        $rincian = $this->request->getPost('id');
        $bulan = $this->request->getPost('bulan');
        $bulan2 = $this->request->getPost('bulan2');
        $tahun = $this->session->get('db_tahun');
        $response = array();

        $data['data'] = array();

        $komponen = '(select usul_belanja_rpk.id_pkm, sub_komponen.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah FROM usul_belanja_rpk
        left join realisasi on realisasi.id_rpk=usul_belanja_rpk.id join rpk on rpk.id=usul_belanja_rpk.id_sub join sub_komponen on sub_komponen.id=rpk.id_menu join rincian on rincian.id=sub_komponen.id_rincian where sub_komponen.id="' . $rincian . '" AND usul_belanja_rpk.tahun="' . $tahun . '" AND sub_komponen.tahun="' . $tahun . '" AND rincian.tahun="' . $tahun . '" AND realisasi.bulan>="' . $bulan . '" AND bulan<="' . $bulan2 . '" group by usul_belanja_rpk.id_pkm) as d';


        $result = $this->uptModel->select('upt.id, upt.pkm, d.harga_total, d.jumlah')
            ->join($komponen, 'd.id_pkm=upt.id', 'left')
            ->findAll();
        $no = 1;
        foreach ($result as $key => $value) {

            $data['data'][$key] = array(
                $no++,
                $value->pkm,
                $value->harga_total,
                $value->jumlah,
            );
        }

        return $this->response->setJSON($data);
    }
}