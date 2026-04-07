<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilModel extends Model
{
    public const PASSING_LIMIT = 10;

    protected $table            = 'hasil';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['mahasiswa_id', 'nilai_preferensi', 'ranking'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'mahasiswa_id'     => 'required|is_not_unique[mahasiswa.id]',
        'nilai_preferensi' => 'required|numeric',
        'ranking'          => 'required|integer',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get results with ranking and student info
     *
     * @return array
     */
    public function getRankedResults()
    {
        return $this->select('hasil.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.prodi')
                    ->join('mahasiswa', 'mahasiswa.id = hasil.mahasiswa_id')
                    ->orderBy('ranking', 'ASC')
                    ->findAll();
    }
}
