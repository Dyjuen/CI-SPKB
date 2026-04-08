<?php

namespace App\Controllers;
use App\Models\MahasiswaModel;
use App\Models\PenilaianModel;

/**
 * MahasiswaController mengelola data profil mahasiswa.
 * Data ini menjadi entitas utama yang akan dinilai dalam sistem pendukung keputusan.
 */
class MahasiswaController extends BaseController
{
    /**
     * Menampilkan daftar mahasiswa dengan sistem pagination untuk performa aplikasi.
     */
    public function index()
    {
        $model = new MahasiswaModel();
        $data['mahasiswa'] = $model->asArray()->paginate(10);
        $data['pager'] = $model->pager;
        $data['title'] = 'Data Mahasiswa';

        return view('mahasiswa/index', $data);
    }

    /**
     * Menampilkan form untuk menambah data mahasiswa baru.
     */
    public function tambah()
    {
        $data['title'] = 'Tambah Mahasiswa Baru';
        return view('mahasiswa/tambah', $data);
    }

    /**
     * Menyimpan data mahasiswa baru ke database.
     * Secara otomatis memvalidasi input berdasarkan rules di MahasiswaModel.
     */
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

    /**
     * Memperbarui data profil mahasiswa yang sudah ada.
     */
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

    /**
     * Menghapus data mahasiswa dan data penilaian terkait.
     * Penghapusan penilaian dilakukan secara manual (cascade) untuk menjaga konsistensi data.
     */
    public function delete($id = null)
    {
        $model = new MahasiswaModel();
        
        if ($model->delete($id)) {
            // Menghapus semua penilaian yang terikat dengan mahasiswa ini agar tidak ada data sampah (orphan records)
            $penilaianModel = new PenilaianModel();
            $penilaianModel->where('mahasiswa_id', $id)->delete();

            return redirect()->to('/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus mahasiswa');
    }
}
