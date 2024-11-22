<?php
namespace App\Controller;

// Tambahkan use statement untuk PDO
use PDO;
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
                // Cek akses langsung ke controller
                if (strpos($_SERVER['SCRIPT_FILENAME'], '/src/Controller/') !== false) {
                    require_once __DIR__ . '/../../views/codepages/codes/403.php';
                    exit();
                }
        $database = new Database();
        $this->db = $database->getConnection();
        $this->answer = new Answer($this->db);
    }

    /**
     * Menyimpan jawaban pengguna
     * 
     * @param array $postData Data jawaban yang akan disimpan
     * @return array Status operasi ['status' => string, 'message' => string]
     * @throws \Exception Jika terjadi kesalahan saat menyimpan
     */
    public function saveAnswer($postData) {
        try {
            // Validasi session dan user_id
            if (!isset($_SESSION)) {
                SessionHelper::startSession();
            }
            
            $userId = $_SESSION['user_id'] ?? null;
            if (!$userId) {
                error_log("Error: User ID tidak ditemukan dalam session");
                return ['status' => 'error', 'message' => 'Sesi tidak valid'];
            }
            
            // Log data yang diterima
            error_log("Received POST data: " . print_r($postData, true));
            error_log("User ID from session: " . $userId);
            
            $answers = [];
            foreach ($postData as $key => $value) {
                if (strpos($key, 'question_') === 0) {
                    $questionId = str_replace(['question_', '_1', '_2'], '', $key);
                    
                    // Log setiap jawaban yang diproses
                    error_log("Processing question ID: " . $questionId . " with value: " . print_r($value, true));
                    
                    try {
                        $query = "SELECT type FROM questions WHERE id = :questionId";
                        $stmt = $this->db->prepare($query);
                        $stmt->bindParam(':questionId', $questionId);
                        $stmt->execute();
                        $questionType = $stmt->fetch(\PDO::FETCH_ASSOC)['type'];
                        
                        if ($questionType === 'double_text_input') {
                            $answers[$questionId] = [
                                'kota' => $postData["question_{$questionId}_1"] ?? '',
                                'provinsi' => $postData["question_{$questionId}_2"] ?? ''
                            ];
                        } else {
                            $answerText = is_array($value) ? 
                                array_map(function($v) use ($questionId) { 
                                    return $this->getAnswerText($questionId, $v); 
                                }, $value) : 
                                $this->getAnswerText($questionId, $value);
                            
                            $answers[$questionId] = $answerText;
                        }
                    } catch (\Exception $e) {
                        error_log("Error processing question $questionId: " . $e->getMessage());
                        continue;
                    }
                }
            }
            
            // Log jawaban yang akan disimpan
            error_log("Final answers to be saved: " . json_encode($answers));
            
            // Simpan jawaban
            $query = "INSERT INTO user_answers (user_id, answers) VALUES (:user_id, :answers)
                     ON DUPLICATE KEY UPDATE answers = :answers";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $answersJson = json_encode($answers);
            $stmt->bindParam(':answers', $answersJson);
            
            if ($stmt->execute()) {
                $hash = $this->generateHash($userId);
                return [
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'user_id' => $userId,
                    'hash' => $hash
                ];
            }
            
            throw new \Exception('Gagal menyimpan jawaban');
            
        } catch (\Exception $e) {
            error_log("Error in saveAnswer: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return ['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan jawaban'];
        }
    }

    /**
     * Mengambil jawaban berdasarkan ID pengguna
     * 
     * @param int $user_id ID pengguna
     * @return array|null Data jawaban pengguna atau null jika tidak ditemukan
     */
    public function getAnswersByUserId($userId) {
        try {
            $query = "SELECT * FROM user_answers WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                $answers = json_decode($result['answers'], true);
                
                // Konversi ID jawaban menjadi text
                foreach ($answers as $questionId => $answer) {
                    if (is_array($answer)) {
                        // Untuk jawaban multiple choice atau paired scale
                        foreach ($answer as $key => $value) {
                            if (is_numeric($value)) {
                                $answers[$questionId][$key] = $this->getAnswerText($questionId, $value);
                            }
                        }
                    } else if (is_numeric($answer)) {
                        // Untuk jawaban single choice
                        $answers[$questionId] = $this->getAnswerText($questionId, $answer);
                    }
                }
                
                $result['answers'] = json_encode($answers);
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Error dalam getAnswersByUserId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mendapatkan text jawaban berdasarkan ID jawaban
     * 
     * @param int $questionId ID pertanyaan
     * @param int $answerId ID jawaban
     * @return string Text jawaban
     */
    private function getAnswerText($questionId, $answerId, $type = null) {
        try {
            // Tambahkan penanganan untuk double_text_input
            $query = "SELECT type FROM questions WHERE id = :questionId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':questionId', $questionId);
            $stmt->execute();
            $questionType = $stmt->fetch(PDO::FETCH_ASSOC)['type'];

            if ($questionType === 'double_text_input') {
                return $answerId; // Langsung kembalikan nilai input
            }

            if ($type === 'paired_scale') {
                // Untuk pertanyaan dengan sub-pertanyaan (seperti nomor 11)
                $query = "SELECT sq.sub_question_text, qo.option_text, sq.part
                         FROM question_options qo
                         JOIN sub_questions sq ON qo.sub_question_id = sq.id
                         WHERE qo.id = :answer_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':answer_id', $answerId);
                $stmt->execute();
                $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($result) {
                    return [
                        'sub_question' => $result['sub_question_text'],
                        'answer' => $result['option_text'],
                        'part' => $result['part']
                    ];
                }
            } else {
                // Query untuk mendapatkan teks jawaban
                $query = "SELECT option_text FROM question_options WHERE id = :answerId";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':answerId', $answerId);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $result ? $result['option_text'] : $answerId;
            }
        } catch (\PDOException $e) {
            error_log("Error in getAnswerText: " . $e->getMessage());
            return $answerId;
        }
    }

    public function viewAnswers() {
        try {
            $hash = $_GET['q'] ?? null;
            if (!$hash) {
                throw new \Exception('Parameter tidak valid');
            }
            
            // Decode hash untuk mendapatkan user_id
            $userId = $this->decodeHash($hash);
            
            if (!$userId) {
                throw new \Exception('ID tidak valid');
            }
            
            $userAnswers = $this->getAnswersByUserId($userId);
            $questionController = new QuestionController();
            $questions = $questionController->getAllQuestions();
            
            require_once __DIR__ . '/../../views/questions/show_answers.php';
            
        } catch (\Exception $e) {
            error_log("Error viewing answers: " . $e->getMessage());
            header('Location: /traceritesa/tracer/views/codepages/codes/404.php');
            exit();
        }
    }

    public function generateHash($userId) {
        $key = 'penunjang_' . date('Ymd');
        return hash('sha256', $userId . $key);
    }

    public function decodeHash($hash) {
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

    public function showResult() {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                throw new \Exception('Sesi tidak valid');
            }
            
            require_once __DIR__ . '/../../views/questions/result.php';
            
        } catch (\Exception $e) {
            error_log("Error showing result: " . $e->getMessage());
            header('Location: /traceritesa/tracer/views/codepages/codes/404.php');
            exit();
        }
    }
    public function getAllAnswers() {
        try {
            $query = "SELECT user_answers.*, users.nama, users.nim, users.perguruan, users.thn_lulus 
                     FROM user_answers 
                     JOIN users ON user_answers.user_id = users.id 
                     ORDER BY user_answers.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting all answers: " . $e->getMessage());
            return [];
        }
    }

    public function getAllAnswersWithUserDetails() {
        try {
            $query = "SELECT user_answers.*, users.nama, users.nim, users.perguruan, users.thn_lulus, users.id as user_id 
                      FROM user_answers 
                      JOIN users ON user_answers.user_id = users.id 
                      ORDER BY users.nama ASC";
                      
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Filter jawaban berdasarkan opsi nomor 1
            foreach ($results as $key => $result) {
                $answers = json_decode($result['answers'], true);
                if (isset($answers['1'])) {
                    $results[$key]['status_kerja'] = $answers['1'];
                }
            }
            
            return $results;
        } catch (\PDOException $e) {
            error_log("Error getting answers with user details: " . $e->getMessage());
            return [];
        }
    }

    public function checkUserHasAnswers($userId) {
        // Implementasi logika untuk mengecek jawaban user
        // Contoh:
        return $this->model->hasAnswers($userId);
    }
}
?>

