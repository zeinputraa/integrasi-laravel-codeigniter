<?php

use App\Controllers\BookController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Dashboard
$routes->get('/', [BookController::class, 'dashboard']);
$routes->get('/dashboard', [BookController::class, 'dashboard']);

// Books Routes
$routes->group('books', function ($routes) {
    $routes->get('/', [BookController::class, 'index']);
    $routes->get('create', [BookController::class, 'create']);
    $routes->post('store', [BookController::class, 'store']);
    $routes->get('edit/(:num)', [BookController::class, 'edit']);
    $routes->post('update/(:num)', [BookController::class, 'update']);
    $routes->get('delete/(:num)', [BookController::class, 'delete']);
    $routes->post('update-status/(:num)', [BookController::class, 'updateStatus']);
    $routes->get('books/statistics', [BookController::class, 'statistics']);
});