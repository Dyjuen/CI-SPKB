<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;

class PenilaianController extends BaseController
{
    protected $mahasiswaModel;
    protected $kriteriaModel;
    protected $penilaianModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->kriteriaModel  = new KriteriaModel();
        $this->penilaianModel = new PenilaianModel();
    }

    /**
     * Show the scoring grid.
     */
    public function index()
    {
        $mahasiswa = $this->mahasiswaModel->asArray()->findAll();
        $kriteria  = $this->kriteriaModel->asArray()->findAll();
        $scores    = $this->penilaianModel->asArray()->findAll();

        // Structure scores: [mahasiswa_id][kriteria_id] = nilai
        $mappedScores = [];
        foreach ($scores as $s) {
            $mappedScores[$s['mahasiswa_id']][$s['kriteria_id']] = $s['nilai'];
        }

        return view('penilaian/index', [
            'title'         => 'Penilaian SAW',
            'mahasiswaList' => $mahasiswa,
            'kriteriaList'  => $kriteria,
            'scores'        => $mappedScores,
        ]);
    }

    /**
     * Save scores for a specific candidate.
     */
    public function store($mahasiswaId)
    {
        $kriteriaList = $this->kriteriaModel->findAll();
        $rules = [];
        $messages = [];

        foreach ($kriteriaList as $k) {
            $field = "nilai.{$k->id}";
            $rules[$field] = 'required|numeric';
            $messages[$field] = [
                'required' => "Nilai untuk kriteria {$k->nama_kriteria} harus diisi.",
                'numeric'  => "Nilai untuk kriteria {$k->nama_kriteria} harus berupa angka.",
            ];
        }

        if (!$this->validate($rules, $messages)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash()
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error_mhs_id', $mahasiswaId); // To know which row has errors
        }

        $inputNilai = $this->request->getVar('nilai');

        foreach ($inputNilai as $kriteriaId => $nilai) {
            $this->penilaianModel->upsertScore((int)$mahasiswaId, (int)$kriteriaId, (float)$nilai);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Nilai berhasil disimpan.',
                'csrf'    => csrf_hash()
            ]);
        }

        return redirect()->to('/penilaian')->with('success', 'Nilai berhasil disimpan');
    }
}
