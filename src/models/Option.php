<?php
namespace App\Models;

/**
 * Class Option
 * 
 * @package App\Models
 * Mengelola data pilihan jawaban untuk sub-pertanyaan
 */
class Option {
    /**
     * @var \PDO Koneksi database
     */
    private $conn;

    /**
     * @var string Nama tabel di database
     */
    private $table = 'question_options';

    /**
     * @var int ID pilihan jawaban
     */
    public $id;

    /**
     * @var int ID sub pertanyaan terkait
     */
    public $sub_question_id;

    /**
     * @var string Label pilihan jawaban
     */
    public $option_label;

    /**
     * @var string Teks pilihan jawaban
     */
    public $option_text;

    /**
     * @var string Nilai pilihan jawaban
     */
    public $option_value;

    /**
     * @var bool Menandakan apakah input teks diperlukan
     */
    public $is_text_input_required;

    /**
     * Konstruktor untuk class Option
     * 
     * @param \PDO $db Koneksi database
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Mengambil semua pilihan jawaban berdasarkan ID pertanyaan
     * 
     * @param int $question_id ID pertanyaan
     * @return array Daftar pilihan jawaban untuk pertanyaan yang ditentukan
     */
    public function getByQuestionId($question_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE sub_question_id IN (
                      SELECT id FROM sub_questions WHERE question_id = :question_id
                  )";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 