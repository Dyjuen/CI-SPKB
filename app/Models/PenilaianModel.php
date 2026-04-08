<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PenilaianModel mengelola data skor/nilai setiap mahasiswa untuk setiap kriteria.
 */
class PenilaianModel extends Model
{
    protected $table            = 'penilaian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['mahasiswa_id', 'kriteria_id', 'nilai'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Aturan validasi untuk memastikan relasi data mahasiswa dan kriteria benar
    protected $validationRules      = [
        'mahasiswa_id' => 'required|is_not_unique[mahasiswa.id]',
        'kriteria_id'  => 'required|is_not_unique[kriteria.id]',
        'nilai'         => 'required|numeric',
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
     * Menyimpan atau memperbarui nilai mahasiswa per kriteria.
     * Jika data sudah ada, maka nilai akan diperbarui (update).
     * Jika data belum ada, maka data baru akan ditambahkan (insert).
     *
     * @param int $mahasiswa_id
     * @param int $kriteria_id
     * @param float $nilai
     * @return bool
     */
    public function upsertScore(int $mahasiswa_id, int $kriteria_id, float $nilai)
    {
        // Mencari apakah sudah ada penilaian untuk kombinasi mahasiswa dan kriteria ini
        $existing = $this->where([
            'mahasiswa_id' => $mahasiswa_id,
            'kriteria_id'  => $kriteria_id,
        ])->first();

        if ($existing) {
            // Jika ada, lakukan update pada baris tersebut
            return $this->update($existing->id, ['nilai' => $nilai]);
        }

        // Jika tidak ada, buat baris penilaian baru
        return $this->insert([
            'mahasiswa_id' => $mahasiswa_id,
            'kriteria_id'  => $kriteria_id,
            'nilai'        => $nilai,
        ]);
    }

    /**
     * Mengambil semua data skor untuk digunakan dalam pembentukan matriks SAW.
     *
     * @return array
     */
    public function getAllScores()
    {
        return $this->findAll();
    }
}
