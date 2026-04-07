<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('login')) {
            return redirect()->to('/dashboard');
        }
        return redirect()->to('/login');
    }

    public function login()
    {
        if (session()->get('login')) {
            return redirect()->to('/dashboard');
        }
        return view('Login');
    }

    public function authenticate()
    {
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = (string)$this->request->getPost('password');

        $user = $model->findByUsername($username);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found');
        }

        if (!password_verify($password, $user->password)) {
            return redirect()->to('/login')->with('error', 'Wrong password');
        }

        session()->regenerate();
        session()->set([
            'login'    => true,
            'user_id'  => $user->id,
            'username' => $user->username
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}