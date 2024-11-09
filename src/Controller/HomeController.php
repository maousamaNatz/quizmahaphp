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
    /**
     * Menampilkan halaman utama aplikasi
     * 
     * @return void Memuat file index.php dari direktori publics
     */
    public function index()
    {
        require_once __DIR__ . '/../../publics/index.php';
    }
} 