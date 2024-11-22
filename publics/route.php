<?php
if (!defined('APP_RUNNING')) {
    require_once __DIR__ . '/../views/codepages/codes/403.php';
    exit();
}

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

// Route untuk halaman utama
$routes->add('home', new Route('/', [
    '_controller' => 'App\Controller\HomeController::index',
]));

// Route untuk form pendaftaran
$routes->add('registration', new Route('/registration', [
    '_controller' => 'App\Controller\UserController::createUser',
]));

// Route untuk halaman pertanyaan
$routes->add('questions', new Route('/penunjangmasadepan', [
    '_controller' => 'App\Controller\QuestionController::show',
]));

// Route untuk menyimpan jawaban
$routes->add('save_answers', new Route('/save-answers', [
    '_controller' => 'App\Controller\AnswerController::saveAnswer',
]));

// Route untuk halaman hasil
$routes->add('result', new Route('/result', [
    '_controller' => 'App\Controller\AnswerController::showResult',
]));

// Route untuk dashboard admin
$routes->add('admin_dashboard', new Route('/dash', [
    '_controller' => 'App\Controller\AdminController::showDashboard'
]));

// Route untuk login admin
$routes->add('admin_login', new Route('/login', [
    '_controller' => 'App\Controller\AdminController::showLogin'
]));

// Route untuk proses login
$routes->add('admin_login_process', new Route('/login/process', [
    '_controller' => 'App\Controller\AdminController::processLogin'
]));

// Route untuk logout
$routes->add('admin_logout', new Route('/logout', [
    '_controller' => 'App\Controller\AdminController::logout'
]));

// Route untuk assets
$routes->add('assets', new Route('/assets/{type}/{file}', [
    '_controller' => 'App\Controller\AssetController::serve',
    'requirements' => [
        'type' => 'css|js|media',
        'file' => '.+'
    ]
]));

// Route untuk halaman login
$routes->add('login', new Route('/login', [
    '_controller' => 'App\Controller\AuthController::showLogin'
]));

// Route untuk proses login
// $routes->add('login_process', new Route('/login/process', [
//     '_controller' => 'App\Controller\AuthController::processLogin'
// ]));

// Route untuk logout
$routes->add('logout', new Route('/logout', [
    '_controller' => 'App\Controller\AuthController::logout'
]));

// Route untuk melihat jawaban dengan hash
$routes->add('view_answers', new Route('/lihatapcb', [
    '_controller' => 'App\Controller\AnswerController::viewAnswers',
    'methods' => ['GET']
]));

return $routes;
