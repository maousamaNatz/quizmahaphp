<?php

namespace App\Middleware;

use App\Controller\AdminController;
use App\Helpers\SessionHelper;

class AdminAuthMiddleware {
    public static function authenticate() {
        SessionHelper::startSession();
        $adminController = new AdminController();
        
        if (!$adminController->isLoggedIn()) {
            header('Location: /traceritesa/tracer/views/codepages/codes/401.php');
            exit();
        }
    }
} 