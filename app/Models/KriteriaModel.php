<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * KriteriaModel mengelola data kriteria penilaian.
 * Terdapat dua tipe kriteria: Benefit (B) dan Cost (C).
 */
class KriteriaModel extends Model
{
    protected $table            = 'kriteria';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // Soft delete digunakan untuk keamanan agar data tidak hilang permanen
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_kriteria', 'bobot', 'tipe'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Aturan validasi input kriteria
    protected $validationRules      = [
        'nama_kriteria' => 'required|min_length[3]|max_length[100]',
        // Bobot harus antara 0 dan 1 (representasi persentase)
        'bobot'         => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[1]',
        // Tipe harus B (Benefit) atau C (Cost)
        'tipe'          => 'required|in_list[B,C]',
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
}
