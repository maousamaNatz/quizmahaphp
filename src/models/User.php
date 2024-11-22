<?php
namespace App\Models;

use PDO;
use PDOException;

/**
 * Class User
 * 
 * @package App\Models
 * Represents a user in the system with methods for creating and authenticating users.
 */
class User {
    /**
     * @var \PDO $conn Database connection object
     */
    private $conn;

    /**
     * @var string $table Name of the users table
     */
    private $table = 'users';

    // User properties
    public $id;           // User ID
    public $nama;         // User name
    public $nim;          // User NIM
    public $email;        // User email
    public $password;     // User password
    public $tgl_lahir;    // Date of birth
    public $thn_lulus;    // Graduation year
    public $perguruan;    // University
    public $nik;          // National ID number
    public $npwp;         // Tax number

    /**
     * Constructor for the User class.
     * 
     * @param \PDO $db The database connection
     */
    public function __construct($db) {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Creates a new user in the database.
     * 
     * @return bool True if the user was created successfully, false otherwise.
     */
    public function create() {
        try {
            error_log("=== Starting User Creation in Model ===");
            
            $query = "INSERT INTO users (nama, nim, email, tgl_lahir, thn_lulus, perguruan, nik, npwp) 
                     VALUES (:nama, :nim, :email, :tgl_lahir, :thn_lulus, :perguruan, :nik, :npwp)";
            
            error_log("Preparing query: " . $query);
            
            $stmt = $this->conn->prepare($query);
            
            // Bind parameters dengan logging
            $params = [
                ':nama' => $this->nama,
                ':nim' => $this->nim,
                ':email' => $this->email,
                ':tgl_lahir' => $this->tgl_lahir,
                ':thn_lulus' => $this->thn_lulus,
                ':perguruan' => $this->perguruan,
                ':nik' => $this->nik,
                ':npwp' => $this->npwp
            ];
            
            error_log("Parameters to bind: " . json_encode($params));
            
            foreach($params as $key => $value) {
                $stmt->bindValue($key, $value);
                error_log("Bound parameter $key with value: $value");
            }
            
            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                error_log("User created successfully with ID: " . $this->id);
                return true;
            }
            
            $error = $stmt->errorInfo();
            error_log("Query execution failed: " . print_r($error, true));
            return false;
            
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Authenticates a user based on the email and password.
     * 
     * @return bool True if login was successful, false otherwise.
     */
    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            // Verify password
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                return true;
            }
        }
        return false;
    }
    
    public function exists($field, $value) {
        try {
            if (empty($value)) {
                return false;
            }
            
            $query = "SELECT COUNT(*) FROM {$this->table} WHERE {$field} = :value";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            error_log("Error checking existence: " . $e->getMessage());
            throw new \Exception("Gagal memeriksa data");
        }
    }
}
?>
