<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;
use App\Models\TimelineModel;

class Timeline extends BaseController
{

	protected $timelineModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->timelineModel = new TimelineModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{

		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'timeline',
			'title'     		=> 'Jadwal Input'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('timeline', $data);
		}
	}


	public function basic()
	{
		$tahun = $this->session->get('db_tahun');
		$db = db_connect();
		$builder = $db->table('timeline')->select('id, tahun, modul, link, waktu')->where('tahun', $tahun);

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

		$result = $this->timelineModel->select()->findAll();

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
				$value->tahun,
				$value->modul,
				$value->link,
				$value->waktu,

				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getSelect()
	{
		$timeline = $this->request->getPost('timeline');
		if ($timeline) {
			$datatimeline = $this->timelineModel->select('id, tahun, modul, link, waktu')->where('id', $timeline)->findAll();
			$isidata = "";
		} else {
			$datatimeline = $this->timelineModel->select('id, tahun, modul, link, waktu')->findAll();
			$isidata = "<option>--Pilih Nama Anggaran--</option>";
		}

		$data['data'] = array();

		foreach ($datatimeline as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->modul . '</option>';
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

			$data = $this->timelineModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['tahun'] = $this->request->getPost('tahun');
		$fields['modul'] = $this->request->getPost('modul');
		$fields['link'] = $this->request->getPost('link');
		$fields['waktu'] = $this->request->getPost('waktu');


		$this->validation->setRules([
			'tahun' => ['label' => 'Tahun', 'rules' => 'required|numeric|min_length[0]|max_length[4]'],
			'modul' => ['label' => 'Modul', 'rules' => 'required|min_length[0]|max_length[255]'],
			'link' => ['label' => 'Link', 'rules' => 'required|min_length[0]|max_length[255]'],
			'waktu' => ['label' => 'Waktu', 'rules' => 'required|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->timelineModel->insert($fields)) {

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
		$fields['tahun'] = $this->request->getPost('tahun');
		$fields['modul'] = $this->request->getPost('modul');
		$fields['link'] = $this->request->getPost('link');
		$fields['waktu'] = $this->request->getPost('waktu');


		$this->validation->setRules([
			'tahun' => ['label' => 'Tahun', 'rules' => 'required|numeric|min_length[0]|max_length[4]'],
			'modul' => ['label' => 'Modul', 'rules' => 'required|min_length[0]|max_length[255]'],
			'link' => ['label' => 'Link', 'rules' => 'required|min_length[0]|max_length[255]'],
			'waktu' => ['label' => 'Waktu', 'rules' => 'required|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->timelineModel->update($fields['id'], $fields)) {

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

			if ($this->timelineModel->where('id', $id)->delete()) {

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