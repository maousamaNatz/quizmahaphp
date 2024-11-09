<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/config/Database.php';

use App\Helpers\SessionHelper;
use App\Controller\AnswerController;
use App\Controller\QuestionController;

SessionHelper::startSession();

// Dapatkan user_id dari parameter URL
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if (!$user_id) {
    die('ID User tidak valid');
}

// Inisialisasi controller
$answerController = new AnswerController();
$questionController = new QuestionController();

// Ambil jawaban user
$userAnswers = $answerController->getAnswersByUserId($user_id);
$answers = json_decode($userAnswers['answers'], true);

// Ambil semua pertanyaan
$questions = $questionController->getAllQuestions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawaban Tracer Study</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Jawaban Tracer Study</h1>
            
            <?php if ($answers): ?>
                <div class="space-y-6">
                    <?php foreach ($questions as $question): ?>
                        <div class="border-b pb-4">
                            <h3 class="font-semibold text-lg mb-2">
                                <?= htmlspecialchars($question['question_text']) ?>
                            </h3>
                            <p class="text-gray-700">
                                <?php
                                $answer = $answers[$question['id']] ?? 'Tidak dijawab';
                                if (is_array($answer)) {
                                    echo implode(', ', array_map('htmlspecialchars', $answer));
                                } else {
                                    echo htmlspecialchars($answer);
                                }
                                ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Tidak ada jawaban yang tersedia.</p>
            <?php endif; ?>
            
            <div class="mt-6">
                <a href="/traceritesa/tracer/views/questions/dash.php" 
                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
</body>
</html> 