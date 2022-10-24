<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;

use CodeIgniter\Model;

class RukModel extends Model
{

    protected $table = 'ruk';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_menu', 'tujuan', 'sasaran', 'target', 'tgjawab', 'sumberdaya', 'mitra', 'waktu', 'indikator', 'id_pkm', 'keterangan', 'catatan', 'status', 'tahun', 'user_id'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}