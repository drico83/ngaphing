<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\KomponenModel;

class Komponen extends BaseController
{

	protected $komponenModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->komponenModel = new KomponenModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'komponen',
			'title'     		=> 'Daftar Komponen Kegiatan'
		];
		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('komponen', $data);
		}
	}

	public function dt()
	{
		$tahun = $this->session->get('db_tahun');

		$dt = new Datatables('komponen');
		$dt->table('komponen');
		$dt->where(['tahun' => $tahun]);

		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getkomponen()
	{
		$tahun = $this->session->get('db_tahun');
		$komponen = $this->request->getPost('komponen');
		if ($komponen) {
			$datarka = $this->komponenModel->select('id, nama_komponen')->where('id', $komponen)->findAll();
			$isidata = "";
		} else {
			$datarka = $this->komponenModel->select('id, nama_komponen')->where('tahun', $tahun)->findAll();
			$isidata = "<option>--Pilih Komponen Kegiatan--</option>";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->nama_komponen . '</option>';
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

			$data = $this->komponenModel->where('id', $id)->first();

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
		$fields['nama_komponen'] = $this->request->getPost('namaKomponen');
		$fields['tahun'] = $tahun;


		$this->validation->setRules([
			'nama_komponen' => ['label' => 'Nama Komponen', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->komponenModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Komponen Kegiatan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Komponen Kegiatan Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_komponen'] = $this->request->getPost('namaKomponen');


		$this->validation->setRules([
			'nama_komponen' => ['label' => 'Nama Komponen', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->komponenModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Komponen Kegiatan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Komponen Kegiatan Gagal!';
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

			if ($this->komponenModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Komponen Kegiatan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Hapus Komponen Kegiatan Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}