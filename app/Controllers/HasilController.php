<?php

namespace App\Controllers;

use App\Models\HasilModel;
use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;

class HasilController extends BaseController
{
    protected $hasilModel;
    protected $mahasiswaModel;
    protected $kriteriaModel;
    protected $penilaianModel;

    public function __construct()
    {
        $this->hasilModel = new HasilModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->kriteriaModel = new KriteriaModel();
        $this->penilaianModel = new PenilaianModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Hasil Perhitungan SAW',
            'breadcrumb' => 'Hasil',
            'hasil'      => $this->hasilModel->getRankedResults()
        ];

        return view('hasil/index', $data);
    }

    public function hitung()
    {
        $mahasiswaList = $this->mahasiswaModel->findAll();
        $kriteriaList  = $this->kriteriaModel->findAll();
        $penilaianList = $this->penilaianModel->findAll();

        if (empty($mahasiswaList) || empty($kriteriaList)) {
            return redirect()->back()->with('error', 'Data mahasiswa atau kriteria masih kosong.');
        }

        // --- Step 0: Validation (Check if all criteria are filled for each student) ---
        // Group penilaian by mahasiswa_id
        $scoresByMhs = [];
        foreach ($penilaianList as $p) {
            $scoresByMhs[$p->mahasiswa_id][] = $p->kriteria_id;
        }

        $totalKriteria = count($kriteriaList);
        foreach ($mahasiswaList as $m) {
            $mhsScores = isset($scoresByMhs[$m->id]) ? $scoresByMhs[$m->id] : [];
            if (count($mhsScores) < $totalKriteria) {
                return redirect()->back()->with('error', 'kriteria belum diisi sepenuhnya');
            }
        }

        // --- Step 1: Build Decision Matrix (X) ---
        $matrix = [];
        $scoresMap = [];
        foreach ($penilaianList as $p) {
            $scoresMap[$p->mahasiswa_id][$p->kriteria_id] = $p->nilai;
        }

        foreach ($mahasiswaList as $m) {
            foreach ($kriteriaList as $k) {
                $matrix[$m->id][$k->id] = $scoresMap[$m->id][$k->id];
            }
        }

        // --- Step 2: Normalization (R) ---
        $normalized = [];
        foreach ($kriteriaList as $k) {
            $colValues = array_column($matrix, $k->id);
            $maxVal = max($colValues);
            $minVal = min($colValues);

            foreach ($mahasiswaList as $m) {
                $val = $matrix[$m->id][$k->id];
                
                if ($k->tipe === 'B') {
                    // Benefit: r_ij = x_ij / max(x_j)
                    $normalized[$m->id][$k->id] = ($maxVal == 0) ? 0 : $val / $maxVal;
                } else {
                    // Cost: r_ij = min(x_j) / x_ij
                    $normalized[$m->id][$k->id] = ($val == 0) ? 0 : $minVal / $val;
                }
            }
        }

        // --- Step 3: Preference Value (V) ---
        $vScores = [];
        foreach ($mahasiswaList as $m) {
            $v = 0;
            foreach ($kriteriaList as $k) {
                $v += $k->bobot * $normalized[$m->id][$k->id];
            }
            $vScores[$m->id] = $v;
        }

        // --- Step 4: Save & Rank ---
        arsort($vScores);
        
        $this->hasilModel->db->transStart();
        $this->hasilModel->truncate(); // Clear old results
        
        $rank = 1;
        foreach ($vScores as $mId => $score) {
            $this->hasilModel->insert([
                'mahasiswa_id'     => $mId,
                'nilai_preferensi' => $score,
                'ranking'          => $rank++
            ]);
        }
        $this->hasilModel->db->transComplete();

        return redirect()->to('/hasil')->with('success', 'Perhitungan SAW berhasil diselesaikan.');
    }
}
