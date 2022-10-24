<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\ProgramModel;

class Program extends BaseController
{

	protected $programModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->programModel = new ProgramModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'program',
			'title'     		=> 'Daftar Program Pelayanan Puskesmas'
		];
		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('program', $data);
		}
	}



	public function dt()
	{
		$dt = new Datatables('program'); //siswa is a table name
		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getprogram()
	{
		$prog = $this->request->getPost('prog');
		if ($prog) {
			$datarka = $this->programModel->select('id, nama_program')->where('id', $prog)->findAll();
			$isidata = "";
		} else {
			$datarka = $this->programModel->select('id, nama_program')->findAll();
			$isidata = "";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->nama_program . '</option>';
		};

		$msg = [
			'data' => $isidata
		];
		return $this->response->setJSON($msg);
	}
	public function getprogram2()
	{
		$prog = $this->request->getPost('prog');

		$datarka = $this->programModel->select('id, nama_program')->findAll();
		$isidata = "";


		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isi = explode(',', $prog);
			if (in_array($isi, $value->id)) {
				$isidata .= '	<option value="' . $value->id . '" selected>' . $value->nama_program . '</option>';
			} else {
				$isidata .= '	<option value="' . $value->id . '">' . $value->nama_program . '</option>';
			}
		};

		$msg = [
			'data' => $isidata
		];
		return $this->response->setJSON($msg);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->programModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_program'] = $this->request->getPost('namaProgram');


		$this->validation->setRules([
			'nama_program' => ['label' => 'Nama Program', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->programModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Program Pelayanan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Program Pelayanan Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_program'] = $this->request->getPost('namaProgram');


		$this->validation->setRules([
			'nama_program' => ['label' => 'Nama Program', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->programModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Program Pelayanan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Program Pelayanan Gagal!';
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

			if ($this->programModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Program Pelayanan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Program Pelayanan Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}