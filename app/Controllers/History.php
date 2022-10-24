<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\HistoryModel;

class History extends BaseController
{
	
    protected $historyModel;
    protected $validation;
	
	public function __construct()
	{
	    $this->historyModel = new HistoryModel();
       	$this->validation =  \Config\Services::validation();
		
	}
	
	public function index()
	{

	    $data = [
                'controller'    	=> 'history',
                'title'     		=> 'Daftar Histrory'				
			];
		
		return view('history', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->historyModel->select('id, id_pkm, id_prog, deskripsi, created_at')->findAll();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit('. $value->id .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove('. $value->id .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->id,
				$value->id_pkm,
				$value->id_prog,
				$value->deskripsi,
				$value->created_at,

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
			
			$data = $this->sourcesModel->where('id' ,$id)->first();

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
        $fields['id_prog'] = $this->request->getPost('idProg');
        $fields['deskripsi'] = $this->request->getPost('deskripsi');
        $fields['created_at'] = $this->request->getPost('createdAt');


        $this->validation->setRules([
            'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|numeric|max_length[11]'],
            'id_prog' => ['label' => 'Nama Program', 'rules' => 'required|numeric|max_length[11]'],
            'deskripsi' => ['label' => 'Deskripsi', 'rules' => 'required'],
            'created_at' => ['label' => 'Tanggal', 'rules' => 'required|valid_date'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->historyModel->insert($fields)) {
												
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
        $fields['id_pkm'] = $this->request->getPost('idPkm');
        $fields['id_prog'] = $this->request->getPost('idProg');
        $fields['deskripsi'] = $this->request->getPost('deskripsi');
        $fields['created_at'] = $this->request->getPost('createdAt');


        $this->validation->setRules([
            'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|numeric|max_length[11]'],
            'id_prog' => ['label' => 'Nama Program', 'rules' => 'required|numeric|max_length[11]'],
            'deskripsi' => ['label' => 'Deskripsi', 'rules' => 'required'],
            'created_at' => ['label' => 'Tanggal', 'rules' => 'required|valid_date'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->historyModel->update($fields['id'], $fields)) {
				
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
		
			if ($this->historyModel->where('id', $id)->delete()) {
								
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