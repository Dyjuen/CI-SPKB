<?php

namespace App\Models;

use CodeIgniter\Model;

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

    // Validation
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
     * Upsert score for a student per criterion
     *
     * @param int $mahasiswa_id
     * @param int $kriteria_id
     * @param float $nilai
     * @return bool
     */
    public function upsertScore(int $mahasiswa_id, int $kriteria_id, float $nilai)
    {
        // CodeIgniter 4's replace() works like INSERT OR REPLACE
        // To handle ON DUPLICATE KEY UPDATE with current timestamps, we'll find existing or insert
        $existing = $this->where([
            'mahasiswa_id' => $mahasiswa_id,
            'kriteria_id'  => $kriteria_id,
        ])->first();

        if ($existing) {
            return $this->update($existing->id, ['nilai' => $nilai]);
        }

        return $this->insert([
            'mahasiswa_id' => $mahasiswa_id,
            'kriteria_id'  => $kriteria_id,
            'nilai'        => $nilai,
        ]);
    }

    /**
     * Get all scores formatted for SAW calculation matrix
     *
     * @return array
     */
    public function getAllScores()
    {
        return $this->findAll();
    }
}
