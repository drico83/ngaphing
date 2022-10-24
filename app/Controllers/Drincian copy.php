<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\UsulanModel;
use App\Models\HistoryModel;
use App\Models\UptModel;

class Drincian extends BaseController
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
            'controller'        => 'drincian',
            'title'             => 'Daftar Rincian Usulan Per Puskesmas'
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('drincian', $data);
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
        $tahun = $this->session->get('db_tahun');
        $response = array();

        $data['data'] = array();
        $join = "(SELECT ruk.id, sub_komponen.id_prog, sub_komponen.id_rincian, sub_komponen.id_kom, ruk.id_pkm, ruk.id_menu, ruk.status, sum(b.harga_total) as harga_approve FROM ruk JOIN (SELECT `id_sub`, SUM(`harga_total`) as harga_total FROM `usul_belanja` where usul_belanja.tahun=" . $tahun . " GROUP BY `id_sub`) as b ON b.id_sub=ruk.id JOIN sub_komponen ON sub_komponen.id=ruk.id_menu WHERE ruk.status='Approve' AND sub_komponen.id_rincian= " . $rincian . " GROUP BY ruk.id_pkm) as c";

        $komponen = '(select usul_belanja_rpk.id_pkm, rincian.id, sum(usul_belanja_rpk.harga_total) as harga_total, sum(realisasi.jumlah) as jumlah FROM usul_belanja_rpk
        join rpk on rpk.id=usul_belanja_rpk.id_sub join sub_komponen on sub_komponen.id=rpk.id_menu join rincian on rincian.id=sub_komponen.id_rincian join realisasi ON realisasi.id_rpk=usul_belanja_rpk.id where rincian.id="' . $rincian . '" and usul_belanja_rpk.tahun="' . $tahun . '" group by usul_belanja_rpk.id_pkm) as d';
        // $join = "(SELECT usul_belanja.id_pkm, SUM(usul_belanja.harga_total) as harga_total FROM usul_belanja LEFT JOIN ruk ON ruk.id=usul_belanja.id_sub LEFT JOIN sub_komponen ON sub_komponen.id=ruk.id_menu  WHERE sub_komponen.id_rincian=" . $rincian . " GROUP BY usul_belanja.id_pkm) as b";

        $result = $this->uptModel->select('upt.id, upt.pkm, c.harga_approve, d.harga_total')
            ->join($join, 'c.id_pkm=upt.id', 'left')
            ->join($komponen, 'd.id_pkm=upt.id', 'left')
            ->limit(68)
            ->find();
        $no = 1;
        foreach ($result as $key => $value) {

            $data['data'][$key] = array(
                $no++,
                $value->pkm,
                $value->harga_approve,
                $value->harga_total,
            );
        }

        return $this->response->setJSON($data);
    }
}