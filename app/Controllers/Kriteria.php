<?php

namespace App\Controllers;

class Kriteria extends BaseController
{
    public function index()
    {
        return view('layouts/Layout', [
            'title'        => 'Kriteria & Bobot',
            'breadcrumb'   => 'Kriteria',
            'content_view' => view('kriteria', [
                'kriteria' => [] // nanti diisi dari model
            ])
        ]);
    }
}
