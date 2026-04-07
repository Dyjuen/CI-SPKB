<?php

namespace App\Controllers;

use App\Models\KriteriaModel;
use CodeIgniter\API\ResponseTrait;

class KriteriaController extends BaseController
{
    use ResponseTrait;

    protected $kriteriaModel;

    public function __construct()
    {
        $this->kriteriaModel = new KriteriaModel();
    }

    /**
     * Display a listing of the resource.
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
     * Return a single record as JSON for AJAX pre-fill.
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
     * Create a new resource object from user input.
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
     * Update an existing resource.
     */
    public function update($id = null)
    {
        $rules = $this->kriteriaModel->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Existence check for Bug #2 (Option A)
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
     * Delete an existing resource.
     */
    public function delete($id = null)
    {
        if ($this->kriteriaModel->delete($id)) {
            return redirect()->to('/kriteria')->with('success', 'Kriteria berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus kriteria');
    }
}
