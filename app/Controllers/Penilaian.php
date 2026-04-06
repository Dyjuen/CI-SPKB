<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;

class Penilaian extends BaseController
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

    public function index()
    {
        // Ambil semua mahasiswa dan kriteria
        $mahasiswa = $this->mahasiswaModel->findAll();
        $kriteria  = $this->kriteriaModel->findAll();

        // Susun penilaian array[mhs_id][krt_id] = nilai
        $raw       = $this->penilaianModel->findAll();
        $penilaian = [];
        foreach ($raw as $p) {
            $penilaian[$p['mahasiswa_id']][$p['kriteria_id']] = $p['nilai'];
        }

        $data = [
            'title'      => 'Input Penilaian',
            'breadcrumb' => 'Penilaian',
            'mahasiswa'  => $mahasiswa,
            'kriteria'   => $kriteria,
            'penilaian'  => $penilaian,
        ];

        return view('layouts/Layout', [
            'title'        => $data['title'],
            'breadcrumb'   => $data['breadcrumb'],
            'content_view' => view('penilaian', $data)
        ]);
    }

    public function simpan()
    {
        $nilai_post = $this->request->getPost('nilai');

        if (empty($nilai_post)) {
            return redirect()->to('penilaian')
                             ->with('error', 'Tidak ada data penilaian yang dikirim.');
        }

        foreach ($nilai_post as $mhs_id => $kriteria_arr) {
            foreach ($kriteria_arr as $krt_id => $nilai) {
                if ($nilai === '' || $nilai === null) continue;

                // Cek apakah sudah ada datanya
                $cek = $this->penilaianModel
                            ->where('mahasiswa_id', $mhs_id)
                            ->where('kriteria_id', $krt_id)
                            ->first();

                if ($cek) {
                    // Update
                    $this->penilaianModel->update($cek['id'], ['nilai' => $nilai]);
                } else {
                    // Insert baru
                    $this->penilaianModel->insert([
                        'mahasiswa_id' => $mhs_id,
                        'kriteria_id'  => $krt_id,
                        'nilai'        => $nilai,
                    ]);
                }
            }
        }

        return redirect()->to('penilaian')
                         ->with('success', 'Semua penilaian berhasil disimpan.');
    }
}