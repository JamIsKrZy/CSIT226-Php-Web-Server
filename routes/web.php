<?php
use App\Controllers\LoginController;
use App\Controllers\UserController;

// Login routes
$router->get('/login', [LoginController::class, 'showLogin']);
$router->post('/login', [LoginController::class, 'handleLogin']);

// Signup routes
$router->get('/signup', [LoginController::class, 'showSignup']);
$router->post('/signup', [LoginController::class, 'handleSignup']);

// Users routes
$router->get('/users', [UserController::class, 'listUsers']);

// Home route
$router->get('/', function() {
    echo "Welcome to the login system! <a href='/login'>Login</a> | <a href='/signup'>Sign Up</a> | <a href='/users'>View Users</a>";
});
