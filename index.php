<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/config/Database.php';

use App\Helpers\SessionHelper;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use App\Helpers\ErrorHandler;

SessionHelper::startSession();

try {
    // Load routes
    $routes = require __DIR__ . '/publics/route.php';
    
    // Buat request context
    $request = Request::createFromGlobals();
    $context = new RequestContext();
    $context->fromRequest($request);
    
    // URL matcher
    $matcher = new UrlMatcher($routes, $context);
    
    // Get current path
    $pathInfo = $request->getPathInfo();
    
    // Match route
    $parameters = $matcher->match($pathInfo);
    
    // Execute controller
    $controller = $parameters['_controller'];
    list($controllerClass, $method) = explode('::', $controller);
    
    // Instantiate controller and call method
    $controllerInstance = new $controllerClass();
    $response = $controllerInstance->$method($parameters);
    
} catch (\Exception $e) {
    error_log($e->getMessage());
    ErrorHandler::handle(500);
}
?>