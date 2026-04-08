<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\HasilModel;

/**
 * DashboardController menangani tampilan ringkasan data dan statistik utama aplikasi.
 */
class DashboardController extends BaseController
{
    /**
     * Menampilkan dashboard aplikasi.
     * Mengambil data statistik seperti jumlah mahasiswa, kriteria, dan ringkasan hasil perankingan.
     */
    public function index()
    {
        $mahasiswaModel = new MahasiswaModel();
        $kriteriaModel = new KriteriaModel();
        $hasilModel = new HasilModel();

        // Mengambil hasil perankingan yang sudah dihitung
        $rankedResults = $hasilModel->asArray()->getRankedResults() ?? [];
        $totalHasil = count($rankedResults);
        $threshold = \App\Models\HasilModel::PASSING_LIMIT;

        // Menyusun data untuk dikirim ke view dashboard
        $data = [
            'total_mahasiswa'    => $mahasiswaModel->countAllResults(),
            'total_kriteria'     => $kriteriaModel->countAllResults(),
            // Mengambil 5 besar (threshold) untuk ringkasan di dashboard
            'top_ranking'        => array_slice($rankedResults, 0, $threshold),
            'kriteria_list'      => $kriteriaModel->asArray()->findAll(),
            'total_lulus'        => min($totalHasil, $threshold),
            'total_tidak_lulus'  => max(0, $totalHasil - $threshold),
        ];

        return view('dashboard/index', $data);
    }
}
