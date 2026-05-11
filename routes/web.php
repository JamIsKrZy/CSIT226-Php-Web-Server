<?php
use App\Controllers\LoginController;
use App\Controllers\UserController;
use App\Controllers\AdminController;

// Login routes
$router->get('/login', [LoginController::class, 'showLogin']);
$router->post('/login', [LoginController::class, 'handleLogin']);
$router->get('/logout', [LoginController::class, 'logout']);
$router->get('/admin/login', [LoginController::class, 'showAdminLogin']);
$router->post('/admin/login', [LoginController::class, 'handleAdminLogin']);

// Signup routes
$router->get('/signup', [LoginController::class, 'showSignup']);
$router->post('/signup', [LoginController::class, 'handleSignup']);

// Users routes
$router->get('/users', [UserController::class, 'listUsers']);
$router->get('/dashboard', [UserController::class, 'dashboard']);
$router->get('/section-demand', [UserController::class, 'sectionDemand']);
$router->get('/enrollment-plan', [UserController::class, 'enrollmentPlan']);
$router->get('/alternative-sections', [UserController::class, 'alternativeSections']);
$router->get('/enrollment-updates', [UserController::class, 'enrollmentUpdates']);
$router->get('/change-password', [UserController::class, 'changePassword']);
$router->get('/admin/student-interest', [AdminController::class, 'studentInterest']);
$router->get('/admin/enrollment-updates', [AdminController::class, 'enrollmentUpdates']);
$router->post('/admin/enrollment-updates/add', [AdminController::class, 'addUpdate']);
$router->post('/admin/enrollment-updates/edit', [AdminController::class, 'editUpdate']);
$router->post('/admin/enrollment-updates/delete', [AdminController::class, 'deleteUpdate']);

$router->get('/admin/management', [AdminController::class, 'adminManagement']);
$router->post('/admin/management/add', [AdminController::class, 'addAdmin']);
$router->post('/admin/management/edit', [AdminController::class, 'editAdmin']);
$router->post('/admin/management/delete', [AdminController::class, 'deleteAdmin']);

// Home route
$router->get('/', [LoginController::class, 'showLogin']);
