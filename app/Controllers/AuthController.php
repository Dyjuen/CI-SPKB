<?php

namespace App\Controllers;
use App\Models\UserModel;

/**
 * AuthController menangani semua proses yang berkaitan dengan otentikasi pengguna.
 */
class AuthController extends BaseController
{
    /**
     * Menentukan halaman awal saat mengakses base URL.
     * Jika sudah login, diarahkan ke dashboard, jika belum ke login page.
     */
    public function index()
    {
        if (session()->get('login')) {
            return redirect()->to('/dashboard');
        }
        return redirect()->to('/login');
    }

    /**
     * Menampilkan halaman login.
     * Dilakukan pengecekan status login untuk mencegah user yang sudah masuk kembali ke form login.
     */
    public function login()
    {
        if (session()->get('login')) {
            return redirect()->to('/dashboard');
        }
        return view('Login');
    }

    /**
     * Memproses data login yang dikirim oleh pengguna.
     * Verifikasi dilakukan berdasarkan username dan password yang di-hash.
     */
    public function authenticate()
    {
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = (string)$this->request->getPost('password');

        // Mencari data user berdasarkan username unik
        $user = $model->findByUsername($username);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found');
        }

        // Verifikasi password menggunakan algoritma hashing default PHP untuk keamanan
        if (!password_verify($password, $user->password)) {
            return redirect()->to('/login')->with('error', 'Wrong password');
        }

        // Meregenerasi session ID untuk mencegah session fixation attacks
        session()->regenerate();
        session()->set([
            'login'    => true,
            'user_id'  => $user->id,
            'username' => $user->username
        ]);

        return redirect()->to('/dashboard');
    }

    /**
     * Mengakhiri session pengguna dan diarahkan kembali ke halaman login.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}