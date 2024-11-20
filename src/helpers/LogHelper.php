<?php
namespace App\Helpers;

/**
 * Class LogHelper
 * 
 * @package App\Helpers
 * Mengelola pencatatan log aplikasi secara terpusat
 */
class LogHelper {
    /**
     * @var string Lokasi file log utama
     */
    private static $logFile = __DIR__ . '/../../logs/app.log';
    
    /**
     * @var array Level log yang tersedia
     */
    private static $logLevels = [
        'DEBUG' => 100,
        'INFO' => 200,
        'WARNING' => 300,
        'ERROR' => 400,
        'CRITICAL' => 500
    ];

    /**
     * Mencatat pesan log ke dalam file
     * 
     * @param string $message Pesan yang akan dicatat
     * @param string $level Level log (DEBUG, INFO, WARNING, ERROR, CRITICAL)
     * @param array $context Data tambahan untuk log
     * @return void
     */
    public static function log($message, $level = 'INFO', array $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $level = strtoupper($level);
        
        if (!isset(self::$logLevels[$level])) {
            $level = 'INFO';
        }

        // Konversi encoding pesan
        $message = mb_convert_encoding($message, 'UTF-8', 'auto');
        
        // Format konteks jika ada
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        
        // Format pesan log
        $logMessage = "[$timestamp][$level] $message$contextStr\n";
        
        // Pastikan direktori log ada
        self::ensureLogDirectoryExists();
        
        // Tulis log ke file
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log untuk level DEBUG
     */
    public static function debug($message, array $context = []) {
        self::log($message, 'DEBUG', $context);
    }

    /**
     * Log untuk level INFO
     */
    public static function info($message, array $context = []) {
        self::log($message, 'INFO', $context);
    }

    /**
     * Log untuk level WARNING
     */
    public static function warning($message, array $context = []) {
        self::log($message, 'WARNING', $context);
    }

    /**
     * Log untuk level ERROR
     */
    public static function error($message, array $context = []) {
        self::log($message, 'ERROR', $context);
    }

    /**
     * Log untuk level CRITICAL
     */
    public static function critical($message, array $context = []) {
        self::log($message, 'CRITICAL', $context);
    }

    /**
     * Membersihkan file log
     */
    public static function clearLog() {
        if(file_exists(self::$logFile)) {
            file_put_contents(self::$logFile, '');
        }
    }

    /**
     * Memastikan direktori log ada
     */
    private static function ensureLogDirectoryExists() {
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }
} 