<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\UsulanModel;
use App\Models\HistoryModel;
use App\Models\UptModel;

class Rrincian extends BaseController
{

    protected $usulanModel;
    protected $historyModel;
    protected $uptModel;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->usulanModel = new UsulanModel();
        $this->historyModel = new HistoryModel();
        $this->uptModel = new UptModel();
        $this->session = session();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $data = [
            'controller'        => 'rrincian',
            'title'             => 'Daftar Rincian Usulan Per Puskesmas'
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('bok/rrincian', $data);
        }
    }

    public function dt()
    {
        // $rincian = $this->request->getPost('id');
        // $tahun = $this->session->get('db_tahun');
        // $dt = new Datatables();

        // $join = "(SELECT usul_belanja.id_pkm, SUM(usul_belanja.harga_total) as harga_total1 FROM usul_belanja LEFT JOIN ruk ON ruk.id=usul_belanja.id_sub LEFT JOIN sub_komponen ON sub_komponen.id=ruk.id_menu  WHERE sub_komponen.id_rincian=" . $rincian . " and sub_komponen.tahun=" . $tahun . " GROUP BY usul_belanja.id_pkm) as b";
        // $komponen = '(select usul_belanja_rpk.id_pkm, rincian.id, sum(usul_belanja_rpk.harga_total) as harga_total2 FROM usul_belanja_rpk
        // join rpk on rpk.id=usul_belanja_rpk.id_sub join sub_komponen on sub_komponen.id=rpk.id_menu join rincian on rincian.id=sub_komponen.id_rincian where rincian.id=' . $rincian . 'and rincian.tahun=' . $tahun . ' group by usul_belanja_rpk.id_pkm) as d';

        // $dt->table('upt');
        // $dt->select('upt.id, upt.pkm, b.harga_total');
        // $dt->join($join, 'b.id_pkm=upt.id', 'left');
        // $dt->join($komponen, 'd.id_pkm=upt.id', 'left');
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

        $komponen = '(select usul_belanja_rpk.id_pkm, rincian.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah FROM usul_belanja_rpk
        join rpk on rpk.id=usul_belanja_rpk.id_sub join sub_komponen on sub_komponen.id=rpk.id_menu join rincian on rincian.id=sub_komponen.id_rincian left join realisasi ON realisasi.id_rpk=usul_belanja_rpk.id where rincian.id="' . $rincian . '" and usul_belanja_rpk.tahun="' . $tahun . '" AND realisasi.bulan>="' . $bulan . '" AND realisasi.bulan<="' . $bulan2 . '" group by usul_belanja_rpk.id_pkm) as d';


        $result = $this->uptModel->select('upt.id, upt.pkm, d.harga_total, d.jumlah')
            ->join($komponen, 'd.id_pkm=upt.id', 'left')
            ->limit(68)
            ->find();
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