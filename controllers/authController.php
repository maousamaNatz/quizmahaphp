<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    public function register($data) {
        $this->user->nama = $data['nama'];
        $this->user->nim = $data['nim'];
        $this->user->email = $data['email'];
        $this->user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->user->tgl_lahir = $data['tgl_lahir'];
        $this->user->thn_lulus = $data['thn_lulus'];
        $this->user->perguruan = $data['perguruan'];
        $this->user->nik = $data['nik'];
        $this->user->npwp = $data['npwp'];

        if ($this->user->create()) {
            // Set session for the user
            session_start();
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['user_name'] = $this->user->nama;

            // Redirect to questions page
            header('Location: questions.php');
            exit();
        } else {
            return ['status' => 'error', 'message' => 'Registration failed.'];
        }
    }

    public function login($data) {
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];

        if ($this->user->login()) {
            return ['status' => 'success', 'message' => 'Login successful.'];
        } else {
            return ['status' => 'error', 'message' => 'Invalid email or password.'];
        }
    }
}
?>
