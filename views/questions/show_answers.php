<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/config/Database.php';

use App\Helpers\SessionHelper;
use App\Controller\AnswerController;
use App\Controller\QuestionController;

SessionHelper::startSession();

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if (!$user_id) {
    die('ID User tidak valid');
}

$answerController = new AnswerController();
$questionController = new QuestionController();

$userAnswers = $answerController->getAnswersByUserId($user_id);
$answers = json_decode($userAnswers['answers'], true);
$questions = $questionController->getAllQuestions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawaban Tracer Study</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: "Roboto", sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>
    
    <section class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Jawaban Tracer Study</h1>
            
            <div class="space-y-6" id="questionContainer">
                <?php foreach ($questions as $question): 
                    if (isset($answers[$question['id']])):
                        $answerText = $answers[$question['id']];
                ?>
                    <div class="question-item border-b pb-4 mb-4">
                        <h3 class="font-semibold mb-2">
                            <?= htmlspecialchars($question['question_text']) ?>
                        </h3>
                        <div class="answer-content pl-4">
                            <p class="text-gray-700">
                                <?php 
                                if (is_array($answerText)) {
                                    if (isset($answerText[0]['sub_question'])) {
                                        // Untuk pertanyaan dengan sub-pertanyaan (seperti nomor 11)
                                        echo "<div class='grid grid-cols-1 gap-2'>";
                                        foreach ($answerText as $subAnswer) {
                                            echo "<div class='border-b pb-2'>";
                                            echo "<strong>" . htmlspecialchars($subAnswer['sub_question']) . "</strong><br>";
                                            echo "Bagian " . htmlspecialchars($subAnswer['part']) . ": ";
                                            echo htmlspecialchars($subAnswer['answer']);
                                            echo "</div>";
                                        }
                                        echo "</div>";
                                    } else {
                                        // Untuk jawaban multiple choice
                                        echo implode(", ", array_map('htmlspecialchars', $answerText));
                                    }
                                } else {
                                    echo htmlspecialchars($answerText);
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php 
                    endif; 
                endforeach; 
                
                if (empty($answers)): 
                ?>
                    <div class="text-center text-gray-500 py-8">
                        <p class="text-xl">Belum ada jawaban yang tersedia</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
</body>
</html> 