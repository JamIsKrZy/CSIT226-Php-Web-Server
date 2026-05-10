<?php
use App\Controllers\LoginController;
use App\Controllers\UserController;

// Login routes
$router->get('/login', [LoginController::class, 'showLogin']);
$router->post('/login', [LoginController::class, 'handleLogin']);
$router->get('/logout', [LoginController::class, 'logout']);

// Signup routes
$router->get('/signup', [LoginController::class, 'showSignup']);
$router->post('/signup', [LoginController::class, 'handleSignup']);

// Users routes
$router->get('/users', [UserController::class, 'listUsers']);
$router->get('/dashboard', [UserController::class, 'dashboard']);
$router->get('/section-demand', [UserController::class, 'sectionDemand']);
$router->get('/enrollment-plan', [UserController::class, 'enrollmentPlan']);

// Home route
$router->get('/', [LoginController::class, 'showLogin']);
