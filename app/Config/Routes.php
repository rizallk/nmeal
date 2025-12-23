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
$routes->get('dashboard/recommendation', 'DashboardController::getRecommendation', ['filter' => 'authcheck']);

// Pengambilan makanan
$routes->get('/food-pickup', 'FoodPickupController::index', ['filter' => 'authcheck']);
$routes->get('food-pickup/export-pdf', 'FoodPickupController::exportPdf', ['filter' => 'authcheck']);
$routes->post('/food-pickup/save', 'FoodPickupController::save', ['filter' => 'authcheck']);
// Get Student Allergens
$routes->get('food-pickup/get-allergens/(:num)', 'FoodPickupController::getStudentAllergens/$1', ['filter' => 'authcheck']);

// Daftar user
$routes->get('/daftar-user', 'DaftarUserController::index', ['filter' => 'authcheck']);
$routes->get('/tambah-user', 'DaftarUserController::registerView', ['filter' => 'authcheck']);
$routes->post('/tambah-user/register', 'DaftarUserController::register', ['filter' => 'authcheck']);
$routes->get('/edit-user/(:num)', 'DaftarUserController::edit/$1', ['filter' => 'authcheck']);
$routes->post('/update-user/(:num)', 'DaftarUserController::update/$1', ['filter' => 'authcheck']);
$routes->post('/delete-user/(:num)', 'DaftarUserController::delete/$1', ['filter' => 'authcheck']);

// Daftar siswa
$routes->get('/daftar-siswa', 'DaftarSiswaController::index', ['filter' => 'authcheck']);
$routes->get('/tambah-siswa', 'DaftarSiswaController::registerView', ['filter' => 'authcheck']);
$routes->post('/tambah-siswa/register', 'DaftarSiswaController::register', ['filter' => 'authcheck']);
$routes->get('/edit-siswa/(:num)', 'DaftarSiswaController::edit/$1', ['filter' => 'authcheck']);
$routes->post('/update-siswa/(:num)', 'DaftarSiswaController::update/$1', ['filter' => 'authcheck']);
$routes->post('/delete-siswa/(:num)', 'DaftarSiswaController::delete/$1', ['filter' => 'authcheck']);

// Daftar makanan
$routes->get('/daftar-makanan', 'DaftarMakananController::index', ['filter' => 'authcheck']);
$routes->get('/tambah-makanan', 'DaftarMakananController::registerView', ['filter' => 'authcheck']);
$routes->post('/tambah-makanan/register', 'DaftarMakananController::register', ['filter' => 'authcheck']);
$routes->get('/edit-makanan/(:num)', 'DaftarMakananController::edit/$1', ['filter' => 'authcheck']);
$routes->post('/update-makanan/(:num)', 'DaftarMakananController::update/$1', ['filter' => 'authcheck']);
$routes->post('/delete-makanan/(:num)', 'DaftarMakananController::delete/$1', ['filter' => 'authcheck']);

// Daftar allergen
$routes->get('/daftar-alergen', 'DaftarAlergenController::index', ['filter' => 'authcheck']);
$routes->get('/tambah-alergen', 'DaftarAlergenController::registerView', ['filter' => 'authcheck']);
$routes->post('/tambah-alergen/register', 'DaftarAlergenController::register', ['filter' => 'authcheck']);
$routes->get('/edit-alergen/(:num)', 'DaftarAlergenController::edit/$1', ['filter' => 'authcheck']);
$routes->post('/update-alergen/(:num)', 'DaftarAlergenController::update/$1', ['filter' => 'authcheck']);
$routes->post('/delete-alergen/(:num)', 'DaftarAlergenController::delete/$1', ['filter' => 'authcheck']);

// Daftar bahan makanan
$routes->get('/daftar-bahan-makanan', 'DaftarBahanMakananController::index', ['filter' => 'authcheck']);
$routes->get('/tambah-bahan-makanan', 'DaftarBahanMakananController::registerView', ['filter' => 'authcheck']);
$routes->post('/tambah-bahan-makanan/register', 'DaftarBahanMakananController::register', ['filter' => 'authcheck']);
$routes->get('/edit-bahan-makanan/(:num)', 'DaftarBahanMakananController::edit/$1', ['filter' => 'authcheck']);
$routes->post('/update-bahan-makanan/(:num)', 'DaftarBahanMakananController::update/$1', ['filter' => 'authcheck']);
$routes->post('/delete-bahan-makanan/(:num)', 'DaftarBahanMakananController::delete/$1', ['filter' => 'authcheck']);

// Aktivitas makan siswa
$routes->get('/food-activity', 'FoodActivityStudentController::index', ['filter' => 'authcheck']);

// Notification
$routes->post('/notification/subscribe', 'NotificationController::subscribe', ['filter' => 'authcheck']);
$routes->get('/notification/get-public-key', 'NotificationController::getPublicKey', ['filter' => 'authcheck']);

// Profile
$routes->get('/profile', 'ProfileController::index', ['filter' => 'authcheck']);
$routes->post('/update-profile/(:num)', 'ProfileController::update/$1', ['filter' => 'authcheck']);
