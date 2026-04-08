<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * MahasiswaModel mengelola data identitas mahasiswa.
 */
class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // Soft delete untuk mencegah kehilangan data jika tidak sengaja terhapus
    protected $protectFields    = true;
    protected $allowedFields    = ['nim', 'nama', 'prodi'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Aturan validasi input data mahasiswa
    protected $validationRules      = [
        'id'    => 'permit_empty|is_natural_no_zero',
        // NIM harus unik (kecuali untuk record yang sedang diedit), berupa angka, dan panjangnya sesuai standar
        'nim'   => 'required|is_unique[mahasiswa.nim,id,{id}]|numeric|min_length[5]|max_length[20]',
        'nama'  => 'required|min_length[3]|max_length[100]',
        'prodi' => 'required|max_length[100]',
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
