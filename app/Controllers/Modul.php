<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;
use App\Models\ModulModel;

class Modul extends BaseController
{

	protected $modulModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->modulModel = new ModulModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'modul',
			'title'     		=> 'Modul Akses'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('modul', $data);
		}
	}


	public function basic()
	{
		$db = db_connect();
		$builder = $db->table('modul')->select('id, nama_modul, controller');

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

		$result = $this->modulModel->select()->findAll();

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
				$value->nama_modul,
				$value->controller,

				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getSelect()
	{
		$modul = $this->request->getPost('modul');
		if ($modul) {
			$datamodul = $this->modulModel->select('id, nama_modul')->where('id', $modul)->findAll();
			$isidata = "";
		} else {
			$datamodul = $this->modulModel->select('id, nama_modul')->findAll();
			$isidata = "<option>--Pilih Nama Anggaran--</option>";
		}

		$data['data'] = array();

		foreach ($datamodul as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->nama_modul . '</option>';
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

			$data = $this->modulModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nama_modul'] = $this->request->getPost('nama_modul');
		$fields['controller'] = $this->request->getPost('controller');


		$this->validation->setRules([
			'nama_modul' => ['label' => 'Nama modul', 'rules' => 'required|min_length[0]|max_length[100]'],
			'controller' => ['label' => 'Controller', 'rules' => 'required|min_length[0]|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->modulModel->insert($fields)) {

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
		$fields['nama_modul'] = $this->request->getPost('nama_modul');
		$fields['controller'] = $this->request->getPost('controller');


		$this->validation->setRules([
			'nama_modul' => ['label' => 'Nama modul', 'rules' => 'required|min_length[0]|max_length[100]'],
			'controller' => ['label' => 'Controller', 'rules' => 'required|min_length[0]|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->modulModel->update($fields['id'], $fields)) {

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

			if ($this->modulModel->where('id', $id)->delete()) {

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