<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\HasilModel;

class Dashboard extends BaseController
{
    public function __construct()
    {
        // Proteksi harus login
        if (!session()->get('login')) {
            header('Location: /');
            exit();
        }
    }

    public function index()
    {
        $mahasiswaModel = new MahasiswaModel();
        $kriteriaModel = new KriteriaModel();
        $hasilModel = new HasilModel();

        $data = [
            'title'              => 'Dashboard',
            'total_mahasiswa'    => count($mahasiswaModel->findAll()),
            'total_kriteria'     => count($kriteriaModel->findAll()),
            'top_ranking'        => $hasilModel->getRankedResults() ?? [],
            'kriteria_list'      => $kriteriaModel->findAll(),
            'total_lulus'        => 0,
            'total_tidak_lulus'  => 0,
        ];

        return view('layouts/Layout', [
            'title'        => 'Dashboard',
            'breadcrumb'   => 'Dashboard',
            'content_view' => view('Dashboard', $data)
        ]);
    }
}