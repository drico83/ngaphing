<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\KodrekModel;

class Kodrek extends BaseController
{

	protected $kodrekModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->kodrekModel = new KodrekModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'kodrek',
			'title'     		=> 'Daftar Kode Rekening Belanja'
		];
		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('kodrek', $data);
		}
	}

	public function dt()
	{
		$tahun = $this->session->get('db_tahun');
		$dt = new Datatables();
		$dt->table('kodrek');
		$dt->where(['tahun' => $tahun]);
		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> 
            <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getkodrek()
	{
		$kodrek = $this->request->getPost('kodrek');
		if ($kodrek) {
			$datarka = $this->kodrekModel->select('id, nama_rekening')->where('id', $kodrek)->findAll();
			$isidata = "";
		} else {
			$datarka = $this->kodrekModel->select('id, nama_rekening')->findAll();
			$isidata = "<option>--Pilih Kodrek Belanja--</option>";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->nama_rekening . '</option>';
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

			$data = $this->kodrekModel->where('id', $id)->first();

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
		$fields['nama_rekening'] = $this->request->getPost('namaRekening');


		$this->validation->setRules([
			'kode' => ['label' => 'Kode Rekening', 'rules' => 'required|max_length[255]'],
			'nama_rekening' => ['label' => 'Nama Rekening', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->kodrekModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Kode Rekening Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Kode Rekening Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['kode'] = $this->request->getPost('kode');
		$fields['nama_rekening'] = $this->request->getPost('namaRekening');


		$this->validation->setRules([
			'kode' => ['label' => 'Kode Rekening', 'rules' => 'required|max_length[255]'],
			'nama_rekening' => ['label' => 'Nama Rekening', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->kodrekModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Kode Rekening Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Kode Rekening Gagal!';
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

			if ($this->kodrekModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Kode Rekening Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Hapus Kode Rekening Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}