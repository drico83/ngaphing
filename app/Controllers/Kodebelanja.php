<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\KodebelanjaModel;

class Kodebelanja extends BaseController
{

	protected $kodebelanjaModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->kodebelanjaModel = new KodebelanjaModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'kodebelanja',
			'title'     		=> 'Daftar Kode Belanja'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('kodebelanja', $data);
		}
	}

	public function dt()
	{
		$tahun = $this->session->get('db_tahun');
		$dt = new Datatables();
		$dt->table('kode_belanja');
		$dt->select('kode_belanja.*, kodrek.nama_rekening');
		$dt->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek');
		$dt->where(['kode_belanja.tahun' => $tahun]);
		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> 
            <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getkodebelanja()
	{
		$tahun = $this->session->get('db_tahun');
		$kodebelanja = $this->request->getPost('kodebelanja');
		if ($kodebelanja) {
			$datarka = $this->kodebelanjaModel->select('id, nama_belanja')->where(['id' => $kodebelanja, 'tahun' => $tahun])->findAll();
			$isidata = "";
		} else {
			$datarka = $this->kodebelanjaModel->select('kode_belanja.id, kodrek.kode, kodrek.nama_rekening, kode_belanja.nama_belanja')->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')->where('kode_belanja.tahun', $tahun)->findAll();
			$isidata = "<option>--Pilih Kodrek Belanja--</option>";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->kode . " | " . $value->nama_rekening . " | " . $value->nama_belanja . '</option>';
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

			$data = $this->kodebelanjaModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_kodrek'] = $this->request->getPost('idKodrek');
		$fields['nama_belanja'] = $this->request->getPost('namaBelanja');
		$fields['satuan'] = $this->request->getPost('satuan');
		$fields['harga'] = $this->request->getPost('harga');


		$this->validation->setRules([
			'id_kodrek' => ['label' => 'Kode Rekening', 'rules' => 'required|max_length[11]'],
			'nama_belanja' => ['label' => 'Nama Objek Belanja', 'rules' => 'required|max_length[255]'],
			'satuan' => ['label' => 'Satuan', 'rules' => 'required|max_length[20]'],
			'harga' => ['label' => 'Harga', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->kodebelanjaModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Komponen Belanja Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Komponen Belanja Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_kodrek'] = $this->request->getPost('idKodrek');
		$fields['nama_belanja'] = $this->request->getPost('namaBelanja');
		$fields['satuan'] = $this->request->getPost('satuan');
		$fields['harga'] = $this->request->getPost('harga');


		$this->validation->setRules([
			'id_kodrek' => ['label' => 'Kode Rekening', 'rules' => 'required|max_length[11]'],
			'nama_belanja' => ['label' => 'Nama Objek Belanja', 'rules' => 'required|max_length[255]'],
			'satuan' => ['label' => 'Satuan', 'rules' => 'required|max_length[20]'],
			'harga' => ['label' => 'Harga', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->kodebelanjaModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Komponen Belanja Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Komponen Belanja Gagal!';
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

			if ($this->kodebelanjaModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Komponen Belanja Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Hapus Komponen Belanja Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}