<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/config/Database.php';

use App\Helpers\SessionHelper;
use App\Helpers\ErrorHandler;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;

// Register error handler
ErrorHandler::register();

SessionHelper::startSession();

try {
    $routes = require __DIR__ . '/publics/route.php';
    
    $request = Request::createFromGlobals();
    $context = new RequestContext();
    $context->fromRequest($request);
    
    $matcher = new UrlMatcher($routes, $context);
    $pathInfo = $request->getPathInfo();
    $parameters = $matcher->match($pathInfo);
    
    $controller = $parameters['_controller'];
    list($controllerClass, $method) = explode('::', $controller);
    
    $controllerInstance = new $controllerClass();
    $response = $controllerInstance->$method($parameters);
    
} catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    ErrorHandler::handleException(new \Exception('Halaman tidak ditemukan', 404));
} catch (\Exception $e) {
    ErrorHandler::handleException($e);
}
?>