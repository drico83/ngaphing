<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PesertaModel;

class Peserta extends BaseController
{

	protected $pesertaModel;
	protected $validation;

	public function __construct()
	{
		$this->pesertaModel = new PesertaModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'peserta',
			'title'     		=> 'Daftar Peserta Undangan Desk Offline'
		];

		return view('peserta', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->pesertaModel->select('peserta.*, upt.pkm')->join('upt', 'upt.id=peserta.id_pkm')->findAll();
		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if(in_groups('admin')){
			$ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i>Edit</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i>Hapus</button>';
			};
			$ops .= '</div>';

			$data['data'][$key] = array(
				$no++,
				$value->nama,
				$value->pkm,
				$value->nip,
				$value->jabatan,
				$value->surtug,
                $ops,
                
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->pesertaModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_pkm'] = $this->request->getPost('idPkm');
		$fields['nama'] = $this->request->getPost('nama');
		$fields['nip'] = $this->request->getPost('nip');
		$fields['jabatan'] = $this->request->getPost('jabatan');



		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|is_unique[peserta.id_pkm]'],
			'nama' => ['label' => 'Nama Peserta', 'rules' => 'required|max_length[100]'],
			'nip' => ['label' => 'NIP / NIK', 'rules' => 'required|max_length[20]'],
			'jabatan' => ['label' => 'Jabatan', 'rules' => 'required|max_length[100]'],


		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->pesertaModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Tambah Peserta Undangan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Tambah Peserta Undangan Gagal!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_pkm'] = $this->request->getPost('idPkm');
		$fields['nama'] = $this->request->getPost('nama');
		$fields['nip'] = $this->request->getPost('nip');
		$fields['jabatan'] = $this->request->getPost('jabatan');


		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|max_length[11]'],
			'nama' => ['label' => 'Nama Peserta', 'rules' => 'required|max_length[100]'],
			'nip' => ['label' => 'NIP / NIK', 'rules' => 'required|max_length[20]'],
			'jabatan' => ['label' => 'Jabatan', 'rules' => 'required|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->pesertaModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ubah Peserta Undangan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Ubah Peserta Undangan Gagal!';
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

			if ($this->pesertaModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Hapus Peserta Undangan Berhasil';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Hapus Peserta Undangan gagal!';
			}
		}

		return $this->response->setJSON($response);
	}
}