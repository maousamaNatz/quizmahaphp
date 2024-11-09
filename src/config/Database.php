<?php
namespace App\Config;

use PDO;
use PDOException;
use Dotenv\Dotenv;

/**
 * Class Database
 * 
 * @package App\Config
 * Mengelola koneksi database aplikasi
 */
class Database {
    /**
     * @var string Host database
     */
    private $host;

    /**
     * @var string Nama database
     */
    private $db_name;

    /**
     * @var string Username database
     */
    private $username;

    /**
     * @var string Password database
     */
    private $password;

    /**
     * @var PDO Objek koneksi database
     */
    private $conn;

    /**
     * Konstruktor untuk inisialisasi konfigurasi database
     */
    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->host = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        $this->db_name = $_ENV['DB_NAME'];
    }

    /**
     * Membuat koneksi ke database
     * 
     * @return PDO Objek koneksi database
     * @throws PDOException Jika koneksi gagal
     */
    public function connect() {
        $this->conn = null;
    
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Kesalahan koneksi: " . $e->getMessage());
            throw $e;
        }
    
        return $this->conn;
    }
}
