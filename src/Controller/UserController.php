<?php

namespace App\Controller;

use PDO;
use PDOException;
use App\Models\User;
use App\Config\Database;
use Exception;

/**
 * Class UserController
 * 
 * @package App\Controller
 * Controller untuk menangani operasi terkait pengguna
 */
class UserController 
{
    /** @var \PDO $db Koneksi database */
    private $db;
    
    /** @var User $user Instance model User */
    private $user;

    /**
     * Constructor UserController
     * Menginisialisasi koneksi database dan model User
     */
    public function __construct() {
        // Cek akses langsung ke controller
        if (strpos($_SERVER['SCRIPT_FILENAME'], '/src/Controller/') !== false) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
            exit();
        }
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    /**
     * Membuat pengguna baru
     * 
     * @param array $data Data pengguna yang berisi:
     *                    - nama: string Nama lengkap
     *                    - nim: string NIM
     *                    - email: string Email
     *                    - tgl_lahir: string Tanggal lahir
     *                    - thn_lulus: string Tahun lulus
     *                    - perguruan: string Nama perguruan tinggi
     *                    - nik: string|null NIK (opsional)
     *                    - npwp: string|null NPWP (opsional)
     * @return array Status operasi ['status' => string, 'message' => string, 'user_id' => int|null]
     */
    public function createUser($data) {
        try {
            error_log("=== Start Create User Process ===");
            error_log("Input data: " . json_encode($data));
            
            // Validasi koneksi database
            if (!$this->db) {
                error_log("ERROR: Koneksi database gagal");
                throw new \Exception("Koneksi database gagal");
            }
            
            // Validasi data
            $this->validateUserData($data);
            error_log("Data validation passed");
            
            // Set data user
            $this->setUserData($data);
            error_log("User data set successfully");
            
            // Coba create user
            if ($this->user->create()) {
                $userId = $this->user->id;
                error_log("User created successfully with ID: " . $userId);
                
                return [
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'user_id' => $userId
                ];
            }
            
            error_log("ERROR: Failed to create user");
            throw new \Exception("Gagal menyimpan data");
            
        } catch (\Exception $e) {
            error_log("ERROR in createUser: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            error_log("POST data: " . print_r($data, true));
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function validateUserData($data) {
        $required = ['nama', 'nim', 'email', 'tgl_lahir', 'thn_lulus', 'perguruan'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Field $field wajib diisi");
            }
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Format email tidak valid");
        }
    }

    private function setUserData($data) {
        $this->user->nama = $data['nama'];
        $this->user->nim = $data['nim'];
        $this->user->email = $data['email'];
        $this->user->tgl_lahir = $data['tgl_lahir'];
        $this->user->thn_lulus = $data['thn_lulus'];
        $this->user->perguruan = $data['perguruan'];
        $this->user->nik = $data['nik'] ?? null;
        $this->user->npwp = $data['npwp'] ?? null;
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
