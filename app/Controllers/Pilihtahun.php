<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TahunModel;

class Pilihtahun extends BaseController
{

	protected $tahunModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->session = \Config\Services::session();
		$this->tahunModel = new TahunModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{
		$ops = '';
		$result = $this->tahunModel->findAll();
		foreach ($result as $key => $value) {

			$ops .= '<div class="col-lg-2 col-md-2 col-xs-2">';
			$ops .= '<div class="bg-danger" style="border-radius: 60px; width: 60px; height: 60px;margin-left: auto;margin-right: auto; cursor: pointer; border:2px solid white;">';
			$ops .= '<i class="fa fa-database text-white fa-2x" style="padding:11px 0 0 15px;" onclick="tahun(' . $value->tahun . ')"></i>';
			$ops .= '</div>';
			$ops .= '<h5 class="text-center font-medium text-primary text-shadow"><span>' . $value->tahun . '</span></h5>';
			$ops .= '</div>';
		};

		$data = [
			'controller'    	=> 'tahun',
			'title'     		=> 'Database Tahun',
			'request'           => \Config\Services::request(),
			'ops'				=> $ops
		];

		return view('pilihtahun', $data);
	}


	public function tahun()
	{


		$tahun = $this->request->getPost('id');

		if ($this->validation->check($tahun, 'required|numeric')) {

			$this->session->set('db_tahun', $tahun);
			$data['tahun'] = $tahun;

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
}