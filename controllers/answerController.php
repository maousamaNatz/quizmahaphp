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
        return $this->answer->getByUserId($user_id);
    }
}
?>
