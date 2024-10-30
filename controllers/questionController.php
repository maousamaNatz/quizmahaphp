<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/Option.php';

class QuestionController {
    private $db;
    private $question;
    private $option;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->question = new Question($this->db);
        $this->option = new Option($this->db);
    }

    public function getAllQuestions() {
        return $this->question->getAll();
    }

    public function getOptionsByQuestionId($question_id) {
        return $this->option->getByQuestionId($question_id);
    }
}
?>
