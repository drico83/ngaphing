<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\SubkomponenModel;

class Subkomponen2 extends BaseController
{

	protected $subkomponenModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->subkomponenModel = new SubkomponenModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'subkomponen2',
			'title'     		=> 'Daftar Sub Komponen Kegiatan'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('subkomponen2', $data);
		}
	}

	public function dt()
	{
		$tahun = $this->session->get('db_tahun');
		$dt = new Datatables('sub_komponen');
		$dt->select('sub_komponen.id as id, program.nama_program, rincian.nama_rincian, komponen.nama_komponen, sub_komponen.nama_subkomponen');
		$dt->join('program', 'program.id=sub_komponen.id_prog');
		$dt->join('rincian', 'rincian.id=sub_komponen.id_rincian');
		$dt->join('komponen', 'komponen.id=sub_komponen.id_kom');
		$dt->where(['sub_komponen.tahun' => $tahun]);
		return $dt->draw();
	}

	public function getsubkomponen()
	{
		$subkomponen = $this->request->getPost('subkomponen');
		if ($subkomponen) {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen')->where('id', $subkomponen)->findAll();
			$isidata = "";
		} else {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen')->findAll();
			$isidata = "<option>--Pilih Sub Komponen Kegiatan--</option>";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->nama_subkomponen . '</option>';
		};

		$msg = [
			'data' => $isidata
		];
		return $this->response->setJSON($msg);
	}

	public function getsubkomponen2()
	{
		$prog = $this->request->getPost('prog');
		if ($prog) {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen')->where('id_prog', $prog)->findAll();
			$isidata = "<option>--Pilih Sub Komponen Kegiatan--</option>";
		} else {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen')->findAll();
			$isidata = "<option>--Pilih Sub Komponen Kegiatan--</option>";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->nama_subkomponen . '</option>';
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

			$data = $this->subkomponenModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_subkomponen'] = $this->request->getPost('namaSubkomponen');
		$fields['id_prog'] = $this->request->getPost('id_prog');
		$fields['id_rincian'] = $this->request->getPost('id_rincian');
		$fields['id_kom'] = $this->request->getPost('id_kom');


		$this->validation->setRules([
			'nama_subkomponen' => ['label' => 'Nama Sub Komponen', 'rules' => 'required'],
			'id_prog' => ['label' => 'Nama Program', 'rules' => 'required'],
			'id_rincian' => ['label' => 'Nama Rincian', 'rules' => 'required'],
			'id_kom' => ['label' => 'Nama Komponen', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->subkomponenModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Sub Komponen Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Sub Komponen Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_subkomponen'] = $this->request->getPost('namaSubkomponen');
		$fields['id_prog'] = $this->request->getPost('id_prog');
		$fields['id_rincian'] = $this->request->getPost('id_rincian');
		$fields['id_kom'] = $this->request->getPost('id_kom');

		$this->validation->setRules([
			'nama_subkomponen' => ['label' => 'Nama Sub Komponen', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->subkomponenModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Sub Komponen Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Sub Komponen Gagal!';
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

			if ($this->subkomponenModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Sub Komponen Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Hapus Sub Komponen Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}