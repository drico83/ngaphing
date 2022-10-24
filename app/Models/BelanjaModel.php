<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;

use CodeIgniter\Model;

class BelanjaModel extends Model
{

	protected $table = 'belanja_p';
	protected $primaryKey = 'id';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['id_pkm', 'b_pegawai', 'b_barjas', 'modal_tanah', 'modal_bangunan', 'modal_alat', 'modal_lain', 'tahun'];
	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	function get_belanja_pkm()
	{
		return $this->select('belanja_p.id as id, belanja_p.id_pkm as id_pkm, c.silpa as silpa, belanja_p.b_pegawai as b_pegawai, belanja_p.b_barjas as b_barjas, belanja_p.modal_tanah as modal_tanah, 
		belanja_p.modal_bangunan as modal_bangunan, belanja_p.modal_alat as modal_alat, belanja_p.modal_lain as modal_lain, belanja_p.created_at as created_at, belanja_p.updated_at as updated_at, b.pkm as pkm,
		(d.umum+d.jampersal+d.kapitasi_jkn+d.non_kapitasi+d.non_kapitasi_lain+d.jasa_giro) as pendapat')
			->join('puskesmas as b', 'belanja_p.id_pkm = b.id')
			->join('silpa as c', 'belanja_p.id_pkm = c.id_pkm')
			->join('pendapatan_p as d', 'belanja_p.id_pkm = d.id_pkm')
			->where('belanja_p.tahun', 2021)
			->findAll();
	}

	function get_belanja_pkm1($id)
	{
		return $this->select('belanja_p.id as id, belanja_p.id_pkm as id_pkm, belanja_p.b_pegawai as b_pegawai, belanja_p.b_barjas as b_barjas, belanja_p.modal_tanah as modal_tanah, 
		belanja_p.modal_bangunan as modal_bangunan, belanja_p.modal_alat as modal_alat, belanja_p.modal_lain as modal_lain, belanja_p.created_at as created_at, belanja_p.updated_at as updated_at, b.pkm as pkm')
			->join('puskesmas as b', 'belanja_p.id_pkm = b.id')
			->where([
				'belanja_p.id' => $id,
				'belanja_p.tahun' => 2021
			])
			->first();
	}

	function get_belanja_total()
	{
		return $this->select('sum(belanja_p.b_pegawai + belanja_p.b_barjas + belanja_p.modal_tanah + 
		belanja_p.modal_bangunan + belanja_p.modal_alat + belanja_p.modal_lain) as total_belanja')
			->where('belanja_p.tahun', 2021)
			->first();
	}
}