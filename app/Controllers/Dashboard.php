<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('layouts/Layout', [
            'content_view' => view('dashboard')
        ]);
    }
}