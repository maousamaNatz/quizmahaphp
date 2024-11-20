<?php

namespace App\Controller;

/**
 * Class HomeController
 * 
 * @package App\Controller
 * Mengelola tampilan halaman utama aplikasi
 */
class HomeController
{
    public function __construct()
    {
        // Cek akses langsung ke controller
        if (strpos($_SERVER['SCRIPT_FILENAME'], '/src/Controller/') !== false) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
            exit();
        }
    }

    /**
     * Menampilkan halaman utama aplikasi
     * 
     * @return void Memuat file index.php dari direktori publics
     */
    public function index()
    {
        // Definisikan konstanta untuk mengontrol akses
        if (!defined('APP_RUNNING')) {
            define('APP_RUNNING', true);
        }
        
        require_once __DIR__ . '/../../publics/index.php';
    }
} 