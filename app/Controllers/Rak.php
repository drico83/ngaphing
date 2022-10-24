<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\RakModel;


class Rak extends BaseController
{

	protected $rakModel;
	protected $validation;

	public function __construct()
	{
		$this->rakModel = new RakModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$title = $this->JudulModel->where('id', 1)->first();
		$data = [
			'controller'    	=> 'rak',
			'title'     		=> $title,

		];

		return view('rak', $data);
	}




	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->rakModel->select('id, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, jrak')->findAll();

		foreach ($result as $key => $value) {


			$ops  = '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i></button>';


			$data['data'][$key] = array(
				$value->id,
				$value->b1,
				$value->b2,
				$value->b3,
				$value->b4,
				$value->b5,
				$value->b6,
				$value->b7,
				$value->b8,
				$value->b9,
				$value->b10,
				$value->b11,
				$value->b12,
				$value->jrak,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getAll2()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->rakModel->get_rak_pkm();

		foreach ($result as $key => $value) {


			$ops  = '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i></button>';


			$data['data'][$key] = array(
				$value->id,
				$value->b1,
				$value->b2,
				$value->b3,
				$value->b4,
				$value->b5,
				$value->b6,
				$value->b7,
				$value->b8,
				$value->b9,
				$value->b10,
				$value->b11,
				$value->b12,
				$value->jrak,

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

			$data = $this->rakModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['b1'] = $this->request->getPost('b1');
		$fields['b2'] = $this->request->getPost('b2');
		$fields['b3'] = $this->request->getPost('b3');
		$fields['b4'] = $this->request->getPost('b4');
		$fields['b5'] = $this->request->getPost('b5');
		$fields['b6'] = $this->request->getPost('b6');
		$fields['b7'] = $this->request->getPost('b7');
		$fields['b8'] = $this->request->getPost('b8');
		$fields['b9'] = $this->request->getPost('b9');
		$fields['b10'] = $this->request->getPost('b10');
		$fields['b11'] = $this->request->getPost('b11');
		$fields['b12'] = $this->request->getPost('b12');
		$fields['jrak'] = $this->request->getPost('jrak');


		$this->validation->setRules([
			'b1' => ['label' => 'B1', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b2' => ['label' => 'B2', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b3' => ['label' => 'B3', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b4' => ['label' => 'B4', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b5' => ['label' => 'B5', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b6' => ['label' => 'B6', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b7' => ['label' => 'B7', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b8' => ['label' => 'B8', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b9' => ['label' => 'B9', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b10' => ['label' => 'B10', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b11' => ['label' => 'B11', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b12' => ['label' => 'B12', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'jrak' => ['label' => 'Jrak', 'rules' => 'permit_empty|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->rakModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Data has been inserted successfully';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Insertion error!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['b1'] = $this->request->getPost('b1');
		$fields['b2'] = $this->request->getPost('b2');
		$fields['b3'] = $this->request->getPost('b3');
		$fields['b4'] = $this->request->getPost('b4');
		$fields['b5'] = $this->request->getPost('b5');
		$fields['b6'] = $this->request->getPost('b6');
		$fields['b7'] = $this->request->getPost('b7');
		$fields['b8'] = $this->request->getPost('b8');
		$fields['b9'] = $this->request->getPost('b9');
		$fields['b10'] = $this->request->getPost('b10');
		$fields['b11'] = $this->request->getPost('b11');
		$fields['b12'] = $this->request->getPost('b12');
		$fields['jrak'] = $this->request->getPost('jrak');


		$this->validation->setRules([
			'b1' => ['label' => 'B1', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b2' => ['label' => 'B2', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b3' => ['label' => 'B3', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b4' => ['label' => 'B4', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b5' => ['label' => 'B5', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b6' => ['label' => 'B6', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b7' => ['label' => 'B7', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b8' => ['label' => 'B8', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b9' => ['label' => 'B9', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b10' => ['label' => 'B10', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b11' => ['label' => 'B11', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'b12' => ['label' => 'B12', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'jrak' => ['label' => 'Jrak', 'rules' => 'permit_empty|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->rakModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'RAK sudah diinput';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Input RAK gagal!';
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

			if ($this->rakModel->where('id', $id)->delete()) {

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