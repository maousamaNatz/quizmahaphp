<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Controller\AdminController;

$adminController = new AdminController();
$adminController->logout(); 