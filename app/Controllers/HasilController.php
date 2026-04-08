<?php

namespace App\Controllers;

use App\Models\HasilModel;
use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;

/**
 * HasilController menangani perolehan dan perhitungan hasil akhir menggunakan metode SAW.
 */
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

    /**
     * Menampilkan halaman index hasil perhitungan.
     * Mengambil data hasil perankingan yang sudah dihitung dan menyajikannya dengan pagination.
     */
    public function index()
    {
        $hasil = $this->hasilModel
                ->select('hasil.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.prodi')
                ->join('mahasiswa', 'mahasiswa.id = hasil.mahasiswa_id')
                ->orderBy('ranking', 'ASC')
                ->paginate(10);
        
        $pager = $this->hasilModel->pager;
        $batasLulus = \App\Models\HasilModel::PASSING_LIMIT;
        
        // Menggunakan total dari pager jika tersedia untuk menghitung jumlah lulus/tidak lulus
        $totalHasil = $pager->getTotal();

        $data = [
            'title'             => 'Hasil Perhitungan SAW',
            'breadcrumb'        => 'Hasil',
            'hasil'             => $hasil,
            'batas_lulus'       => $batasLulus,
            'total_lulus'       => min($totalHasil, $batasLulus),
            'total_tidak_lulus' => max(0, $totalHasil - $batasLulus),
            'pager'             => $pager,
        ];

        return view('hasil/index', $data);
    }

    /**
     * Menjalankan proses perhitungan metode SAW (Simple Additive Weighting).
     * Langkah-langkah meliputi validasi data, pembentukan matriks keputusan, normalisasi, dan perankingan.
     */
    public function hitung()
    {
        $mahasiswaList = $this->mahasiswaModel->findAll();
        $kriteriaList  = $this->kriteriaModel->findAll();
        $penilaianList = $this->penilaianModel->findAll();

        // Cek ketersediaan data dasar sebelum memulai perhitungan
        if (empty($mahasiswaList) || empty($kriteriaList)) {
            return redirect()->back()->with('error', 'Data mahasiswa atau kriteria masih kosong.');
        }

        // --- Langkah 0: Validasi (Pastikan semua kriteria sudah terisi untuk setiap mahasiswa) ---
        // Mengelompokkan data penilaian berdasarkan ID mahasiswa
        $scoresByMhs = [];
        foreach ($penilaianList as $p) {
            $scoresByMhs[$p->mahasiswa_id][] = $p->kriteria_id;
        }

        $totalKriteria = count($kriteriaList);
        foreach ($mahasiswaList as $m) {
            $mhsScores = isset($scoresByMhs[$m->id]) ? $scoresByMhs[$m->id] : [];
            // Jika ada mahasiswa yang belum memiliki penilaian lengkap, proses dihentikan
            if (count($mhsScores) < $totalKriteria) {
                return redirect()->back()->with('error', 'kriteria belum diisi sepenuhnya');
            }
        }

        // --- Langkah 1: Membentuk Matriks Keputusan (X) ---
        // Matriks ini berisi nilai asli setiap mahasiswa untuk setiap kriteria
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

        // --- Langkah 2: Normalisasi Matriks (R) ---
        // Menyesuaikan nilai berdasarkan tipe kriteria (Benefit atau Cost)
        $normalized = [];
        foreach ($kriteriaList as $k) {
            $colValues = array_column($matrix, $k->id);
            $maxVal = max($colValues);
            $minVal = min($colValues);

            foreach ($mahasiswaList as $m) {
                $val = $matrix[$m->id][$k->id];
                
                if ($k->tipe === 'B') {
                    // Benefit: Nilai tertinggi adalah yang terbaik
                    // r_ij = x_ij / max(x_j)
                    $normalized[$m->id][$k->id] = ($maxVal == 0) ? 0 : $val / $maxVal;
                } else {
                    // Cost: Nilai terendah adalah yang terbaik
                    // r_ij = min(x_j) / x_ij
                    $normalized[$m->id][$k->id] = ($val == 0) ? 0 : $minVal / $val;
                }
            }
        }

        // --- Langkah 3: Menghitung Nilai Preferensi (V) ---
        // Mengalikan bobot kriteria dengan nilai yang sudah dinormalisasi
        $vScores = [];
        foreach ($mahasiswaList as $m) {
            $v = 0;
            foreach ($kriteriaList as $k) {
                $v += $k->bobot * $normalized[$m->id][$k->id];
            }
            $vScores[$m->id] = $v;
        }

        // --- Langkah 4: Simpan & Perankingan ---
        // Mengurutkan hasil dari yang terbesar ke terkecil
        arsort($vScores);
        
        // Menggunakan transaksi untuk memastikan integritas data saat mengganti hasil lama dengan yang baru
        $this->hasilModel->db->transStart();
        $this->hasilModel->truncate(); // Menghapus data hasil lama
        
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
