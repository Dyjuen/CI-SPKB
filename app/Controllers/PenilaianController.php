<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;

/**
 * PenilaianController mengelola proses pengisian nilai setiap mahasiswa terhadap kriteria yang ada.
 */
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
     * Menampilkan tabel penilaian (grid).
     * Memetakan skor dari database ke dalam struktur array [mahasiswa_id][kriteria_id] 
     * agar mudah ditampilkan dalam bentuk tabel matriks di view.
     */
    public function index()
    {
        $mahasiswa = $this->mahasiswaModel->asArray()->paginate(10);
        $kriteria  = $this->kriteriaModel->asArray()->findAll();
        $scores    = $this->penilaianModel->asArray()->findAll();

        // Menyusun skor: [mahasiswa_id][kriteria_id] = nilai
        // Struktur ini mempermudah pencarian nilai saat looping baris mahasiswa dan kolom kriteria
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
     * Menyimpan nilai-nilai kriteria untuk mahasiswa tertentu.
     * Mendukung input via form standar maupun AJAX (JSON).
     */
    public function store($mahasiswaId)
    {
        $kriteriaList = $this->kriteriaModel->findAll();

        // Mendeteksi apakah request dikirim dalam format JSON (biasanya oleh fetch API di frontend)
        $isJson = strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false;
        if ($isJson) {
            $jsonBody   = $this->request->getJSON(true); // Decode menjadi array
            $inputNilai = $jsonBody['nilai'] ?? [];
        } else {
            $inputNilai = $this->request->getVar('nilai') ?? [];
        }

        // Membangun aturan validasi secara dinamis berdasarkan jumlah kriteria yang ada
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

        // Menjalankan validasi secara manual menggunakan service validation CodeIgniter
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        if (!$validation->run(['nilai' => $inputNilai])) {
            // Jika request adalah AJAX/JSON, kembalikan respons error dalam format JSON beserta CSRF hash baru
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

        // Menyimpan atau memperbarui nilai menggunakan method upsert di model
        foreach ($inputNilai as $kriteriaId => $nilai) {
            $this->penilaianModel->upsertScore((int)$mahasiswaId, (int)$kriteriaId, (float)$nilai);
        }

        // Mengembalikan respons sukses sesuai tipe request
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
