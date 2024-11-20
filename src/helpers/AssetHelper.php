<?php

namespace App\Helpers;

class AssetHelper
{
    private static $basePath = null;

    public static function url($path)
    {
        if (self::$basePath === null) {
            self::initBasePath();
        }
        return self::$basePath . '/views/assets/' . ltrim($path, '/');
    }

    private static function initBasePath()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $dirName = dirname($scriptName);
        
        // Jika di root directory
        if ($dirName == '/') {
            self::$basePath = '';
        } else {
            // Hapus '/publics' atau '/views' dari path jika ada
            $basePath = preg_replace('/(\/publics|\/views).*/', '', $dirName);
            self::$basePath = rtrim($basePath, '/');
        }
    }

    public static function setBasePath($path)
    {
        self::$basePath = $path;
    }
} 