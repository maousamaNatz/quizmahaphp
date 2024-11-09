<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

/**
 * Controller untuk menangani autentikasi pengguna
 * 
 * Class ini menangani proses registrasi dan login pengguna
 */
class AuthController {
    /** @var PDO Instance koneksi database */
    private $db;
    
    /** @var User Instance model User */
    private $user;

    /**
     * Constructor AuthController
     * Menginisialisasi koneksi database dan model User
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    /**
     * Melakukan registrasi pengguna baru
     *
     * @param array $data Data registrasi pengguna yang berisi:
     *                    - nama: string Nama lengkap pengguna
     *                    - nim: string Nomor Induk Mahasiswa
     *                    - email: string Alamat email pengguna
     *                    - password: string Password pengguna (akan di-hash)
     *                    - tgl_lahir: string Tanggal lahir (format: YYYY-MM-DD)
     *                    - thn_lulus: string Tahun kelulusan
     *                    - perguruan: string Nama perguruan tinggi
     *                    - nik: string|null Nomor Induk Kependudukan (opsional)
     *                    - npwp: string|null Nomor Pokok Wajib Pajak (opsional)
     * @return array Status registrasi ['status' => string, 'message' => string]
     */
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
            // Set session untuk pengguna
            session_start();
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['user_name'] = $this->user->nama;

            // Redirect ke halaman pertanyaan
            header('Location: questions.php');
            exit();
        } else {
            return ['status' => 'error', 'message' => 'Registration failed.'];
        }
    }

    /**
     * Melakukan proses login pengguna
     *
     * @param array $data Data login yang berisi:
     *                    - email: string Alamat email pengguna
     *                    - password: string Password pengguna
     * @return array Status login ['status' => string, 'message' => string]
     */
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
