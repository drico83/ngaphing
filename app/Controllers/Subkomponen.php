<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\SubkomponenModel;

class Subkomponen extends BaseController
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
			'controller'    	=> 'subkomponen',
			'title'     		=> 'Daftar Sub Komponen Kegiatan'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('subkomponen', $data);
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
		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getsubkomponen()
	{
		$tahun = $this->session->get('db_tahun');
		$subkomponen = $this->request->getPost('subkomponen');
		if ($subkomponen) {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen')->where('id', $subkomponen)->findAll();
			$isidata = "";
		} else {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen')->where('tahun', $tahun)->findAll();
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
		$tahun = $this->session->get('db_tahun');
		$prog = $this->request->getPost('prog');
		if ($prog) {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen, id_prog, keterangan')->where(['tahun' => $tahun])->findAll();
			$isidata = "<option>--Pilih Sub Komponen Kegiatan--</option>";

			foreach ($datarka as $key => $value) {
				$list = explode(",", $value->id_prog);
				if (in_array($prog, $list)) {

					$isidata .= '	<option value="' . $value->id . '">' . $value->nama_subkomponen . ' | ' . $value->keterangan . '</option>';
				}
			};
		} else {
			$datarka = $this->subkomponenModel->select('id, nama_subkomponen')->where('tahun', $tahun)->findAll();
			$isidata = "<option>--Pilih Sub Komponen Kegiatan--</option>";
			foreach ($datarka as $key => $value) {
				$isidata .= '	<option value="' . $value->id . '">' . $value->nama_subkomponen . '</option>';
			};
		}

		$data['data'] = array();



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
		$tahun = $this->session->get('db_tahun');
		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_subkomponen'] = $this->request->getPost('namaSubkomponen');
		$id_prog = $this->request->getPost('id_prog');
		$fields['id_rincian'] = $this->request->getPost('id_rincian');
		$fields['id_kom'] = $this->request->getPost('id_kom');
		$fields['tahun'] = $tahun;
		$isi = '';
		// var_dump($id_prog);
		// die;
		foreach ($id_prog as $key => $value) {
			$isi .= $value . ',';
		};
		$fields['id_prog'] = $isi;
		$this->validation->setRules([
			'nama_subkomponen' => ['label' => 'Nama Sub Komponen', 'rules' => 'required'],
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