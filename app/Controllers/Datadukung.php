<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\DatadukungModel;
use App\Models\UptModel;

class Datadukung extends BaseController
{

	protected $datadukungModel;
	protected $uptModel;
	protected $validation;

	public function __construct()
	{
		$this->datadukungModel = new DatadukungModel();
		$this->uptModel = new UptModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'datadukung',
			'title'     		=> 'Upload Data Dukung'
		];

		return view('datadukung', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		// $result = $this->datadukungModel->select('id, id_pkm, surat, sptj, kak, rab')->findAll();
		$result = $this->uptModel->select('data_dukung.id, upt.id as id_pkm, data_dukung.surat, data_dukung.sptj, data_dukung.kak, data_dukung.rab, upt.pkm')
			->join('data_dukung', 'data_dukung.id_pkm=upt.id', 'left')->findAll();
		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->surat == null || $value->surat == "") {
				$ops .= '	<button type="button" class="btn btn-sm btn-success" onclick="add(' . $value->id_pkm . ')"><i class="fa fa-save"></i>Upload Dakung</button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="add(' . $value->id_pkm . ')"><i class="fa fa-edit"></i>Edit Dakung</button>';
			};

			$ops .= '</div>';
			$srt = '<a href="assets/upload/' . $value->surat . '" target="_blank">' . $value->surat . '</a>';
			$sp = '<a href="assets/upload/' . $value->sptj . '" target="_blank">' . $value->sptj . '</a>';
			$ka = '<a href="assets/upload/' . $value->kak . '" target="_blank">' . $value->kak . '</a>';
			$ra = '<a href="assets/upload/' . $value->rab . '" target="_blank">' . $value->rab . '</a>';
			$data['data'][$key] = array(
				$no++,
				$value->pkm,
				$srt,
				$sp,
				$ka,
				$ra,

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

			$data = $this->sourcesModel->where('id', $id)->first();

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
		$fields['sptj'] = $this->request->getPost('sptj');
		$fields['kak'] = $this->request->getPost('kak');
		$fields['rab'] = $this->request->getPost('rab');
		$surat = $this->request->getFile('surat');
		$sptj = $this->request->getFile('sptj');
		$kak = $this->request->getFile('kak');
		$rab = $this->request->getFile('rab');
		$id_pkm = $this->request->getPost('idPkm');
		$pkm = $this->uptModel->where('id', $id_pkm)->first();

		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|numeric|max_length[11]'],
			'sptj' => ['label' => 'Surat Pernyataan Tanggung Jawab', 'rules' => 'uploaded[surat]|ext_in[surat,pdf]', 'errors' => ['uploaded' => 'File {field} Harus Diupload', 'ext_in' => 'File {field} harus berbentuk Pdf']],
			'kak' => ['label' => 'Kerangka Acuan Kegiatan', 'rules' => 'uploaded[surat]|ext_in[surat,pdf]', 'errors' => ['uploaded' => 'File {field} Harus Diupload', 'ext_in' => 'File {field} harus berbentuk Pdf']],
			'rab' => ['label' => 'Rencana Anggaran Biaya', 'rules' => 'uploaded[surat]|ext_in[surat,pdf]', 'errors' => ['uploaded' => 'File {field} Harus Diupload', 'ext_in' => 'File {field} harus berbentuk Pdf']],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {
			$dataupload = $this->datadukungModel->where('id', $id_pkm)->first();

			if ($dataupload != null || $dataupload != "") {
				$xsurat = './assets/upload/' .  $dataupload->surat;
				$xsptj = './assets/upload/' .  $dataupload->sptj;
				$xkak = './assets/upload/' .  $dataupload->kak;
				$xrab = './assets/upload/' .  $dataupload->rab;
				unlink($xsurat);
				unlink($xsptj);
				unlink($xkak);
				unlink($xrab);
			};
			if ($surat->move('assets/upload', 'surat-' . $pkm->pkm . '.' . $surat->getExtension())) {
				$sptj->move('assets/upload', 'sptj-' . $pkm->pkm . '.' . $sptj->getExtension());
				$kak->move('assets/upload', 'kak-' . $pkm->pkm . '.' . $kak->getExtension());
				$rab->move('assets/upload', 'rab-' . $pkm->pkm . '.' . $rab->getExtension());
				$fields['surat'] = $surat->getName();
				$fields['sptj'] = $sptj->getName();
				$fields['kak'] = $kak->getName();
				$fields['rab'] = $rab->getName();
				$dataupload = $this->datadukungModel->where('id_pkm', $id_pkm)->first();
				if ($dataupload == null || $dataupload == "") {
					$this->datadukungModel->insert($fields);
				};
				$response['success'] = true;
				$response['messages'] = 'File sudah di Upload';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Upload File Gagal!';
			};
		};

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['id_pkm'] = $this->request->getPost('idPkm');
		$fields['surat'] = $this->request->getPost('surat');
		$fields['sptj'] = $this->request->getPost('sptj');
		$fields['kak'] = $this->request->getPost('kak');
		$fields['rab'] = $this->request->getPost('rab');


		$this->validation->setRules([
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|numeric|max_length[11]'],
			'surat' => ['label' => 'Surat Usulan', 'rules' => 'required|max_length[100]'],
			'sptj' => ['label' => 'Surat Pernyataan Tanggung Jawab', 'rules' => 'required|max_length[100]'],
			'kak' => ['label' => 'Kerangka Acuan Kegiatan', 'rules' => 'required|max_length[100]'],
			'rab' => ['label' => 'Rencana Anggaran Biaya', 'rules' => 'required|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->datadukungModel->update($fields['id'], $fields)) {

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

			if ($this->datadukungModel->where('id', $id)->delete()) {

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