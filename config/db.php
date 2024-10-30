<?php
   require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Database {
    private $conn;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    public function connect() {
        $this->conn = null;
    
        try {
            $this->conn = new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            exit();
        }
    
        return $this->conn;
    }
    
}
?>
