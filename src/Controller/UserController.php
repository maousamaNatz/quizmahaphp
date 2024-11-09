<?php

namespace App\Controller;

use PDO;
use PDOException;
use App\Models\User;
use App\Config\Database;

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
            if (empty($data['nama']) || empty($data['nim']) || empty($data['email'])) {
                return ['status' => 'error', 'message' => 'Semua field wajib diisi'];
            }

            $this->user->nama = $data['nama'];
            $this->user->nim = $data['nim'];
            $this->user->email = $data['email'];
            $this->user->tgl_lahir = $data['tgl_lahir'];
            $this->user->thn_lulus = $data['thn_lulus'];
            $this->user->perguruan = $data['perguruan'];
            $this->user->nik = $data['nik'] ?? null;
            $this->user->npwp = $data['npwp'] ?? null;

            if ($this->user->create()) {
                $userId = $this->user->id;
                $_SESSION['user_id'] = $userId;
                error_log("New user created with ID: " . $userId);
                
                return [
                    'status' => 'success',
                    'message' => 'User berhasil dibuat',
                    'user_id' => $userId
                ];
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => 'Database error'];
        }
        
        return ['status' => 'error', 'message' => 'Gagal membuat user'];
    }
}
