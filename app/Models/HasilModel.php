<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * HasilModel menyimpan dan mengelola data hasil perankingan SAW.
 */
class HasilModel extends Model
{
    // Batas jumlah mahasiswa yang dianggap lulus/diterima
    public const PASSING_LIMIT = 5;

    protected $table            = 'hasil';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['mahasiswa_id', 'nilai_preferensi', 'ranking'];

    protected bool $allowEmptyInserts = false;

    // Pengaturan waktu otomatis (created_at, updated_at)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Aturan validasi untuk menjamin integritas data sebelum masuk ke database
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
     * Mengambil data hasil perankingan lengkap dengan informasi profil mahasiswa.
     * Join dilakukan untuk mendapatkan NIM dan Nama Mahasiswa.
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
