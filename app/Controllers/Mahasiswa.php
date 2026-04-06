<?php

namespace App\Controllers;

class Mahasiswa extends BaseController
{
    public function index()
    {
        return view('layouts/Layout', [
            'title'        => 'Data Mahasiswa',
            'breadcrumb'   => 'Mahasiswa',
            'content_view' => view('mahasiswa')
        ]);
    }
}