<?php

/**
 * AnimeList Application
 * PHP Native MVC Framework
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', __DIR__);

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/core/',
        BASE_PATH . '/app/models/',
        BASE_PATH . '/app/controllers/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load helper functions
require_once BASE_PATH . '/core/Helper.php';

// Initialize and run the application
$app = new App();
$app->run();
