<?php
ob_start();

define('APP_RUNNING', true);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Helpers/SecurityHelper.php';

// Validasi akses
App\Helpers\SecurityHelper::validateAccess();

// Kode yang sudah ada sebelumnya
require_once __DIR__ . '/src/config/Database.php';

use App\Helpers\SessionHelper;
use App\Helpers\ErrorHandler;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use App\Helpers\AssetHelper;

// Register error handler
ErrorHandler::register();

SessionHelper::startSession();

// Daftar path yang diizinkan untuk assets
$allowedAssetPaths = [
    'css' => ['styles.css', 'style.css'],
    'js' => ['main-DHkgdsrw.js'],
    'media' => ['logos.png']
];

// Konfigurasi error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

try {
    $routes = require __DIR__ . '/publics/route.php';
    
    $request = Request::createFromGlobals();
    $context = new RequestContext();
    $context->fromRequest($request);
    
    $matcher = new UrlMatcher($routes, $context);
    $pathInfo = $request->getPathInfo();
    
    // Validasi path untuk assets
    if (strpos($pathInfo, '/assets/') === 0) {
        $parts = explode('/', trim($pathInfo, '/'));
        if (count($parts) < 3 || 
            !isset($allowedAssetPaths[$parts[1]]) || 
            !in_array($parts[2], $allowedAssetPaths[$parts[1]])) {
            throw new \Exception('Asset tidak ditemukan', 404);
        }
    }
    
    // Validasi format path
    if (!preg_match('/^[a-zA-Z0-9\/_-]*$/', $pathInfo)) {
        throw new \Exception('URL tidak valid', 400);
    }
    
    $parameters = $matcher->match($pathInfo);
    $controller = $parameters['_controller'];
    list($controllerClass, $method) = explode('::', $controller);
    
    // Validasi controller dan method
    if (!class_exists($controllerClass) || !method_exists($controllerClass, $method)) {
        throw new \Exception('Controller tidak ditemukan', 404);
    }
    
    $controllerInstance = new $controllerClass();
    $response = $controllerInstance->$method($parameters);
    
} catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    ErrorHandler::handleException(new \Exception('Halaman tidak ditemukan', 404));
} catch (\Exception $e) {
    $code = $e->getCode() ?: 500;
    ErrorHandler::handleException(new \Exception($e->getMessage(), $code));
}

ob_end_flush();
?>