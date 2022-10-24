<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
// use \Hermawan\DataTables\DataTable;
use App\Models\PendapatanpModel;
use App\Models\SilpaModel;
use App\Models\Mcountdown;

class Pendapatanp extends BaseController
{

	protected $pendapatanpModel;
	protected $validation;
	protected $session;
	protected $silpa;
	protected $mcountdown;

	public function __construct()
	{
		$this->pendapatanpModel = new PendapatanpModel();
		$this->silpaModel = new SilpaModel();
		$this->validation =  \Config\Services::validation();
		$this->session =  session();
		$this->mcountdown = new Mcountdown();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$timer = $this->mcountdown->where(['link' => 'pendapatanp', 'tahun' => $tahun])->first();
		$data = [
			'controller'    	=> 'pendapatanp',
			'title'     		=> 'Pendapatan Perubahan',
			'timer'				=> $timer,
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('blud/pendapatanp', $data);
		}
	}


	public function basic()
	{
		// $db = db_connect();
		// $builder = $db->table('pendapatan_p')->select('tahun, id_pkm, umum, jampersal, kapitasi_jkn, non_kapitasi, non_kapitasi_lain');

		// return DataTable::of($builder)->addNumbering()
		// 	->add('action', function ($row) {
		// 		return '<div class="btn-group">
		// 		<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		// 		<i class="fas fa-eject"></i> Aksi  </button>
		// 		<div class="dropdown-menu">
		// 		<a class="dropdown-item text-info" onClick="save(' . $row->id . ')"><i class="fas fa-edit"></i>   ' .  lang("App.edit")  . '</a>
		// 		<a class="dropdown-item text-orange" ><i class="far fa-copy"></i>   ' .  lang("App.copy")  . '</a>
		// 		<div class="dropdown-divider"></div>
		// 		<a class="dropdown-item text-danger" onClick="remove(' . $row->id . ')"><i class="fas fa-trash"></i>   ' .  lang("App.delete")  . '</a>
		// 		</div></div>';
		// 	}, 'last')
		// 	->hide('id')
		// 	->toJson();
	}

	public function getAll()
	{
		$response = $data['data'] = array();
		$tahun = $this->session->get('db_tahun');

		$result = $this->pendapatanpModel->select('pendapatan_p.*, upt.pkm, pendapatan_m.murni')->join('upt', 'upt.id=pendapatan_p.id_pkm', 'right')->join('pendapatan_m', 'pendapatan_m.id_pkm=pendapatan_p.id_pkm', 'left')->where('pendapatan_p.tahun', $tahun)->limit(67)->find();
		$no = 1;
		foreach ($result as $key => $value) {
			if ($value->umum != null) {
				$ops = '<div class="btn-group">';
				$ops .= '<button class="btn btn-primary" onclick="save(' . $value->id . ')"><i class="fa-solid fa-pen-to-square"></i>   Edit</button>';
				$ops .= '<button class="btn btn-danger" onclick="remove(' . $value->id . ')"><i class="fa-solid fa-pen-to-square"></i>   Hapus</button>';
				$ops .= '</div>';
			} else {
				$ops = '';
			};



			$jumlah = $value->umum + $value->jampersal + $value->kapitasi_jkn + $value->non_kapitasi + $value->non_kapitasi_lain + $value->jasa_giro;
			$data['data'][$key] = array(
				$no++,

				$value->pkm,
				$value->umum,
				$value->jampersal,
				$value->kapitasi_jkn,
				$value->non_kapitasi,
				$value->non_kapitasi_lain,
				$value->jasa_giro,
				$jumlah,
				$value->murni,
				$jumlah - $value->murni,
				$value->silpa,
				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getSelect()
	{
		$pendapatan_p = $this->request->getPost('pendapatan_p');
		if ($pendapatan_p) {
			$datapendapatan_p = $this->pendapatanpModel->select('id')->where('id', $pendapatan_p)->findAll();
			$isidata = "";
		} else {
			$datapendapatan_p = $this->pendapatanpModel->select('id')->findAll();
			$isidata = "<option>--Pilih Nama Anggaran--</option>";
		}

		$data['data'] = array();

		foreach ($datapendapatan_p as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->tahun . '</option>';
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

			$data = $this->pendapatanpModel->where('id', $id)->first();

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
		$fields['tahun'] = $tahun;
		$fields['id_pkm'] = $this->request->getPost('id_pkm');
		$id_pkm = $this->request->getPost('id_pkm');
		$fields['umum'] = $this->request->getPost('umum');
		$fields['jampersal'] = $this->request->getPost('jampersal');
		$fields['kapitasi_jkn'] = $this->request->getPost('kapitasi_jkn');
		$fields['non_kapitasi'] = $this->request->getPost('non_kapitasi');
		$fields['non_kapitasi_lain'] = $this->request->getPost('non_kapitasi_lain');
		$fields['jasa_giro'] = $this->request->getPost('jasa_giro');
		$fields['silpa'] = $this->request->getPost('silpa');


		$this->validation->setRules([
			'tahun' => ['label' => 'Tahun', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[4]'],
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|min_length[0]|max_length[11]'],
			'umum' => ['label' => 'Pasien Umum', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'jampersal' => ['label' => 'Jampersal', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'kapitasi_jkn' => ['label' => 'Kapitasi JKN', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'non_kapitasi' => ['label' => 'Non kapitasi JKN', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'non_kapitasi_lain' => ['label' => 'Non kapitasi lain', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			$data = $this->pendapatanpModel->where(['id_pkm' => $id_pkm, 'tahun' => $tahun])->countAllResults();
			// var_dump($data);
			if ($data < 1) {
				if ($this->pendapatanpModel->insert($fields)) {

					$response['success'] = true;
					$response['messages'] = lang("App.insert-success");
				} else {

					$response['success'] = false;
					$response['messages'] = lang("App.insert-error");
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Data Sudah ada';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['tahun'] = $this->request->getPost('tahun');
		$fields['id_pkm'] = $this->request->getPost('id_pkm');
		$fields['umum'] = $this->request->getPost('umum');
		$fields['jampersal'] = $this->request->getPost('jampersal');
		$fields['kapitasi_jkn'] = $this->request->getPost('kapitasi_jkn');
		$fields['non_kapitasi'] = $this->request->getPost('non_kapitasi');
		$fields['non_kapitasi_lain'] = $this->request->getPost('non_kapitasi_lain');
		$fields['jasa_giro'] = $this->request->getPost('jasa_giro');
		$fields['silpa'] = $this->request->getPost('silpa');


		$this->validation->setRules([
			'tahun' => ['label' => 'Tahun', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[4]'],
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|min_length[0]|max_length[11]'],
			'umum' => ['label' => 'Pasien Umum', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'jampersal' => ['label' => 'Jampersal', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'kapitasi_jkn' => ['label' => 'Kapitasi JKN', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'non_kapitasi' => ['label' => 'Non kapitasi JKN', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],
			'non_kapitasi_lain' => ['label' => 'Non kapitasi lain', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pendapatanpModel->update($fields['id'], $fields)) {

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

			if ($this->pendapatanpModel->where('id', $id)->delete()) {

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