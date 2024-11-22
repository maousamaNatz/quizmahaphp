<?php
namespace App\Controller;

class BaseController
{
    protected function __construct()
    {
        if (!defined('APP_RUNNING')) {
            $this->blockAccess();
        }

        if (strpos($_SERVER['SCRIPT_FILENAME'], '/src/Controller/') !== false) {
            $this->blockAccess();
        }
    }

    protected function blockAccess()
    {
        http_response_code(403);
        if (file_exists(__DIR__ . '/../../views/codepages/codes/403.php')) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
        } else {
            echo "403 Forbidden";
        }
        exit();
    }

    protected function validateCSRF($token)
    {
        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
            throw new \Exception('Token CSRF tidak valid', 403);
        }
    }
} 