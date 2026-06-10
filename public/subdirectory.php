<?php

/**
 * Dynamic subdirectory routing override for local Apache servers (e.g. XAMPP).
 * This dynamically resolves the project folder name and ensures that Laravel's
 * router correctly matches route paths when accessed without '/public/'.
 */

// Normalize paths to use forward slashes
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');

$subfolder = '';
if (!empty($docRoot) && strpos($projectRoot, $docRoot) === 0) {
    $subfolder = substr($projectRoot, strlen($docRoot));
    $subfolder = '/' . trim($subfolder, '/');
}

$subfolder = rtrim($subfolder, '/'); // Ensure no trailing slash

if (!empty($subfolder)) {
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], $subfolder) === 0) {
        if (strpos($_SERVER['REQUEST_URI'], $subfolder . '/public') === false) {
            $_SERVER['SCRIPT_NAME'] = $subfolder . '/index.php';
            $_SERVER['PHP_SELF'] = $subfolder . '/index.php';
        }
    }
}
