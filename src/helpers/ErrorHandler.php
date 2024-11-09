<?php
namespace App\Helpers;

/**
 * Class ErrorHandler
 * 
 * @package App\Helpers
 * Menangani kesalahan HTTP dan mengarahkan ke halaman yang sesuai
 */
class ErrorHandler {
    /**
     * Menangani kode kesalahan HTTP
     * 
     * @param int $code Kode status HTTP
     * @return void
     */
    public static function handle($code) {
        http_response_code($code);
        
        $baseUrl = '/traceritesa/tracer/views/codepages/';
        
        switch ($code) {
            case 404:
                header("Location: {$baseUrl}404.php"); // Halaman tidak ditemukan
                break;
            case 403:
                header("Location: {$baseUrl}403.php"); // Akses ditolak
                break;
            case 500:
                header("Location: {$baseUrl}500.php"); // Kesalahan server internal
                break;
            default:
                header("Location: {$baseUrl}404.php");
                break;
        }
        exit();
    }
}