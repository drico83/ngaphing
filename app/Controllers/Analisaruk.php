<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\UsulanModel;
use App\Models\HistoryModel;
use App\Models\UptModel;

class Analisaruk extends BaseController
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
            'controller'        => 'analisaruk',
            'title'             => 'Daftar Rincian Usulan Per Puskesmas'
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('analisaruk', $data);
        }
    }


    public function getAll()
    {

        $kodrek = $this->request->getPost('id');
        $tahun = $this->session->get('db_tahun');
        $response = array();

        $data['data'] = array();

        $result = $this->usulanModel->select('usul_belanja.id_pkm, upt.pkm, sum(usul_belanja.vol_total) as vol_total')
            ->join('kode_belanja', 'kode_belanja.id=usul_belanja.id_belanja')
            ->join('upt', 'upt.id=usul_belanja.id_pkm')
            ->where(['usul_belanja.tahun' => $tahun, 'kode_belanja.id_kodrek' => $kodrek])
            ->groupBy('usul_belanja.id_pkm')
            ->findAll();
        $no = 1;
        foreach ($result as $key => $value) {

            $data['data'][$key] = array(
                $no++,
                $value->pkm,
                $value->vol_total,
                312,
                $value->vol_total / 312,
            );
        }

        return $this->response->setJSON($data);
    }
}