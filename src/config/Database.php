<?php
namespace App\Config;

class Database {
    private $host = 'localhost';
    private $db_name = 'apacona';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function connect() {
        try {
            if (!$this->conn) {
                $this->conn = new \PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password,
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
            }
            return $this->conn;
        } catch(\PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            throw new \Exception("Koneksi database gagal: " . $e->getMessage());
        }
    }
    
    // Alias untuk connect() untuk kompatibilitas
    public function getConnection() {
        return $this->connect();
    }
    
    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new static();
        }
        return $instance;
    }
}
