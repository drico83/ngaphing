<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;

use CodeIgniter\Model;

class SilpaModel extends Model
{

	protected $table = 'silpa';
	protected $primaryKey = 'id';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['id_pkm', 'tahun', 'silpa'];
	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	function get_silpa_pkm()
	{
		return $this->select('silpa.id as id, silpa.id_pkm as id_pkm, silpa.silpa as silpa, silpa.created_at as created_at, silpa.updated_at as updated_at, b.pkm as pkm')
			->join('puskesmas as b', 'silpa.id_pkm = b.id')
			->findAll();
	}

	function get_silpa_pkm1($id)
	{
		return $this->select('silpa.id as id, silpa.id_pkm as id_pkm, silpa.silpa as silpa, silpa.created_at as created_at, silpa.updated_at as updated_at, b.pkm as pkm')
			->join('puskesmas as b', 'silpa.id_pkm = b.id')
			->where('silpa.id', $id)
			->first();
	}
	function get_silpa_total()
	{
		return $this->select('sum(silpa.silpa) as total_silpa')
			->first();
	}
}