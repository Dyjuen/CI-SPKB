<?php

namespace App\Controllers;
use App\Models\MahasiswaModel;

class Mahasiswa extends BaseController
{
    public function __construct()
    {
        // proteksi harus login
        if (!session()->get('login')) {
            header('Location: /');
            exit();
        }
    }

    public function index()
    {
        $model = new MahasiswaModel();
        $data['mahasiswa'] = $model->findAll();

        return view('layouts/Layout', [
            'title'        => 'Data Mahasiswa',
            'breadcrumb'   => 'Mahasiswa',
            'content_view' => view('Mahasiswa', $data)
        ]);
    }

    public function tambah()
    {
        return view('mahasiswa/tambah');
    }

    public function simpan()
    {
        $model = new MahasiswaModel();

        $model->save([
            'nama' => $this->request->getPost('nama'),
            'nim'  => $this->request->getPost('nim')
        ]);

        return redirect()->to('/mahasiswa');
    }

    public function delete($id)
    {
        $model = new MahasiswaModel();
        $model->delete($id);

        return redirect()->to('/mahasiswa');
    }
}