<?php

namespace App\Controller;

class AssetController 
{
    public function __construct() 
{
    if (!defined('APP_RUNNING')) {
        require_once __DIR__ . '/../../views/codepages/codes/403.php';
        exit();
        }
    }

    public function serve($type, $file)
    {
        $basePath = __DIR__ . '/../../views/assets/';
        $filePath = $basePath . $type . '/' . $file;
        
        if (!file_exists($filePath)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
        
        // Set content type header
        switch ($type) {
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'js':
                header('Content-Type: application/javascript');
                break;
            case 'media':
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                header('Content-Type: media/' . $ext);
                break;
        }
        
        readfile($filePath);
        exit;
    }
} 