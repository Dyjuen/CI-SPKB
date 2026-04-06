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

$routes->get('/', 'Login::index');
