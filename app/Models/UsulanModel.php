<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;

use CodeIgniter\Model;

class UsulanModel extends Model
{

    protected $table = 'usul_belanja';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_pkm', 'id_program', 'id_sub', 'id_belanja', 'vol1', 'sat1', 'vol2', 'sat2', 'vol3', 'sat3', 'vol4', 'sat4', 'vol_total', 'harga_total', 'keterangan', 'tahun'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}