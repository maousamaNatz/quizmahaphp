<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userData) {
        $sql = "INSERT INTO users (nama, nim, email, tgl_lahir, thn_lulus, perguruan, nik, npwp, created_at) 
                VALUES (:nama, :nim, :email, :tgl_lahir, :thn_lulus, :perguruan, :nik, :npwp, NOW())";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($userData);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception('Database error');
        }
    }
} 