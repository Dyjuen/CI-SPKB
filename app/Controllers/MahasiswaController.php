<?php

namespace App\Controllers;
use App\Models\MahasiswaModel;

class MahasiswaController extends BaseController
{
    public function index()
    {
        $model = new MahasiswaModel();
        $data['mahasiswa'] = $model->asArray()->findAll();
        $data['title'] = 'Data Mahasiswa';

        return view('mahasiswa/index', $data);
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Mahasiswa';
        return view('mahasiswa/tambah', $data);
    }

    public function simpan()
    {
        $model = new MahasiswaModel();

        $model->save([
            'nama'  => $this->request->getPost('nama'),
            'nim'   => $this->request->getPost('nim'),
            'prodi' => $this->request->getPost('prodi')
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