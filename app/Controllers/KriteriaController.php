<?php

namespace App\Controllers;

use App\Models\KriteriaModel;
use CodeIgniter\API\ResponseTrait;

/**
 * KriteriaController mengelola data kriteria yang digunakan dalam perhitungan SAW.
 * Setiap kriteria memiliki bobot dan tipe (Benefit/Cost).
 */
class KriteriaController extends BaseController
{
    use ResponseTrait;

    protected $kriteriaModel;

    public function __construct()
    {
        $this->kriteriaModel = new KriteriaModel();
    }

    /**
     * Menampilkan daftar kriteria yang tersedia.
     */
    public function index()
    {
        $data = [
            'title'    => 'Manajemen Kriteria',
            'kriteria' => $this->kriteriaModel->findAll(),
        ];

        return view('kriteria/index', $data);
    }

    /**
     * Mengambil data satu kriteria dalam format JSON.
     * Digunakan untuk proses edit (pre-fill form) melalui AJAX.
     */
    public function show($id = null)
    {
        $data = $this->kriteriaModel->find($id);

        if (!$data) {
            return $this->failNotFound('Kriteria tidak ditemukan');
        }

        return $this->respond($data);
    }

    /**
     * Menyimpan data kriteria baru ke database.
     * Melakukan validasi input sesuai aturan yang didefinisikan di KriteriaModel.
     */
    public function store()
    {
        $rules = $this->kriteriaModel->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $data = [
            'nama_kriteria' => $this->request->getPost('nama_kriteria'),
            'bobot'         => $this->request->getPost('bobot'),
            'tipe'          => $this->request->getPost('tipe'),
        ];

        if ($this->kriteriaModel->insert($data)) {
            return redirect()->to('/kriteria')->with('success', 'Kriteria berhasil disimpan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan kriteria');
    }

    /**
     * Memperbarui data kriteria yang sudah ada.
     * Memastikan data yang akan diupdate memang tersedia di database.
     */
    public function update($id = null)
    {
        $rules = $this->kriteriaModel->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Cek keberadaan data untuk menghindari update pada record yang sudah tidak ada
        if (!$this->kriteriaModel->find($id)) {
            return redirect()->back()->withInput()->with('error', 'Kriteria tidak ditemukan');
        }

        $data = [
            'nama_kriteria' => $this->request->getVar('nama_kriteria'),
            'bobot'         => $this->request->getVar('bobot'),
            'tipe'          => $this->request->getVar('tipe'),
        ];

        if ($this->kriteriaModel->update($id, $data)) {
            return redirect()->to('/kriteria')->with('success', 'Kriteria berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kriteria');
    }

    /**
     * Menghapus data kriteria berdasarkan ID.
     */
    public function delete($id = null)
    {
        if ($this->kriteriaModel->delete($id)) {
            return redirect()->to('/kriteria')->with('success', 'Kriteria berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus kriteria');
    }
}
