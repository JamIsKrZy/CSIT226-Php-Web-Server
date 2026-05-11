<?php
use App\Controllers\LoginController;
use App\Controllers\UserController;
use App\Controllers\ApiController;

// Login routes
$router->get('/login', [LoginController::class, 'showLogin']);
$router->post('/login', [LoginController::class, 'handleLogin']);
$router->get('/logout', [LoginController::class, 'logout']);

// Signup routes
$router->get('/signup', [LoginController::class, 'showSignup']);
$router->post('/signup', [LoginController::class, 'handleSignup']);

// Users routes
$router->get('/users', [UserController::class, 'listUsers']);
$router->get('/courses', [UserController::class, 'listCourses']);
$router->get('/sections', [UserController::class, 'listSections']);
$router->get('/dashboard', [UserController::class, 'dashboard']);
$router->get('/section-demand', [UserController::class, 'sectionDemand']);
$router->get('/enrollment-plan', [UserController::class, 'enrollmentPlan']);
$router->get('/alternative-sections', [UserController::class, 'alternativeSections']);
$router->get('/enrollment-updates', [UserController::class, 'enrollmentUpdates']);
$router->get('/change-password', [UserController::class, 'changePassword']);

// Home route
$router->get('/', [LoginController::class, 'showLogin']);

// ============ API ROUTES ============

// Users API
$router->get('/api/users', [ApiController::class, 'getUsers']);
$router->get('/api/users/detail', [ApiController::class, 'getUser']);
$router->post('/api/users', [ApiController::class, 'createUser']);
$router->put('/api/users', [ApiController::class, 'updateUser']);
$router->delete('/api/users', [ApiController::class, 'deleteUser']);

// Courses API
$router->get('/api/courses', [ApiController::class, 'getCourses']);
$router->post('/api/courses', [ApiController::class, 'createCourse']);
$router->put('/api/courses', [ApiController::class, 'updateCourse']);
$router->delete('/api/courses', [ApiController::class, 'deleteCourse']);

// Sections API
$router->get('/api/sections', [ApiController::class, 'getSections']);
$router->post('/api/sections', [ApiController::class, 'createSection']);
$router->put('/api/sections', [ApiController::class, 'updateSection']);
$router->delete('/api/sections', [ApiController::class, 'deleteSection']);

