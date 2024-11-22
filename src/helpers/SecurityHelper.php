<?php
namespace App\Helpers;

class SecurityHelper
{
    private static $restrictedDirs = [
        '/src/',
        '/vendor/',
        '/views/',
        '/logs/',
        '/assets/',
        '/public/',
        '/components/',
        '/codepages/',
        '/dist/',
        '/config/',
        '/Controller/',
        '/Models/',
        '/Middleware/'
    ];

    private static $allowedAssetTypes = [
        'css' => ['styles.css', 'style.css'],
        'js' => ['main-DHkgdsrw.js'],
        'media' => ['logos.png']
    ];

    public static function validateAccess()
    {
        // Validasi akses langsung ke file PHP
        $currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
        $requestUri = str_replace('\\', '/', $_SERVER['REQUEST_URI']);
        
        // Block akses langsung ke semua file PHP kecuali index.php
        if (strpos($currentPath, '.php') !== false && 
            basename($currentPath) !== 'index.php') {
            self::blockAccess();
        }

        // Cek akses ke direktori terlarang
        foreach (self::$restrictedDirs as $dir) {
            if (strpos($requestUri, $dir) !== false || 
                strpos($currentPath, $dir) !== false) {
                self::blockAccess();
            }
        }

        // Validasi akses asset
        if (strpos($requestUri, '/assets/') !== false) {
            self::validateAssetAccess();
        }
    }

    private static function blockAccess()
    {
        http_response_code(403);
        if (file_exists(__DIR__ . '/../../views/codepages/codes/403.php')) {
            include __DIR__ . '/../../views/codepages/codes/403.php';
        } else {
            echo "403 Forbidden";
        }
        exit();
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
} 