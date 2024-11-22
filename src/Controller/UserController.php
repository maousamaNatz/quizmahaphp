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
                throw new \Exception("Koneksi database gagal");
            }
            
            // Validasi data
            $this->validateUserData($data);
            error_log("Data validation passed");
            
            // Set data user
            $this->setUserData($data);
            error_log("User data set successfully");
            
            // Create user
            if ($this->user->create()) {
                $userId = $this->user->id;
                error_log("User created successfully with ID: " . $userId);
                
                return [
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'user_id' => $userId
                ];
            }
            
            throw new \Exception("Gagal menyimpan data");
            
        } catch (\Exception $e) {
            error_log("ERROR in createUser: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function validateUserData($data) {
        try {
            // Validasi field yang required
            $required = ['nama', 'nim', 'email', 'tgl_lahir', 'thn_lulus', 'perguruan'];
            $errors = [];
            
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $errors[] = "Field " . ucfirst($field) . " harus diisi";
                }
            }
            
            // Validasi field unik
            $uniqueFields = [
                'nim' => 'NIM',
                'email' => 'Email',
                'nik' => 'NIK',
                'npwp' => 'NPWP'
            ];
            
            foreach ($uniqueFields as $field => $label) {
                if (!empty($data[$field]) && $this->user->exists($field, $data[$field])) {
                    $errors[] = "$label sudah terdaftar";
                }
            }
            
            // Validasi format email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format email tidak valid";
            }
            
            // Validasi panjang NIK dan NPWP jika diisi
            if (!empty($data['nik']) && strlen($data['nik']) !== 16) {
                $errors[] = "NIK harus 16 digit";
            }
            
            if (!empty($data['npwp']) && strlen($data['npwp']) !== 15) {
                $errors[] = "NPWP harus 15 digit";
            }
            
            // Jika ada error, throw exception
            if (!empty($errors)) {
                throw new \Exception(implode(", ", $errors));
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Validation error: " . $e->getMessage());
            throw $e;
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

    public function generateHash($userId) {
        $key = 'penunjang_' . date('Ymd');
        return hash('sha256', $userId . $key);
    }

    private function decodeHash($hash) {
        try {
            $query = "SELECT id FROM users WHERE SHA2(CONCAT(id, 'penunjang_" . date('Ymd') . "'), 256) = :hash";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':hash', $hash);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : null;
        } catch (\PDOException $e) {
            error_log("Error decoding hash: " . $e->getMessage());
            return null;
        }
    }

    public function getUserById($userId) {
        try {
            $query = "SELECT id, nama, nim, perguruan, thn_lulus, email, nik, tgl_lahir, npwp 
                     FROM users 
                     WHERE id = :user_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                throw new \Exception('User tidak ditemukan');
            }
            
            return $result;
            
        } catch (\PDOException $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return null;
        }
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
