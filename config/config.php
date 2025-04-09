<?php
// Définir l'environnement (development ou production)
define('ENVIRONMENT', 'development');

// Load environment variables from .env file
function loadEnv($path = '.env') {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Remove quotes if present
        if (strpos($value, '"') === 0 || strpos($value, "'") === 0) {
            $value = substr($value, 1, -1);
        }
        
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
    return true;
}

// Load environment variables
loadEnv();

// Application configuration
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/eclisse');
define('WHATSAPP_NUMBER', getenv('WHATSAPP_NUMBER') ?: '212XXXXXXXXX');
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'admin@eclisse.com');
define('NOTIFICATION_EMAIL', getenv('NOTIFICATION_EMAIL') ?: 'noreply@eclisse.com');
define('SESSION_TIMEOUT', getenv('SESSION_TIMEOUT') ?: 3600);

// Include database configuration
require_once 'database.php';

// Initialize database
initDatabase();

// Helper functions
function redirect($url) {
    header("Location: " . BASE_URL . "/" . $url);
    exit();
}

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Session management
session_start();
session_regenerate_id(true); // Prevent session fixation

// Set session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
    // Session expired
    session_unset();
    session_destroy();
}

$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('admin/login.php');
    }
}
?>
