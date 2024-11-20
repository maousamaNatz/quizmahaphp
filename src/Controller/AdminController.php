<?php

namespace App\Controller;

use App\Config\Database;
use App\Helpers\SessionHelper;

class AdminController {
    private $db;

    public function __construct() {
        // Cek akses langsung ke controller
        if (strpos($_SERVER['SCRIPT_FILENAME'], '/src/Controller/') !== false) {
            require_once __DIR__ . '/../../views/codepages/codes/403.php';
            exit();
        }
        
        $database = new Database();
        $this->db = $database->connect();   
    }

    public function login($username, $password) {
        try {
            $this->logActivity('Login attempt for username: ' . $username);
            
            $query = "SELECT COUNT(*) as count FROM login WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($result['count'] == 0) {
                $this->logActivity('Login failed: User not found - ' . $username);
                return ['status' => 'error', 'message' => 'Username tidak ditemukan'];
            }
            
            $query = "SELECT * FROM login WHERE username = :username AND role = 'admin'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($admin) {
                $decodedPassword = base64_decode($admin['password']);
                
                if ($password === $decodedPassword) {
                    SessionHelper::startSession();
                    SessionHelper::set('admin_logged_in', true);
                    SessionHelper::set('admin_username', $admin['username']);
                    SessionHelper::set('admin_role', $admin['role']);
                    
                    $this->logActivity('Login successful for admin: ' . $username);
                    return ['status' => 'success', 'message' => 'Login berhasil'];
                }
                
                $this->logActivity('Login failed: Invalid password for user - ' . $username);
                return ['status' => 'error', 'message' => 'Password salah'];
            }
            
            $this->logActivity('Login failed: Not an admin account - ' . $username);
            return ['status' => 'error', 'message' => 'Akun ini bukan admin'];
            
        } catch (\PDOException $e) {
            $this->logError('Database error during login: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    private function logActivity($message) {
        $timestamp = date('Y-m-d H:i:s');
        error_log("[{$timestamp}] ACTIVITY: {$message}");
    }

    private function logError($message) {
        $timestamp = date('Y-m-d H:i:s');
        error_log("[{$timestamp}] ERROR: {$message}");
    }

    public function isLoggedIn() {
        return SessionHelper::get('admin_logged_in', false) && 
               SessionHelper::get('admin_role') === 'admin';
    }

    public function logout() {
        SessionHelper::set('admin_logged_in', false);
        SessionHelper::set('admin_username', null);
        SessionHelper::set('admin_role', null);
        session_destroy();
        header('Location: /traceritesa/tracer/login');
        exit();
    }

    public function getDashboardData() {
        if (!$this->isLoggedIn()) {
            return ['status' => 'error', 'message' => 'Akses ditolak'];
        }

        try {
            $query = "SELECT * FROM users ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal mengambil data'];
        }
    }

    public function showLogin() {
        if ($this->isLoggedIn()) {
            header('Location: /traceritesa/tracer/admin/dashboard');
            exit();
        }
        require_once __DIR__ . '/../../views/dash/login.php';
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['status' => 'error', 'message' => 'Metode tidak diizinkan'];
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->login($username, $password);

        if ($result['status'] === 'success') {
            header('Location: /traceritesa/tracer/dash');
            exit();
        }

        $_SESSION['login_error'] = $result['message'];
        header('Location: /traceritesa/tracer/login');
        exit();
    }

    public function showDashboard() {
        if (!$this->isLoggedIn()) {
            header('Location: /traceritesa/tracer/login');
            exit();
        }
        
        $users = $this->getDashboardData();
        require_once __DIR__ . '/../../views/dash/index.php';
    }
} 