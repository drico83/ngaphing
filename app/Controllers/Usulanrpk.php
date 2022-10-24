<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\UsulanRpkModel;
use App\Models\HistoryModel;

class Usulanrpk extends BaseController
{

    protected $usulanrpkModel;
    protected $historyModel;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->usulanrpkModel = new UsulanRpkModel();
        $this->historyModel = new HistoryModel();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {

        $data = [
            'controller'        => 'usulan',
            'title'             => 'Daftar Usulan Objek Belanja'
        ];

        return view('usulan', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();
        $objek = $this->request->getPost('objek');
        $idPkm = $this->request->getPost('idPkm');
        $idProgram = $this->request->getPost('idProgram');
        $result = $this->usulanrpkModel->select('usul_belanja_rpk.*, kode_belanja.nama_belanja, kode_belanja.harga, kodrek.kode')
            ->join('kode_belanja', 'kode_belanja.id=usul_belanja_rpk.id_belanja')
            ->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
            ->where([
                'usul_belanja_rpk.id_pkm' => $idPkm,
                'usul_belanja_rpk.id_program' => $idProgram,
                'usul_belanja_rpk.id_sub' => $objek
            ])->findAll();
        $no = 1;
        foreach ($result as $key => $value) {
            $sat1 = "";
            $sat2 = "";
            $sat3 = "";
            $sat4 = "";
            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="del(' . $value->id . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            if ($value->vol2 == "") {
                $sat1 = $value->sat1;
            } else {
                $sat1 = " x ";
            };
            if ($value->vol3 == "") {
                $sat2 = $value->sat2;
            } else {
                $sat2 = " x ";
            };
            if ($value->vol4 == "") {
                $sat3 = $value->sat3;
            } else {
                $sat3 = " x ";
            };
            $data['data'][$key] = array(
                $no++,
                $value->kode,
                $value->nama_belanja,
                $value->keterangan,
                $value->harga,
                $value->vol1 . $sat1 . $value->vol2 . $sat2 . $value->vol3 . $sat3 . $value->vol4 . $value->sat4,
                $value->vol_total,
                $value->harga_total,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }



    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('id');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->usulanrpkModel->where('id', $id)->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {
        $tahun = $this->session->get('db_tahun');
        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['id_pkm'] = $this->request->getPost('idPkm');
        $fields['id_program'] = $this->request->getPost('idProgram');
        $fields['id_sub'] = $this->request->getPost('idSub');
        $fields['id_belanja'] = $this->request->getPost('idBelanja');
        $fields['vol1'] = $this->request->getPost('vol1');
        $fields['sat1'] = $this->request->getPost('sat1');
        $fields['vol2'] = $this->request->getPost('vol2');
        $fields['sat2'] = $this->request->getPost('sat2');
        $fields['vol3'] = $this->request->getPost('vol3');
        $fields['sat3'] = $this->request->getPost('sat3');
        $fields['vol4'] = $this->request->getPost('vol4');
        $fields['sat4'] = $this->request->getPost('sat4');
        $fields['vol_total'] = $this->request->getPost('vol_total');
        $fields['harga_total'] = $this->request->getPost('harga_total');
        $fields['keterangan'] = $this->request->getPost('keterangan');
        $fields['tahun'] = $tahun;
        $id_prog = $this->request->getPost('idProgram');
        $id_pkm = $this->request->getPost('idPkm');
        $keterangan = $this->request->getPost('keterangan');
        $harga_total = $this->request->getPost('harga_total');

        $this->validation->setRules([
            'id_belanja' => ['label' => 'Komponen Belanja', 'rules' => 'required|numeric|max_length[11]'],
            'vol1' => ['label' => 'Volume', 'rules' => 'required|numeric|max_length[11]'],
            'sat1' => ['label' => 'Satuan', 'rules' => 'required|max_length[2]'],
            'keterangan' => ['label' => 'Keterangan', 'rules' => 'required'],
        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->usulanrpkModel->insert($fields)) {

                $kolom['deskripsi'] = user()->name . ' menambahkan objek belanja ' . $keterangan . ' sebesar ' . $harga_total;
                $kolom['id_pkm'] = $id_pkm;
                $kolom['id_prog'] = $id_prog;
                if ($this->historyModel->insert($kolom)) {
                    $response['success'] = true;
                    $response['messages'] = 'Tambah Objek Belanja Berhasil';
                }
            } else {

                $response['success'] = false;
                $response['messages'] = 'Tambah Objek Belanja Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function edit()
    {

        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['id_pkm'] = $this->request->getPost('idPkm');
        $fields['id_program'] = $this->request->getPost('idProgram');
        $fields['id_sub'] = $this->request->getPost('idSub');
        $fields['id_belanja'] = $this->request->getPost('idBelanja');
        $fields['vol1'] = $this->request->getPost('vol1');
        $fields['sat1'] = $this->request->getPost('sat1');
        $fields['vol2'] = $this->request->getPost('vol2');
        $fields['sat2'] = $this->request->getPost('sat2');
        $fields['vol3'] = $this->request->getPost('vol3');
        $fields['sat3'] = $this->request->getPost('sat3');
        $fields['vol4'] = $this->request->getPost('vol4');
        $fields['sat4'] = $this->request->getPost('sat4');


        $this->validation->setRules([
            'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|numeric|max_length[11]'],
            'id_program' => ['label' => 'Nama Program', 'rules' => 'required|numeric|max_length[11]'],
            'id_sub' => ['label' => 'Nama Kegiatan', 'rules' => 'required|numeric|max_length[11]'],
            'id_belanja' => ['label' => 'Komponen Belanja', 'rules' => 'required|numeric|max_length[11]'],
            'vol1' => ['label' => 'Volume', 'rules' => 'required|numeric|max_length[11]'],
            'sat1' => ['label' => 'Satuan', 'rules' => 'required|max_length[2]'],
            'vol2' => ['label' => 'Volume', 'rules' => 'required|numeric|max_length[11]'],
            'sat2' => ['label' => 'Satuan', 'rules' => 'required|max_length[2]'],
            'vol3' => ['label' => 'Volume', 'rules' => 'required|numeric|max_length[11]'],
            'sat3' => ['label' => 'Satuan', 'rules' => 'required|max_length[2]'],
            'vol4' => ['label' => 'Volume', 'rules' => 'required|numeric|max_length[11]'],
            'sat4' => ['label' => 'Satuan', 'rules' => 'required|max_length[2]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->usulanrpkModel->update($fields['id'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Ubah Objek Belanja Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Ubah Objek Belanja Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function remove()
    {
        $response = array();

        $id = $this->request->getPost('id');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->usulanrpkModel->where('id', $id)->delete()) {

                $response['success'] = true;
                $response['messages'] = 'Hapus Objek Belanja Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Hapus Objek Belanja Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }
}