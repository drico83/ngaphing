<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\PengaturanuserModel;
use App\Models\UptModel;
use App\Models\GrupModel;
use Myth\Auth\Password;


class Profil extends BaseController
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
			'controller'    	=> 'profil',
			'title'     		=> 'Pengaturan Pengguna',
			'request'           => \Config\Services::request()
		];
		$getloc = json_decode(file_get_contents("http://ipinfo.io/"));
		$coordinates = explode(",", $getloc->loc); // -> '32,-72' becomes'32','-72'
		$data['lat'] = $coordinates[0]; // latitude
		$data['long'] = $coordinates[1]; // longitude
		$data['kota'] = $getloc->city; // longitude
		$datapuskesmas = $this->uptModel->where('id', user()->puskesmas)->first();
		$data['puskesmas'] = $datapuskesmas->pkm; // longitude
		$user = $this->pengaturanuserModel->select('users.id as iduser, email, username, users.name as naleng,avatar, phone, active, auth_groups.name as grup')->join('auth_groups_users', 'auth_groups_users.user_id=users.id', 'left')->join('auth_groups', 'auth_groups.id=auth_groups_users.group_id', 'left')->where('users.id', user_id())->first();
		$data['grup'] = $user->grup;
		$data['avatar'] = $user->avatar;
		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('profil', $data);
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
			$btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i></button>";
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
		$fields['email'] = $this->request->getPost('email');
		$fields['phone'] = $this->request->getPost('phone');
		// $fields['active'] = $this->request->getPost('active');


		$this->validation->setRules([
			'name' => ['label' => 'Nama Lengkap', 'rules' => 'permit_empty|max_length[100]'],
			'username' => ['label' => 'Username', 'rules' => 'permit_empty|max_length[30]'],
			'email' => ['label' => 'Email', 'rules' => 'required|valid_email|is_unique[users.email,id,{id}]'],
			'phone' => ['label' => 'Nomor Telepon', 'rules' => 'permit_empty|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			session()->setFlashdata('pesan', $this->validation->listErrors());
		} else {

			if ($this->pengaturanuserModel->update($fields['id'], $fields)) {
				session()->setFlashdata('pesan', 'Ubah Profil Berhasil');
			} else {

				session()->setFlashdata('pesan', 'Ubah Profil Gagal');
			}
		}

		return redirect()->to('/profil');
	}

	public function editpass()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['password_hash'] = $this->request->getPost('password_hash');
		$password_hash = $this->request->getPost('password_hash');
		$fields['repassword'] = $this->request->getPost('repassword');



		$this->validation->setRules([
			'password_hash' => ['label' => 'Password', 'rules' => 'required|min_length[6]'],
			'repassword' => ['label' => 'Ulangi Password', 'rules' => 'required|matches[password_hash]'],


		]);

		if ($this->validation->run($fields) == FALSE) {

			session()->setFlashdata('pesan', $this->validation->listErrors());
		} else {

			if ($this->pengaturanuserModel->update($fields['id'], ['password_hash' => Password::hash($password_hash)])) {
				session()->setFlashdata('pesan', 'Ubah Password Berhasil');
			} else {

				session()->setFlashdata('pesan', 'Ubah Password Gagal');
			}
		}

		return redirect()->to('/profil');
	}

	public function upload()
	{
		if ($this->request->isAJAX()) {

			$rules = [

				"avatar" => [
					"rules" => "uploaded[avatar]|max_size[avatar,1024]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/gif,image/png]",
					"label" => "Profile Image",
				],
			];

			if (!$this->validate($rules)) {

				$response = [
					'success' => false,
					'messages' => "File tidak support",
				];

				return $this->response->setJSON($response);
			} else {

				$file = $this->request->getFile('avatar');

				$profile_image = $file->getName();

				// Renaming file before upload
				$temp = explode(".", $profile_image);
				$newfilename = round(microtime(true)) . '.' . end($temp);
				$fields['id'] = $this->request->getPost('id');
				$fields['avatar'] = $newfilename;
				$cari = $this->pengaturanuserModel->find($fields['id']);
				if ($this->pengaturanuserModel->update($fields['id'], $fields)) {

					if ($cari->avatar != 'avatar.png') {
						unlink('assets/images/users/' . $cari->avatar);
					}
					if ($file->move("assets/images/users", $newfilename)) {

						$response = [
							'success' => true,
							'messages' => "Ganti Profil berhasil",
						];
					} else {

						$response = [
							'success' => false,
							'messages' => "Ganti Profil gagal",
						];
					};
					return $this->response->setJSON($response);
				} else {

					$response = [
						'success' => false,
						'messages' => "Ganti Profil berhasil",
					];

					return $this->response->setJSON($response);
				}
			}
		}
	}
}