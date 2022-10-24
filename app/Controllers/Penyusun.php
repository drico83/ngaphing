<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\PenyusunModel;

class Penyusun extends BaseController
{

	protected $penyusunModel;
	protected $validation;
	protected $session;


	public function __construct()
	{
		$this->penyusunModel = new PenyusunModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'penyusun',
			'title'     		=> 'daftar Kode Belanja Penyusun Sub Komponen'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('penyusun', $data);
		}
	}

	public function dt()
	{
		$tahun = $this->session->get('db_tahun');
		$dt = new Datatables('sub_komponen');
		$dt->table('sub_komponen');
		$dt->where(['tahun' => $tahun]);
		return $dt->addColumn('action', function ($db) {
			$id = $db['id'];
			$btn = "<button class='btn btn-sm btn-info' onclick='add(\"$id\")' title='delete'><i class='fa fa-search'></i>Lihat Objek</button>";
			return $btn;
		})->draw();
	}

	public function getobjek()
	{
		$tahun = $this->session->get('db_tahun');
		$sub = $this->request->getPost('sub');
		$response = array();
		$data['data'] = array();
		$result = $this->penyusunModel->select('penyusun.id, kodrek.kode, kode_belanja.nama_belanja, kode_belanja.harga')
			->join('kode_belanja', 'kode_belanja.id=penyusun.id_kode_belanja')
			->join('kodrek', 'kodrek.id=kode_belanja.id_kodrek')
			->where([
				'penyusun.id_subkomponen' => $sub,
				'penyusun.tahun' => $tahun
			])
			->findAll();
		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="text-center">';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i>Hapus</button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$no++,
				$value->kode,
				$value->nama_belanja,
				$value->harga,
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

			$data = $this->penyusunModel->where('id', $id)->first();

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
		$fields['id_subkomponen'] = $this->request->getPost('idSubkomponen');
		$fields['id_kode_belanja'] = $this->request->getPost('idKodeBelanja');
		$fields['tahun'] = $tahun;
		$id_subkomponen = $this->request->getPost('idSubkomponen');
		$id_kode_belanja = $this->request->getPost('idKodeBelanja');


		$this->validation->setRules([

			'id_kode_belanja' => ['label' => 'Kode Komponen Belanja', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {
			$data = $this->penyusunModel->where(['id_subkomponen' => $id_subkomponen, 'id_kode_belanja' => $id_kode_belanja])->first();
			if ($data) {
				$response['success'] = false;
				$response['messages'] = 'Kode belanja penyusun sudah ada';
			} else {
				if ($this->penyusunModel->insert($fields)) {

					$response['success'] = true;
					$response['messages'] = 'Data has been inserted successfully';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Insertion error!';
				};
			};
		};

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_subkomponen'] = $this->request->getPost('idSubkomponen');
		$fields['id_kode_belanja'] = $this->request->getPost('idKodeBelanja');


		$this->validation->setRules([
			'id_subkomponen' => ['label' => 'Nama Sub Komponen', 'rules' => 'required|max_length[11]'],
			'id_kode_belanja' => ['label' => 'Kode Komponen Belanja', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->penyusunModel->update($fields['id'], $fields)) {

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

			if ($this->penyusunModel->where('id', $id)->delete()) {

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