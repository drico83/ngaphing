<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ObjekbelanjaModel;

class Objekbelanja extends BaseController
{
	
    protected $objekbelanjaModel;
    protected $validation;
	
	public function __construct()
	{
	    $this->objekbelanjaModel = new ObjekbelanjaModel();
       	$this->validation =  \Config\Services::validation();
		
	}
	
	public function index()
	{

	    $data = [
                'controller'    	=> 'objekbelanja',
                'title'     		=> 'Daftar Objek Belanja'				
			];
		
		return view('objekbelanja', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->objekbelanjaModel->select('id, kode_rekening, nama_objek')->findAll();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit('. $value->id .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove('. $value->id .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->id,
				$value->kode_rekening,
				$value->nama_objek,

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
        $fields['kode_rekening'] = $this->request->getPost('kodeRekening');
        $fields['nama_objek'] = $this->request->getPost('namaObjek');


        $this->validation->setRules([
            'kode_rekening' => ['label' => 'Kode Rekening', 'rules' => 'required|max_length[255]'],
            'nama_objek' => ['label' => 'Nama Objek', 'rules' => 'required|max_length[255]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->objekbelanjaModel->insert($fields)) {
												
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
        $fields['kode_rekening'] = $this->request->getPost('kodeRekening');
        $fields['nama_objek'] = $this->request->getPost('namaObjek');


        $this->validation->setRules([
            'kode_rekening' => ['label' => 'Kode Rekening', 'rules' => 'required|max_length[255]'],
            'nama_objek' => ['label' => 'Nama Objek', 'rules' => 'required|max_length[255]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->objekbelanjaModel->update($fields['id'], $fields)) {
				
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
		
			if ($this->objekbelanjaModel->where('id', $id)->delete()) {
								
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