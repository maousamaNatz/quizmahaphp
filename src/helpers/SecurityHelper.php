<?php
namespace App\Helpers;

class SecurityHelper
{
    private static $restrictedDirs = [
        '/src/Helpers/',
        '/src/Middleware/',
        '/src/config/',
        '/src/Controller/',
        '/vendor/',
        '/logs/'
    ];

    private static $allowedAssetTypes = [
        'css' => ['styles.css', 'style.css'],
        'js' => ['main-DHkgdsrw.js'],
        'media' => ['logos.png']
    ];

    public static function validateAccess()
    {
        $currentPath = $_SERVER['SCRIPT_FILENAME'];
        
        // Cek akses ke direktori terlarang
        foreach (self::$restrictedDirs as $dir) {
            if (strpos($currentPath, $dir) !== false) {
                self::blockAccess();
            }
        }

        // Validasi akses asset
        if (strpos($_SERVER['REQUEST_URI'], '/assets/') !== false) {
            self::validateAssetAccess();
        }
    }

    public static function validateAssetAccess()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));
        
        if (count($parts) < 3 || 
            !isset(self::$allowedAssetTypes[$parts[1]]) || 
            !in_array($parts[2], self::$allowedAssetTypes[$parts[1]])) {
            self::blockAccess();
        }
    }

    private static function blockAccess()
    {
        require_once __DIR__ . '/../../views/codepages/codes/403.php';
        exit();
    }
} 