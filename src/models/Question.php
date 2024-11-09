<?php
namespace App\Models;

/**
 * Class Question
 * 
 * @package App\Models
 * Merepresentasikan pertanyaan dalam sistem dengan metode untuk mengambil data pertanyaan
 */
class Question {
    /** @var \PDO $conn Koneksi database */
    private $conn;
    
    /** @var string $table Nama tabel pertanyaan */
    private $table = 'questions';

    // Question properties
    public $id;              // Question ID
    public $question_text;   // Question text content
    public $type;           // Question type

    /**
     * Constructor untuk class Question
     * 
     * @param \PDO $db Koneksi database
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Mengambil semua pertanyaan
     * 
     * @return array Daftar semua pertanyaan
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil pertanyaan berdasarkan ID
     * 
     * @param int $id ID pertanyaan
     * @return array Data pertanyaan
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil sub-pertanyaan berdasarkan ID pertanyaan utama
     * 
     * @param int $question_id ID pertanyaan utama
     * @return array Daftar sub-pertanyaan
     */
    public function getSubQuestions($question_id) {
        $query = "SELECT * FROM sub_questions WHERE question_id = :question_id ORDER BY part";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 