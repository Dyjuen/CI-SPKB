<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');

// Dashboard Routes
$routes->get('/dashboard', 'DashboardController::index');

// Mahasiswa CRUD Routes
$routes->group('/mahasiswa', function ($routes) {
    $routes->get('tambah', 'MahasiswaController::tambah');
    $routes->get('/', 'MahasiswaController::index');
    $routes->post('/', 'MahasiswaController::store');
    $routes->put('(:num)', 'MahasiswaController::update/$1');
    $routes->delete('(:num)', 'MahasiswaController::delete/$1');
});

// Kriteria CRUD Routes
$routes->group('/kriteria', function ($routes) {
    $routes->get('/', 'KriteriaController::index');
    $routes->post('/', 'KriteriaController::store');
    $routes->get('(:num)/json', 'KriteriaController::show/$1');
    $routes->put('(:num)', 'KriteriaController::update/$1');
    $routes->delete('(:num)', 'KriteriaController::delete/$1');
});

// Penilaian Routes
$routes->get('/penilaian', 'PenilaianController::index');
$routes->post('/penilaian/(:num)', 'PenilaianController::store/$1');

// Hasil Routes
$routes->get('/hasil', 'HasilController::index');
$routes->post('/hasil/hitung', 'HasilController::hitung');
