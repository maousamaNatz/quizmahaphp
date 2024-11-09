<?php

namespace App\Helpers;

/**
 * Class SessionHelper
 * 
 * @package App\Helpers
 * Mengelola sesi pengguna dalam aplikasi
 */
class SessionHelper {
    /**
     * @var bool Status sesi sudah dimulai atau belum
     */
    private static $started = false;

    /**
     * Memulai sesi jika belum dimulai
     * 
     * @return void
     */
    public static function startSession() {
        if (!self::$started && session_status() === PHP_SESSION_NONE) {
            session_start();
            self::$started = true;
        }
    }

    /**
     * Menyimpan nilai ke dalam sesi
     * 
     * @param string $key Kunci untuk menyimpan nilai
     * @param mixed $value Nilai yang akan disimpan
     * @return void
     */
    public static function set($key, $value) {
        self::startSession();
        $_SESSION[$key] = $value;
    }

    /**
     * Mengambil nilai dari sesi
     * 
     * @param string $key Kunci untuk mengambil nilai
     * @param mixed $default Nilai default jika kunci tidak ditemukan
     * @return mixed Nilai yang tersimpan atau nilai default
     */
    public static function get($key, $default = null) {
        self::startSession();
        return $_SESSION[$key] ?? $default;
    }
} 