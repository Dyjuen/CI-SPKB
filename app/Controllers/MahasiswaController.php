<?php

namespace App\Controllers;
use App\Models\MahasiswaModel;
use App\Models\PenilaianModel;

class MahasiswaController extends BaseController
{
    public function index()
    {
        $model = new MahasiswaModel();
        $data['mahasiswa'] = $model->asArray()->paginate(10);
        $data['pager'] = $model->pager;
        $data['title'] = 'Data Mahasiswa';

        return view('mahasiswa/index', $data);
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Mahasiswa Baru';
        return view('mahasiswa/tambah', $data);
    }

    public function store()
    {
        $model = new MahasiswaModel();

        if ($model->save([
            'nama'  => $this->request->getPost('nama'),
            'nim'   => $this->request->getPost('nim'),
            'prodi' => $this->request->getPost('prodi')
        ])) {
            return redirect()->to('/mahasiswa')->with('success', 'Mahasiswa berhasil disimpan');
        }

        $errors = $model->errors();
        return redirect()->back()->withInput()->with('error', $errors ? implode(', ', $errors) : 'Gagal menyimpan mahasiswa');
    }

    public function update($id = null)
    {
        $model = new MahasiswaModel();
        
        $data = [
            'id'    => $id,
            'nama'  => $this->request->getVar('nama'),
            'nim'   => $this->request->getVar('nim'),
            'prodi' => $this->request->getVar('prodi')
        ];

        if ($model->save($data)) {
            return redirect()->to('/mahasiswa')->with('success', 'Mahasiswa berhasil diperbarui');
        }

        $errors = $model->errors();
        return redirect()->back()->withInput()->with('error', $errors ? implode(', ', $errors) : 'Gagal memperbarui mahasiswa');
    }

    public function delete($id = null)
    {
        $model = new MahasiswaModel();
        
        if ($model->delete($id)) {
            // Cascade delete correlated penilaian
            $penilaianModel = new PenilaianModel();
            $penilaianModel->where('mahasiswa_id', $id)->delete();

            return redirect()->to('/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus mahasiswa');
    }
}