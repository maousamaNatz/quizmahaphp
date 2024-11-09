<?php

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
$routes->add('questions', new Route('/questions', [
    '_controller' => 'App\Controller\QuestionController::show',
]));

// Route untuk menyimpan jawaban
$routes->add('save_answers', new Route('/save-answers', [
    '_controller' => 'App\Controller\AnswerController::saveAnswer',
]));

// Route untuk halaman hasil
$routes->add('result', new Route('/result/{user_id}', [
    '_controller' => 'App\Controller\ResultController::show',
    'requirements' => ['user_id' => '\d+']
]));

// Route untuk dashboard admin
$routes->add('admin_dashboard', new Route('/admin/dashboard', [
    '_controller' => 'App\Controller\AdminController::dashboard',
]));

return $routes;
