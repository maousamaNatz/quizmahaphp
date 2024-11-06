<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Answer.php';

class AnswerController {
    private $db;
    private $answer;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->answer = new Answer($this->db);
    }

    public function saveAnswer($data) {
        return $this->answer->save($data);
    }

    public function getAnswersByUserId($user_id) {
        // Validasi apakah user_id ada di database
        $query = "SELECT COUNT(*) FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            // Jika user_id ditemukan, ambil jawaban
            return $this->answer->getByUserId($user_id);
        } else {
            // Jika user_id tidak ditemukan, kembalikan pesan error
            return ['status' => 'error', 'message' => 'User ID tidak ditemukan'];
        }
    }
}
?>
