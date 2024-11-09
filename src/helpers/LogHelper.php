<?php
namespace App\Helpers;

/**
 * Class LogHelper
 * 
 * @package App\Helpers
 * Mengelola pencatatan log aplikasi
 */
class LogHelper {
    /**
     * @var string Lokasi file log
     */
    private static $logFile = __DIR__ . '/../../logs/error.log';
    
    /**
     * Mencatat pesan log ke dalam file
     * 
     * @param string $message Pesan yang akan dicatat
     * @param string $level Level log (INFO, ERROR, dll)
     * @return void
     */
    public static function log($message, $level = 'INFO') {
        if (!file_exists(dirname(self::$logFile))) {
            mkdir(dirname(self::$logFile), 0777, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        
        error_log($formattedMessage, 3, self::$logFile);
    }
} 