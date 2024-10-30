<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $nama;
    public $nim;
    public $email;
    public $password;
    public $tgl_lahir;
    public $thn_lulus;
    public $perguruan;
    public $nik;
    public $npwp;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (nama, nim, email, tgl_lahir, thn_lulus, perguruan, nik, npwp)
                  VALUES (:nama, :nim, :email, :tgl_lahir, :thn_lulus, :perguruan, :nik, :npwp)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nama', $this->nama); 
        $stmt->bindParam(':nim', $this->nim);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':tgl_lahir', $this->tgl_lahir);
        $stmt->bindParam(':thn_lulus', $this->thn_lulus);
        $stmt->bindParam(':perguruan', $this->perguruan);
        $stmt->bindParam(':nik', $this->nik);
        $stmt->bindParam(':npwp', $this->npwp);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId(); // Dapatkan ID yang baru dibuat
            error_log("New user created with ID: " . $this->id);
            return true;
        }
        error_log("Failed to create user");
        return false;
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                return true;
            }
        }
        return false;
    }
}
?>
