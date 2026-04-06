<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Kriteria CRUD Routes
$routes->group('kriteria', function($routes) {
    $routes->get('/', 'KriteriaController::index');
    $routes->post('/', 'KriteriaController::store');
    $routes->get('(:num)/json', 'KriteriaController::show/$1');
    $routes->put('(:num)', 'KriteriaController::update/$1');
    $routes->delete('(:num)', 'KriteriaController::delete/$1');
});
// SAW Algorithm Routes
$routes->get('hasil', 'HasilController::index');
$routes->post('hitung', 'HasilController::hitung');

// Penilaian Routes
$routes->group('penilaian', function($routes) {
    $routes->get('/', 'PenilaianController::index');
    $routes->post('(:num)', 'PenilaianController::store/$1');
});
