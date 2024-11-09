<?php
namespace App\Controller;

use App\Models\Question;
use App\Models\Option;
use App\Config\Database;

/**
 * Class QuestionController
 * 
 * @package App\Controller
 * Mengelola operasi yang berkaitan dengan pertanyaan dan pilihan jawabannya, 
 * termasuk menampilkan dan mengambil pertanyaan.
 */
class QuestionController {
    /**
     * @var \PDO $db Objek koneksi database
     */
    private $db;

    /**
     * @var Question $question Instance model Question
     */
    private $question;

    /**
     * @var Option $option Instance model Option
     */
    private $option;

    /**
     * Konstruktor untuk QuestionController.
     * Menginisialisasi koneksi database dan instance model.
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->question = new Question($this->db);
        $this->option = new Option($this->db);
    }

    /**
     * Menampilkan pertanyaan beserta pilihan jawaban terkait.
     * 
     * @return array|void Array dengan status error jika gagal atau memuat tampilan pertanyaan.
     */
    public function show() {
        try {
            $questions = $this->question->getAll();
            $processedQuestions = [];
            
            foreach ($questions as $key => $question) {
                if (in_array($question['id'], $processedQuestions)) {
                    unset($questions[$key]);
                    continue;
                }
                
                $options = $this->option->getByQuestionId($question['id']);
                $questions[$key]['options'] = $options;
                $processedQuestions[] = $question['id'];
            }
            
            $questions = array_values($questions);
            
            $db = $this->db;
            
            require __DIR__ . '/../../views/questions/quest.php';
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memuat pertanyaan'];
        }
    }

    /**
     * Mengambil pilihan jawaban untuk pertanyaan tertentu berdasarkan ID-nya.
     * 
     * @param int $questionId ID dari pertanyaan
     * @return array Daftar pilihan jawaban untuk pertanyaan yang ditentukan atau array kosong jika terjadi error
     */
    public function getByQuestionId($questionId) {
        $query = "SELECT DISTINCT o.* 
                  FROM options o 
                  WHERE o.sub_question_id IN (
                      SELECT id 
                      FROM sub_questions 
                      WHERE question_id = :question_id
                  )
                  ORDER BY o.id ASC";
                  
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':question_id', $questionId, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error dalam getByQuestionId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mengambil semua pertanyaan dari database.
     * 
     * @return array Daftar semua pertanyaan atau array kosong jika terjadi error
     */
    public function getAllQuestions() {
        try {
            $query = "SELECT * FROM questions ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
?>
