<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\MenuModel;

class Menu extends BaseController
{

	protected $menuModel;
	protected $validation;

	public function __construct()
	{
		$this->menuModel = new MenuModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'menu',
			'title'     		=> 'Daftar Menu Kegiatan'
		];

		return view('menu', $data);
	}

	public function dt()
	{
		$dt = new Datatables('menu');
		$dt->select('menu.id as id, program.nama_program, rincian.nama_rincian, komponen.nama_komponen, sub_komponen.nama_subkomponen ');
		$dt->join('program', 'program.id=menu.id_prog');
		$dt->join('rincian', 'rincian.id=menu.id_rincian');
		$dt->join('komponen', 'komponen.id=menu.id_komponen');
		$dt->join('sub_komponen', 'sub_komponen.id=menu.id_subkomponen');
		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->menuModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_prog'] = $this->request->getPost('idProg');
		$fields['id_rincian'] = $this->request->getPost('idRincian');
		$fields['id_komponen'] = $this->request->getPost('idKomponen');
		$fields['id_subkomponen'] = $this->request->getPost('idSubkomponen');


		$this->validation->setRules([
			'id_prog' => ['label' => 'Program Pelayanan', 'rules' => 'required|max_length[11]'],
			'id_rincian' => ['label' => 'Rincian Kegiatan', 'rules' => 'required|max_length[11]'],
			'id_komponen' => ['label' => 'Komponen Kegiatan', 'rules' => 'required|max_length[11]'],
			'id_subkomponen' => ['label' => 'Sub Komponen Kegiatan', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->menuModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Menu Kegiatan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Menu Kegiatan Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_prog'] = $this->request->getPost('idProg');
		$fields['id_rincian'] = $this->request->getPost('idRincian');
		$fields['id_komponen'] = $this->request->getPost('idKomponen');
		$fields['id_subkomponen'] = $this->request->getPost('idSubkomponen');


		$this->validation->setRules([
			'id_prog' => ['label' => 'Program Pelayanan', 'rules' => 'required|max_length[11]'],
			'id_rincian' => ['label' => 'Rincian Kegiatan', 'rules' => 'required|max_length[11]'],
			'id_komponen' => ['label' => 'Komponen Kegiatan', 'rules' => 'required|max_length[11]'],
			'id_subkomponen' => ['label' => 'Sub Komponen Kegiatan', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->menuModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Menu Kegiatan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Menu Kegiatan Gagal!';
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

			if ($this->menuModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Menu Kegiatan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Hapus Menu Kegiatan Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}