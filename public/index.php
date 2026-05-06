<?php
// Start session early
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();
require_once __DIR__ . '/../routes/web.php';

// Dispatch the current URI
$router->dispatch($_SERVER['REQUEST_URI']);