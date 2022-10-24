<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;
use App\Models\Pagu2023Model;

class Pagu2023 extends BaseController
{

	protected $pagu2023Model;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->pagu2023Model = new Pagu2023Model();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'pagu2023',
			'title'     		=> 'Pagu 2023'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('pagu2023', $data);
		}
	}


	public function basic()
	{
		$db = db_connect();
		$builder = $db->table('pagu2023')->select('pagu2023.id, upt.pkm, pagu2023.pagu_ukm, pagu2023.pagu_insentif, pagu2023.pagu_manajemen, pagu2023.pagu_total')->join('upt', 'upt.id=pagu2023.id_pkm');

		return DataTable::of($builder)->addNumbering()
			->add('action', function ($row) {
				return '<div class="btn-group">
				<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-eject"></i> Aksi  </button>
				<div class="dropdown-menu">
				<a class="dropdown-item text-info" onClick="save(' . $row->id . ')"><i class="fas fa-edit"></i>   ' .  lang("App.edit")  . '</a>
				<a class="dropdown-item text-orange" ><i class="far fa-copy"></i>   ' .  lang("App.copy")  . '</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item text-danger" onClick="remove(' . $row->id . ')"><i class="fas fa-trash"></i>   ' .  lang("App.delete")  . '</a>
				</div></div>';
			}, 'last')
			->hide('id')
			->toJson();
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->pagu2023Model->select()->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id . ')"><i class="fa-solid fa-pen-to-square"></i>   ' .  lang("App.edit")  . '</a>';
			$ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id . ')"><i class="fa-solid fa-trash"></i>   ' .  lang("App.delete")  . '</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$value->id,
				$value->id_pkm,
				$value->pagu_ukm,
				$value->pagu_insentif,
				$value->pagu_manajemen,
				$value->pagu_total,

				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getSelect()
	{
		$pagu2023 = $this->request->getPost('pagu2023');
		if ($pagu2023) {
			$datapagu2023 = $this->pagu2023Model->select('id, pagu_total')->where('id', $pagu2023)->findAll();
			$isidata = "";
		} else {
			$datapagu2023 = $this->pagu2023Model->select('id, pagu_total')->findAll();
			$isidata = "<option>--Pilih Nama Anggaran--</option>";
		}

		$data['data'] = array();

		foreach ($datapagu2023 as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->pagu_total . '</option>';
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

			$data = $this->pagu2023Model->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_pkm'] = $this->request->getPost('id_pkm');
		$fields['pagu_ukm'] = $this->request->getPost('pagu_ukm');
		$fields['pagu_insentif'] = $this->request->getPost('pagu_insentif');
		$fields['pagu_manajemen'] = $this->request->getPost('pagu_manajemen');
		$fields['pagu_total'] = $this->request->getPost('pagu_total');


		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|min_length[0]|max_length[11]'],
			'pagu_ukm' => ['label' => 'Pagu UKM', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
			'pagu_insentif' => ['label' => 'Pagu Insentif', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
			'pagu_manajemen' => ['label' => 'Pagu Manajemen', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
			'pagu_total' => ['label' => 'Pagu Total', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pagu2023Model->insert($fields)) {

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
		$fields['id_pkm'] = $this->request->getPost('id_pkm');
		$fields['pagu_ukm'] = $this->request->getPost('pagu_ukm');
		$fields['pagu_insentif'] = $this->request->getPost('pagu_insentif');
		$fields['pagu_manajemen'] = $this->request->getPost('pagu_manajemen');
		$fields['pagu_total'] = $this->request->getPost('pagu_total');


		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|min_length[0]|max_length[11]'],
			'pagu_ukm' => ['label' => 'Pagu UKM', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
			'pagu_insentif' => ['label' => 'Pagu Insentif', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
			'pagu_manajemen' => ['label' => 'Pagu Manajemen', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],
			'pagu_total' => ['label' => 'Pagu Total', 'rules' => 'required|numeric|min_length[0]|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pagu2023Model->update($fields['id'], $fields)) {

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

			if ($this->pagu2023Model->where('id', $id)->delete()) {

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