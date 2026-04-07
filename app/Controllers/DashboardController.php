<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\HasilModel;

class DashboardController extends BaseController
{
    /**
     * Display the application dashboard.
     * 
     * Note: Authentication is handled by the 'auth' filter 
     * configured in Config/Filters.php.
     */
    public function index()
    {
        $mahasiswaModel = new MahasiswaModel();
        $kriteriaModel = new KriteriaModel();
        $hasilModel = new HasilModel();

        $rankedResults = $hasilModel->asArray()->getRankedResults() ?? [];
        $totalHasil = count($rankedResults);
        $threshold = \App\Models\HasilModel::PASSING_LIMIT;

        $data = [
            'total_mahasiswa'    => $mahasiswaModel->countAllResults(),
            'total_kriteria'     => $kriteriaModel->countAllResults(),
            'top_ranking'        => array_slice($rankedResults, 0, $threshold),
            'kriteria_list'      => $kriteriaModel->asArray()->findAll(),
            'total_lulus'        => min($totalHasil, $threshold),
            'total_tidak_lulus'  => max(0, $totalHasil - $threshold),
        ];

        return view('dashboard/index', $data);
    }
}
