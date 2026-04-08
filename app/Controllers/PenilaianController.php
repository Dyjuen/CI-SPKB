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
        $mahasiswa = $this->mahasiswaModel->asArray()->paginate(10);
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
            'pager'         => $this->mahasiswaModel->pager
        ]);
    }

    /**
     * Save scores for a specific candidate.
     */
    public function store($mahasiswaId)
    {
        $kriteriaList = $this->kriteriaModel->findAll();

        // Detect JSON body (sent by the AJAX fetch in the view)
        $isJson = strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false;
        if ($isJson) {
            $jsonBody   = $this->request->getJSON(true); // decode as array
            $inputNilai = $jsonBody['nilai'] ?? [];
        } else {
            $inputNilai = $this->request->getVar('nilai') ?? [];
        }

        // Build validation rules manually so we can validate $inputNilai regardless of source
        $rules    = [];
        $messages = [];
        foreach ($kriteriaList as $k) {
            $field          = "nilai.{$k->id}";
            $rules[$field]  = 'required|numeric';
            $messages[$field] = [
                'required' => "Nilai untuk kriteria {$k->nama_kriteria} harus diisi.",
                'numeric'  => "Nilai untuk kriteria {$k->nama_kriteria} harus berupa angka.",
            ];
        }

        // Validate the normalised data array (works for both JSON and form payloads)
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        if (!$validation->run(['nilai' => $inputNilai])) {
            if ($this->request->isAJAX() || $isJson) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors'  => $validation->getErrors(),
                    'csrf'    => csrf_hash()
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors())
                ->with('error_mhs_id', $mahasiswaId);
        }

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
