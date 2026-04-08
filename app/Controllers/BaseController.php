<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController menyediakan tempat yang nyaman untuk memuat komponen
 * dan menjalankan fungsi yang diperlukan oleh semua kontroler.
 * Semua kontroler baru harus mewarisi (extend) kelas ini.
 */
abstract class BaseController extends Controller
{
    /**
     * Inisialisasi properti untuk kompatibilitas PHP 8.2+.
     */

    // protected $session;

    /**
     * initController dipanggil secara otomatis oleh framework saat kontroler mulai dijalankan.
     * Digunakan untuk memuat helper, model, atau library yang dibutuhkan secara global.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Panggil initController dari parent agar proses inisialisasi framework berjalan normal
        parent::initController($request, $response, $logger);

        // Tambahkan helper atau library yang sering digunakan di sini.
        // $this->session = service('session');
    }
}
