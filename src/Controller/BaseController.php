<?php
namespace App\Controller;

class BaseController
{
    protected function __construct()
    {
        if (!defined('APP_RUNNING')) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
            exit();
        }

        if (strpos($_SERVER['SCRIPT_FILENAME'], '/src/Controller/') !== false) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
            exit();
        }
    }

    protected function validateCSRF($token)
    {
        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
            throw new \Exception('Token CSRF tidak valid', 403);
        }
    }
} 