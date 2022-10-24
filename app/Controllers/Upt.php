<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;
use App\Models\UptModel;

class Upt extends BaseController
{

	protected $uptModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->uptModel = new UptModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'upt',
			'title'     		=> 'Setting UPT'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('upt', $data);
		}
	}


	public function basic()
	{
		$db = db_connect();
		$builder = $db->table('upt')->select('id, kode, pkm, kapus, nip_kapus, katu, nip_katu');

		return DataTable::of($builder)->addNumbering()
			->add('action', function ($row) {

				if (in_groups('admin')) {
					$button = '<a class="dropdown-item text-danger" onClick="remove(' . $row->id . ')"><i class="fas fa-trash"></i>   ' .  lang("App.delete")  . '</a>';
				}
				return '<div class="btn-group">
				<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-eject"></i> Aksi  </button>
				<div class="dropdown-menu">
				<a class="dropdown-item text-info" onClick="save(' . $row->id . ')"><i class="fas fa-edit"></i>   ' .  lang("App.edit")  . '</a>
				<a class="dropdown-item text-orange" ><i class="far fa-copy"></i>   ' .  lang("App.copy")  . '</a>
				<div class="dropdown-divider"></div>
				' . $button . '
				</div></div>';
			}, 'last')
			->hide('id')
			->toJson();
	}



	public function getSelect()
	{
		$upt = $this->request->getPost('upt');
		$pkm = user()->puskesmas;
		if (in_groups('admin')) {
			if ($upt) {
				$dataupt = $this->uptModel->select('id, pkm')->where('id', $upt)->findAll();
				$isidata = "";
			} else {
				$dataupt = $this->uptModel->select('id, pkm')->findAll();
				$isidata = "<option>--Pilih Nama UPT--</option>";
			}
		} else {
			if ($upt) {
				$dataupt = $this->uptModel->select('id, pkm')->where('id', $upt)->findAll();
				$isidata = "";
			} else {
				$dataupt = $this->uptModel->select('id, pkm')->where('id', $pkm)->findAll();
				$isidata = "<option>--Pilih Nama UPT--</option>";
			}
		};



		$data['data'] = array();

		foreach ($dataupt as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->pkm . '</option>';
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

			$data = $this->uptModel->where('id', $id)->first();

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
		$fields['pkm'] = $this->request->getPost('pkm');
		$fields['kapus'] = $this->request->getPost('kapus');
		$fields['nip_kapus'] = $this->request->getPost('nip_kapus');
		$fields['katu'] = $this->request->getPost('katu');
		$fields['nip_katu'] = $this->request->getPost('nip_katu');


		$this->validation->setRules([
			'kode' => ['label' => 'Kode UPT', 'rules' => 'required|min_length[0]|max_length[75]'],
			'pkm' => ['label' => 'Nama UPT', 'rules' => 'required|min_length[0]|max_length[255]'],
			'kapus' => ['label' => 'Kepala', 'rules' => 'required|min_length[0]|max_length[100]'],
			'nip_kapus' => ['label' => 'NIP Kepala', 'rules' => 'required|min_length[0]|max_length[20]'],
			'katu' => ['label' => 'Kasubag TU', 'rules' => 'required|min_length[0]|max_length[100]'],
			'nip_katu' => ['label' => 'NIP Kasubag TU', 'rules' => 'required|min_length[0]|max_length[20]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->uptModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.insert-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.insert-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['kode'] = $this->request->getPost('kode');
		$fields['pkm'] = $this->request->getPost('pkm');
		$fields['kapus'] = $this->request->getPost('kapus');
		$fields['nip_kapus'] = $this->request->getPost('nip_kapus');
		$fields['katu'] = $this->request->getPost('katu');
		$fields['nip_katu'] = $this->request->getPost('nip_katu');


		$this->validation->setRules([
			'kode' => ['label' => 'Kode UPT', 'rules' => 'required|min_length[0]|max_length[75]'],
			'pkm' => ['label' => 'Nama UPT', 'rules' => 'required|min_length[0]|max_length[255]'],
			'kapus' => ['label' => 'Kepala', 'rules' => 'required|min_length[0]|max_length[100]'],
			'nip_kapus' => ['label' => 'NIP Kepala', 'rules' => 'required|min_length[0]|max_length[20]'],
			'katu' => ['label' => 'Kasubag TU', 'rules' => 'required|min_length[0]|max_length[100]'],
			'nip_katu' => ['label' => 'NIP Kasubag TU', 'rules' => 'required|min_length[0]|max_length[20]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->uptModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.update-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.update-error");
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

			if ($this->uptModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("App.delete-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.delete-error");
			}
		}

		return $this->response->setJSON($response);
	}
}