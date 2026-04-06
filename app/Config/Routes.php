<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Dashboard Routes
$routes->get('/dashboard', 'Dashboard::index');

// Mahasiswa CRUD Routes
$routes->get('/mahasiswa', 'Mahasiswa::index');
$routes->get('/mahasiswa/tambah', 'Mahasiswa::tambah');
$routes->post('/mahasiswa/simpan', 'Mahasiswa::simpan');
$routes->get('/mahasiswa/delete/(:num)', 'Mahasiswa::delete/$1');

// Kriteria CRUD Routes
$routes->group('kriteria', function($routes) {
    $routes->get('/', 'Kriteria::index');
    $routes->post('/', 'KriteriaController::store');
    $routes->get('(:num)/json', 'KriteriaController::show/$1');
    $routes->put('(:num)', 'KriteriaController::update/$1');
    $routes->delete('(:num)', 'KriteriaController::delete/$1');
});

// Penilaian Routes
$routes->get('/penilaian', 'Penilaian::index');
$routes->post('/penilaian/simpan', 'Penilaian::simpan');

// Hasil Routes
$routes->get('/hasil', 'HasilController::index');
$routes->post('/hasil/hitung', 'HasilController::hitung');
