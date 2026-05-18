<?php
use App\Controllers\LoginController;
use App\Controllers\UserController;
use App\Controllers\ApiController;
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
$router->get('/courses', [UserController::class, 'listCourses']);
$router->get('/sections', [UserController::class, 'listSections']);
$router->get('/dashboard', [UserController::class, 'dashboard']);
$router->get('/section-demand', [UserController::class, 'sectionDemand']);
$router->get('/enrollment-plan', [UserController::class, 'enrollmentPlan']);
$router->get('/alternative-sections', [UserController::class, 'alternativeSections']);
$router->get('/enrollment-updates', [UserController::class, 'enrollmentUpdates']);
$router->get('/change-password', [UserController::class, 'changePassword']);

// Admin routes
$router->get('/admin/student-interest', [UserController::class, 'adminStudentInterest']);
$router->get('/admin/enrollment-updates', [UserController::class, 'adminEnrollmentUpdates']);
$router->post('/admin/enrollment-updates/add', [AdminController::class, 'addUpdate']);
$router->post('/admin/enrollment-updates/edit', [AdminController::class, 'editUpdate']);
$router->post('/admin/enrollment-updates/delete', [AdminController::class, 'deleteUpdate']);


$router->get('/admin/management', [AdminController::class, 'management']);
$router->post('/admin/management/add', [AdminController::class, 'add']);
$router->post('/admin/management/edit', [AdminController::class, 'edit']);
$router->post('/admin/management/delete', [AdminController::class, 'delete']);

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

// Admin API
$router->get('/api/admin/list', [ApiController::class, 'getAdmins']);
$router->get('/api/admin/detail', [ApiController::class, 'getAdmin']);
$router->post('/api/admin/create', [ApiController::class, 'createAdmin']);
$router->put('/api/admin/update', [ApiController::class, 'updateAdmin']);
$router->delete('/api/admin/delete', [ApiController::class, 'deleteAdmin']);

// Student API
$router->get('/api/student/enrollment-plan', [ApiController::class, 'getStudentEnrollmentPlan']);
$router->get('/api/student/alternative-sections', [ApiController::class, 'getAlternativeSections']);
$router->put('/api/student/switch-section', [ApiController::class, 'switchPlannedSection']);
$router->post('/api/student/add-section', [ApiController::class, 'addSectionToEnrollment']);
$router->delete('/api/student/remove-section', [ApiController::class, 'removeSectionFromEnrollment']);
$router->get('/api/student/section-demand', [ApiController::class, 'getSectionDemand']);
$router->get('/api/student/interest-data', [ApiController::class, 'getStudentInterestData']);
