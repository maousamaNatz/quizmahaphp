<?php
namespace App\Middleware;

class AuthMiddleware
{
    public static function authenticate()
    {
        if (!defined('APP_RUNNING')) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
            exit();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /traceritesa/tracer/login');
            exit();
        }
    }

    public static function validatePermission($requiredRole)
    {
        if (!isset($_SESSION['user_role']) || 
            $_SESSION['user_role'] !== $requiredRole) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
            exit();
        }
    }
} 