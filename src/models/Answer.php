<?php
namespace App\Models;

/**
 * Class Answer
 * 
 * @package App\Models
 * Merepresentasikan jawaban pengguna dalam sistem dengan metode untuk menyimpan dan mengambil jawaban.
 */
class Answer {
    /**
     * @var \PDO $db Objek koneksi database
     */
    private $db;

    /**
     * @var string $table Nama tabel jawaban
     */
    private $table = 'answers';

    // Answer properties
    public $id;              // Answer ID
    public $user_id;         // User ID
    public $question_id;     // Question ID  
    public $answer_text;     // Answer text content

    /**
     * Constructor untuk class Answer.
     * 
     * @param \PDO $db Koneksi database
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Menyimpan jawaban baru ke database.
     * 
     * @param array $data Data jawaban yang berisi:
     *                    - user_id: int ID pengguna
     *                    - question_id: int ID pertanyaan  
     *                    - answer_text: string Teks jawaban
     * @return array Status penyimpanan ['status' => string, 'message' => string]
     */
    public function save($data) {
        try {
            $query = "INSERT INTO user_answers (user_id, answers) 
                     VALUES (:user_id, :answers)
                     ON DUPLICATE KEY UPDATE answers = :answers";
            
            $stmt = $this->db->prepare($query);
            $answersJson = json_encode($data['answers']);
            
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':answers', $answersJson);
            
            if ($stmt->execute()) {
                return [
                    'status' => 'success',
                    'message' => 'Jawaban berhasil disimpan',
                    'user_id' => $data['user_id']
                ];
            }
            
            return ['status' => 'error', 'message' => 'Gagal menyimpan jawaban'];
                
        } catch (\PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    /**
     * Mengambil semua jawaban berdasarkan ID pengguna.
     * 
     * @param int $user_id ID pengguna yang jawabannya akan diambil
     * @return array Daftar jawaban pengguna atau array kosong jika terjadi error
     */
    public function getByUserId($user_id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
} 