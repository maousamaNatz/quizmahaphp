<?php
namespace App\Models;

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
    }

    /**
     * Creates a new user in the database.
     * 
     * @return bool True if the user was created successfully, false otherwise.
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . " (nama, nim, email, tgl_lahir, thn_lulus, perguruan, nik, npwp)
                  VALUES (:nama, :nim, :email, :tgl_lahir, :thn_lulus, :perguruan, :nik, :npwp)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':nama', $this->nama); 
        $stmt->bindParam(':nim', $this->nim);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':tgl_lahir', $this->tgl_lahir);
        $stmt->bindParam(':thn_lulus', $this->thn_lulus);
        $stmt->bindParam(':perguruan', $this->perguruan);
        $stmt->bindParam(':nik', $this->nik);
        $stmt->bindParam(':npwp', $this->npwp);

        // Execute query
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId(); // Get the newly created user ID
            error_log("New user created with ID: " . $this->id);
            return true;
        }

        error_log("Failed to create user");
        return false;
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
}
?>
