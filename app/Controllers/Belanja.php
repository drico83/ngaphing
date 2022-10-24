<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BelanjaModel;
use App\Models\PendapatanpModel;
use App\Models\Mcountdown;

class Belanja extends BaseController
{

	protected $pendapatanpModel;
	protected $belanjaModel;
	protected $validation;
	protected $session;
	protected $mcountdown;

	public function __construct()
	{
		$this->pendapatanpModel = new PendapatanpModel();
		$this->belanjaModel = new BelanjaModel();
		$this->validation =  \Config\Services::validation();
		$this->session = session();
		$this->mcountdown = new Mcountdown();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$timer = $this->mcountdown->where(['link' => 'belanja', 'tahun' => $tahun])->first();
		$data = [
			'controller'    	=> 'belanja',
			'title'     		=> 'proyeksi Belanja BLUD Tahun 2022',
			'timer'				=> $timer,
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('blud/belanja', $data);
		}
	}

	public function jumlahpendapatan()
	{
		$id_pkm = $this->request->getPost('id_pkm');
		$tahun = $this->session->get('db_tahun');
		$result = $this->pendapatanpModel->where(['id_pkm' => $id_pkm, 'tahun' => $tahun])->first();
		if ($result == null) {
			$data['jumlah'] = 0;
		} else {
			$data['jumlah'] = $result->umum + $result->jampersal + $result->kapitasi_jkn + $result->non_kapitasi + $result->non_kapitasi_lain + $result->jasa_giro + $result->silpa;
		}
		return $this->response->setJSON($data);
	}

	public function getAll()
	{
		$response = array();
		$tahun = $this->session->get('db_tahun');
		$data['data'] = array();
		$tahun = $this->session->get('db_tahun');
		// $result = $this->pendapatanpModel->select('pendapatan_p.*, upt.pkm')->join('upt', 'upt.id=pendapatan_p.id_pkm', 'right')->limit(67)->find();
		$result = $this->belanjaModel->select('belanja_p.id, id_pkm, b_pegawai, b_barjas, modal_tanah, modal_bangunan, modal_alat, modal_lain, upt.pkm')->join('upt', 'upt.id=belanja_p.id_pkm', 'right')->where('belanja_p.tahun', $tahun)->findAll();
		$no = 1;
		foreach ($result as $key => $value) {
			$pendapatan = $this->pendapatanpModel->where(['id_pkm' => $value->id_pkm, 'tahun' => $tahun])->first();
			// var_dump($belanja);
			if ($pendapatan == null) {

				$jumlahpendapatan = 0;
				$ops = '';
			} else {
				$umum = $pendapatan->umum;
				$jampersal = $pendapatan->jampersal;
				$kapitasi_jkn = $pendapatan->kapitasi_jkn;
				$non_kapitasi = $pendapatan->non_kapitasi;
				$non_kapitasi_lain = $pendapatan->non_kapitasi_lain;
				$jasa_giro = $pendapatan->jasa_giro;
				$ops = '<div class="btn-group">';
				$ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i></button>';
				$ops .= '</div>';
				$jumlahpendapatan = $umum + $jampersal + $kapitasi_jkn + $non_kapitasi + $non_kapitasi_lain + $jasa_giro;
			}


			$data['data'][$key] = array(
				$no++,
				$value->pkm,
				$jumlahpendapatan,
				$value->b_pegawai,
				$value->b_barjas,
				$value->modal_tanah,
				$value->modal_bangunan,
				$value->modal_alat,
				$value->modal_lain,
				0,
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

			$data = $this->belanjaModel->where('id', $id)->first();

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
		$fields['id_pkm'] = $this->request->getPost('idPkm');
		$pkm = $this->request->getPost('idPkm');
		$fields['b_pegawai'] = $this->request->getPost('bPegawai');
		$fields['b_barjas'] = $this->request->getPost('bBarjas');
		$fields['modal_tanah'] = $this->request->getPost('modalTanah');
		$fields['modal_bangunan'] = $this->request->getPost('modalBangunan');
		$fields['modal_alat'] = $this->request->getPost('modalAlat');
		$fields['modal_lain'] = $this->request->getPost('modalLain');
		$fields['tahun'] = $tahun;


		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|max_length[11]'],
			'b_pegawai' => ['label' => 'Belanja Pegawai', 'rules' => 'required|numeric|max_length[11]'],
			'b_barjas' => ['label' => 'Belanja Barang dan Jasa', 'rules' => 'required|numeric|max_length[11]'],
			'modal_tanah' => ['label' => 'Belanja Modal Tanah', 'rules' => 'required|numeric|max_length[11]'],
			'modal_bangunan' => ['label' => 'Belanja Modal Gedung dan Bangunan', 'rules' => 'required|numeric|max_length[11]'],
			'modal_alat' => ['label' => 'belanja Modal Modal Peralatan dan Mesin', 'rules' => 'required|numeric|max_length[11]'],
			'modal_lain' => ['label' => 'Belanja Modal lainnya', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			$data = $this->belanjaModel->where(['id_pkm' => $pkm, 'tahun' => $tahun])->countAllResults();
			// var_dump($data);
			if ($data >= 1) {
				$response['success'] = false;
				$response['messages'] = 'Data Sudah ditambahkan, silahkan edit';
			} else {
				if ($this->belanjaModel->insert($fields)) {

					$response['success'] = true;
					$response['messages'] = 'Proyeksi belanja berhasil ditambahkan';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Proyeksi belanja gagal ditambahkan!';
				}
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_pkm'] = $this->request->getPost('idPkm');
		$fields['b_pegawai'] = $this->request->getPost('bPegawai');
		$fields['b_barjas'] = $this->request->getPost('bBarjas');
		$fields['modal_tanah'] = $this->request->getPost('modalTanah');
		$fields['modal_bangunan'] = $this->request->getPost('modalBangunan');
		$fields['modal_alat'] = $this->request->getPost('modalAlat');
		$fields['modal_lain'] = $this->request->getPost('modalLain');


		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|max_length[11]'],
			'b_pegawai' => ['label' => 'Belanja Pegawai', 'rules' => 'required|numeric|max_length[11]'],
			'b_barjas' => ['label' => 'Belanja Barang dan Jasa', 'rules' => 'required|numeric|max_length[11]'],
			'modal_tanah' => ['label' => 'Belanja Modal Tanah', 'rules' => 'required|numeric|max_length[11]'],
			'modal_bangunan' => ['label' => 'Belanja Modal Gedung dan Bangunan', 'rules' => 'required|numeric|max_length[11]'],
			'modal_alat' => ['label' => 'belanja Modal Modal Peralatan dan Mesin', 'rules' => 'required|numeric|max_length[11]'],
			'modal_lain' => ['label' => 'Belanja Modal lainnya', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->belanjaModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Proyeksi belanja berhasil diubah';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Proyeksi belanja gagal diubah!';
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

			if ($this->belanjaModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Proyeksi belanja berhasil dihapus';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Proyeksi belanja gagal dihapus!';
			}
		}

		return $this->response->setJSON($response);
	}
}