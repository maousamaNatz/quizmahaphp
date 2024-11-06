<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    public function createUser($data) {
        // Validasi data
        if (empty($data['nama']) || empty($data['nim']) || empty($data['email']) || empty($data['perguruan'])) {
            return ['status' => 'error', 'message' => 'Semua field harus diisi'];
        }
        
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Email tidak valid'];
        }
        
        // Normalisasi data untuk menghapus CRLF
        $data = array_map(function($value) {
            return str_replace(["\r\n", "\r", "\n"], ' ', $value);
        }, $data);
        
        try {
            $this->user->nama = $data['nama'];
            $this->user->nim = $data['nim'];
            $this->user->email = $data['email'];
            $this->user->tgl_lahir = $data['tgl_lahir'];
            $this->user->thn_lulus = $data['thn_lulus'];
            $this->user->perguruan = $data['perguruan'];
            $this->user->nik = $data['nik'] ?? null;
            $this->user->npwp = $data['npwp'] ?? null;

            if ($this->user->create()) {
                return [
                    'status' => 'success',
                    'message' => 'User berhasil dibuat',
                    'user_id' => $this->user->id
                ];
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => 'Database error'];
        }
        
        return ['status' => 'error', 'message' => 'Gagal membuat user'];
    }
}

?>
