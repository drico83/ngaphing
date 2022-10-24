<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{

	protected $table = 'pengajuan';
	protected $primaryKey = 'id';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['id_pkm', 'keterangan', 'no_pengajuan', 'tgl_pengajuan', 'no_spp', 'tgl_spp', 'no_spm', 'tgl_spm', 'verifikator', 'nip_verifikator', 'bulan_awal', 'bulan_akhir', 'nilai', 'tahun'];
	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;
}