<?php
// Start session early
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session Lock Optimization:
// Release session file lock early on GET requests to prevent slow DB queries or hung processes
// from locking subsequent page loads/navigations.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Read and temporarily cache flash messages so they don't get lost
    $flashError = $_SESSION['error'] ?? null;
    $flashSuccess = $_SESSION['success'] ?? null;

    // Clear flash messages from the disk session file
    unset($_SESSION['error']);
    unset($_SESSION['success']);

    // Save session data and release the lock immediately
    session_write_close();

    // Restore the flash messages to the in-memory $_SESSION superglobal 
    // so existing views can still read/unset them safely
    if ($flashError !== null) {
        $_SESSION['error'] = $flashError;
    }
    if ($flashSuccess !== null) {
        $_SESSION['success'] = $flashSuccess;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();
require_once __DIR__ . '/../routes/web.php';

// Dispatch the current URI
$router->dispatch($_SERVER['REQUEST_URI']);