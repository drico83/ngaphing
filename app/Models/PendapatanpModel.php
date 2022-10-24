<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;

use CodeIgniter\Model;

class PendapatanpModel extends Model
{

	protected $table = 'pendapatan_p';
	protected $primaryKey = 'id';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['tahun', 'id_pkm', 'umum', 'jampersal', 'kapitasi_jkn', 'non_kapitasi', 'non_kapitasi_lain', 'jasa_giro', 'silpa'];
	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;
}