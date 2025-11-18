<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth
$routes->addRedirect('/', '/login');
$routes->get('/login', 'AuthController::index');
$routes->post('/login/auth', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

// Dashboard
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'authcheck']);

// Pengambilan makanan
$routes->get('/food-pickup', 'FoodPickupController::index', ['filter' => 'authcheck']);
$routes->post('/food-pickup/save', 'FoodPickupController::save', ['filter' => 'authcheck']);

// Daftar user
$routes->get('/daftar-user', 'DaftarUserController::index', ['filter' => 'authcheck']);
$routes->get('/tambah-user', 'DaftarUserController::registerView', ['filter' => 'authcheck']);
$routes->post('/tambah-user/register', 'DaftarUserController::register', ['filter' => 'authcheck']);
$routes->get('/edit-user/(:num)', 'DaftarUserController::edit/$1', ['filter' => 'authcheck']);
$routes->post('/update-user/(:num)', 'DaftarUserController::update/$1', ['filter' => 'authcheck']);
$routes->post('/delete-user/(:num)', 'DaftarUserController::delete/$1', ['filter' => 'authcheck']);
