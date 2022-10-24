<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RincianModel;

class Rincian extends BaseController
{

	protected $rincianModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->rincianModel = new RincianModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'rincian',
			'title'     		=> 'Daftar Rincian Kegiatan'
		];
		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('rincian', $data);
		}
	}



	public function dt()
	{
		$tahun = $this->session->get('db_tahun');
		$dt = new Datatables();
		$dt->table('rincian');
		$dt->where(['tahun' => $tahun]);

		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getrincian()
	{
		$tahun = $this->session->get('db_tahun');
		$rincian = $this->request->getPost('rincian');
		if ($rincian) {
			$datarka = $this->rincianModel->select('id, nama_rincian')->where('id', $rincian)->findAll();
			$isidata = "";
		} else {
			$datarka = $this->rincianModel->select('id, nama_rincian')->where('tahun', $tahun)->findAll();
			$isidata = "<option>-- Pilih Rincian Kegiatan --</option>";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->nama_rincian . '</option>';
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

			$data = $this->rincianModel->where('id', $id)->first();

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
		$fields['nama_rincian'] = $this->request->getPost('namaRincian');
		$fields['tahun'] = $tahun;


		$this->validation->setRules([
			'nama_rincian' => ['label' => 'Nama Rincian', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->rincianModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Data Rincian Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Data Rincian Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_rincian'] = $this->request->getPost('namaRincian');


		$this->validation->setRules([
			'nama_rincian' => ['label' => 'Nama Rincian', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->rincianModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Data Rincian Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Data Rincian Gagal!';
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

			if ($this->rincianModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Data Rincian Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Hapus Data Rincian Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}