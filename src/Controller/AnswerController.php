<?php
namespace App\Controller;

use App\Models\Answer;
use App\Config\Database;
use App\Helpers\SessionHelper;

/**
 * Class AnswerController
 * 
 * @package App\Controller
 * Controller untuk menangani operasi terkait jawaban pengguna
 */
class AnswerController {
    /** @var \PDO $db Koneksi database */
    private $db;
    
    /** @var Answer $answer Instance model Answer */
    private $answer;

    /**
     * Constructor AnswerController
     * Menginisialisasi koneksi database dan model Answer
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->answer = new Answer($this->db);
    }

    /**
     * Menyimpan jawaban pengguna
     * 
     * @param array $data Data jawaban yang akan disimpan
     * @return array Status operasi ['status' => string, 'message' => string]
     * @throws \Exception Jika terjadi kesalahan saat menyimpan
     */
    public function saveAnswer($data) {
        try {
            if (!isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['user_id'])) {
                error_log('User ID tidak ditemukan dalam session');
                throw new \Exception('Silakan login kembali');
            }
            
            $userId = $_SESSION['user_id'];
            error_log('Processing answer for user_id: ' . $userId);
            
            $answers = [];
            foreach ($data as $key => $value) {
                if (strpos($key, 'question_') === 0) {
                    $questionId = substr($key, 9);
                    $answers[$questionId] = $value;
                }
            }
            
            $query = "INSERT INTO user_answers (user_id, answers) VALUES (:user_id, :answers)";
            $stmt = $this->db->prepare($query);
            $jsonAnswers = json_encode($answers);
            
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':answers', $jsonAnswers);
            
            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'Jawaban berhasil disimpan'];
            }
            
            throw new \Exception('Gagal menyimpan jawaban');
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Mengambil jawaban berdasarkan ID pengguna
     * 
     * @param int $user_id ID pengguna
     * @return array|null Data jawaban pengguna atau null jika tidak ditemukan
     */
    public function getAnswersByUserId($user_id) {
        try {
            $query = "SELECT * FROM user_answers WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
?>
