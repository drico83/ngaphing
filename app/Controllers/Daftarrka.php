<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\DaftarrkaModel;

class Daftarrka extends BaseController
{

    protected $daftarrkaModel;
    protected $validation;

    public function __construct()
    {
        $this->daftarrkaModel = new DaftarrkaModel();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {

        $data = [
            'controller'        => 'daftarrka',
            'title'             => 'Daftar RKA',
            'request'           => \Config\Services::request(),
        ];

        return view('backweb/daftarrka', $data);
    }


    public function dt()
    {
        $dt = new Datatables('daftar_rka'); //siswa is a table name
        return $dt->addColumn('action', function ($db) {
            $id = $db['id'];
            $btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i></button> <button class='btn btn-sm btn-danger' onclick='del(\"$id\")' title='delete'><i class='fa fa-eraser'></i></button>";
            return $btn;
        })->draw();
    }


    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('id');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->daftarrkaModel->where('id', $id)->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['kode'] = $this->request->getPost('kode');
        $fields['judul'] = $this->request->getPost('judul');


        $this->validation->setRules([
            'kode' => ['label' => 'Kode RKA', 'rules' => 'required|max_length[255]'],
            'judul' => ['label' => 'Judul RKA', 'rules' => 'required|max_length[255]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->daftarrkaModel->insert($fields)) {

                $response['success'] = true;
                $response['messages'] = 'RKA Baru berhasil Ditambahkan';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Tambah RKA Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function edit()
    {

        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['kode'] = $this->request->getPost('kode');
        $fields['judul'] = $this->request->getPost('judul');


        $this->validation->setRules([
            'kode' => ['label' => 'Kode RKA', 'rules' => 'required|max_length[255]'],
            'judul' => ['label' => 'Judul RKA', 'rules' => 'required|max_length[255]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->daftarrkaModel->update($fields['id'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Ubah RKA Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Ubah RKA Gagal!';
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

            if ($this->daftarrkaModel->where('id', $id)->delete()) {

                $response['success'] = true;
                $response['messages'] = 'Hapus RKA Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Hapus RKA Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }
}