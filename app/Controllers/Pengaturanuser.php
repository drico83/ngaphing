<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\PengaturanuserModel;
use App\Models\UptModel;
use App\Models\GrupModel;
use Myth\Auth\Password;


class Pengaturanuser extends BaseController
{

	protected $pengaturanuserModel;
	protected $uptModel;
	protected $grupModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->pengaturanuserModel = new PengaturanuserModel();
		$this->uptModel = new UptModel();
		$this->grupModel = new GrupModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}


	public function index()
	{
		$tahun = $this->session->get('db_tahun');

		$data = [
			'controller'    	=> 'pengaturanuser',
			'title'     		=> 'Pengaturan Pengguna',
			'request'           => \Config\Services::request()
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('pengaturanuser', $data);
		}
	}

	public function dt()
	{

		$dt = new Datatables('users'); //siswa is a table name
		$dt->select('users.id as iduser, email, username, users.name as naleng, phone, upt.pkm, active, auth_groups.name as grup');
		$dt->join('auth_groups_users', 'auth_groups_users.user_id=users.id', 'left');
		$dt->join('auth_groups', 'auth_groups.id=auth_groups_users.group_id', 'left');
		$dt->join('upt', 'upt.id=users.puskesmas', 'left');

		return $dt->addColumn('action', function ($db) {
			$id = $db['iduser'];
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>Edit</button>
			<button class='btn btn-sm btn-danger' onclick='reset(\"$id\")' title='reset'><i class='fas fa-retweet'></i>Reset Sandi</button>";
			return $btn;
		})->draw();
	}

	public function puskesmas()
	{
		$pkm = $this->request->getPost('pkm');
		$datapuskesmas = $this->uptModel->select('id, pkm')->findAll();

		$data['data'] = array();
		$isidata = "";
		foreach ($datapuskesmas as $key => $value) {
			$selected = ($pkm == $value->id) ? 'selected' : '';

			$isidata .= '	<option value="' . $value->id . '"' . $selected . '>' . $value->pkm . '</option>';
		}

		$msg = [
			'data' => $isidata
		];
		return $this->response->setJSON($msg);
	}

	public function grup()
	{
		$grup = $this->request->getPost('grup');
		$datapuskesmas = $this->grupModel->select('id, name')->findAll();

		$data['data'] = array();
		$isidata = "";
		foreach ($datapuskesmas as $key => $value) {
			$selected = ($grup == $value->id) ? 'selected' : '';

			$isidata .= '	<option value="' . $value->id . '"' . $selected . '>' . $value->name . '</option>';
		}

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

			$data = $this->pengaturanuserModel->select('users.*, auth_groups_users.group_id')->join('auth_groups_users', 'auth_groups_users.user_id=users.id', 'left')->where('users.id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['name'] = $this->request->getPost('name');
		$fields['username'] = $this->request->getPost('username');
		$fields['puskesmas'] = $this->request->getPost('puskesmas');
		$fields['phone'] = $this->request->getPost('phone');
		$fields['active'] = $this->request->getPost('active');
		$data = [

			'group_id'  => $this->request->getPost('grup'),
		];

		$this->validation->setRules([
			'name' => ['label' => 'Nama Lengkap', 'rules' => 'permit_empty|max_length[100]'],
			'username' => ['label' => 'Username', 'rules' => 'permit_empty|max_length[30]'],
			// 'puskesmas' => ['label' => 'Nama Puskesmas', 'rules' => 'permit_empty|max_length[100]'],
			'phone' => ['label' => 'Nomor Telepon', 'rules' => 'permit_empty|max_length[100]'],
			'active' => ['label' => 'Status Aktivasi', 'rules' => 'required|max_length[1]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->pengaturanuserModel->update($fields['id'], $fields)) {
				$db      = \Config\Database::connect();
				$builder = $db->table('auth_groups_users');
				$builder->update($data, ['user_id' => $fields['id']]);

				$response['success'] = true;
				$response['messages'] = 'Ubah Data Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Data Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function resetpass()
	{
		if ($this->request->isAJAX()) {
			$fields['id'] = $this->request->getPost('id');
			$fields['password_hash'] = Password::hash('123456');
			if ($this->pengaturanuserModel->update($fields['id'], $fields)) {
				$response['success'] = true;
				$response['messages'] = 'Sandi berhasil dikembalikan menjadi 123456';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Data Gagal!';
			}
			return $this->response->setJSON($response);
		}
	}
}