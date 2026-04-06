<?php

namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('Login');
    }

    public function login()
    {
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = md5($this->request->getPost('password'));

        $user = $model->where('username', $username)
                      ->where('password', $password)
                      ->first();

        if ($user) {
            session()->set('login', true);
            return redirect()->to('/mahasiswa');
        } else {
            return redirect()->back()->with('error', 'Login gagal');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}