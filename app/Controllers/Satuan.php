<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\SatuanModel;

class Satuan extends BaseController
{

	protected $satuanModel;
	protected $validation;
	protected $session;


	public function __construct()
	{
		$this->satuanModel = new SatuanModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'satuan',
			'title'     		=> 'Daftar Satuan Belanja'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('satuan', $data);
		}
	}

	public function dt()
	{
		$dt = new Datatables('satuan');

		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>";
			return $btn;
		})->draw();
	}

	public function getsatuan()
	{
		$satuan = $this->request->getPost('satuan');
		if ($satuan) {
			$datarka = $this->satuanModel->select('id, nama_satuan')
				->where('id', $satuan)->findAll();
			$isidata = "<option>-Pilih Satuan-</option>";
		} else {
			$datarka = $this->satuanModel->select('id, nama_satuan')->findAll();
			$isidata = "<option>--Pilih Satuan--</option>";
		}

		$data['data'] = array();

		foreach ($datarka as $key => $value) {
			$isidata .= '<option value="' . $value->id . '">' . $value->nama_satuan . '</option>';
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

			$data = $this->satuanModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_satuan'] = $this->request->getPost('namaSatuan');


		$this->validation->setRules([
			'nama_satuan' => ['label' => 'Nama satuan', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->satuanModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Data has been inserted successfully';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Insertion error!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_satuan'] = $this->request->getPost('namaSatuan');


		$this->validation->setRules([
			'nama_satuan' => ['label' => 'Nama satuan', 'rules' => 'required|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->satuanModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Successfully updated';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Update error!';
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

			if ($this->satuanModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Deletion succeeded';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Deletion error!';
			}
		}

		return $this->response->setJSON($response);
	}
}