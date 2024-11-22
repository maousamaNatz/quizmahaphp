<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/config/Database.php';

use App\Helpers\SessionHelper;
use App\Controller\AnswerController;
use App\Controller\QuestionController;
use App\Controller\UserController;
use App\Helpers\AssetHelper;

SessionHelper::startSession();

$hash = isset($_GET['q']) ? $_GET['q'] : null;

if (!$hash) {
    die('Parameter tidak valid');
}

$answerController = new AnswerController();
$userController = new UserController();
$user_id = $answerController->decodeHash($hash);

if (!$user_id) {
    die('ID tidak valid');
}

$userDetails = $userController->getUserById($user_id);
if (!$userDetails) {
    die('Data user tidak ditemukan');
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
    <link rel="stylesheet" href="<?php echo AssetHelper::url(
        'css/styles.css'
    ); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="icon" href="<?php echo AssetHelper::url(
        'media/logos.png'
    ); ?>" />
    <style>
        body {
            font-family: "Roboto", sans-serif;
        }
        .question-item {
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }   
        .question-item.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .answer-content {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 1rem;
            margin-top: 0.5rem;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php include $_SERVER['DOCUMENT_ROOT'] .
        '/traceritesa/tracer/views/components/navbar.php'; ?>

    <section class="flex flex-col items-center justify-center py-20 bg-gray-100">
        <a href="/traceritesa/tracer/" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4">Kembali ke Halaman Tracer</a>
        <div class="w-full max-w-5xl px-4">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-8 p-6 bg-blue-50 rounded-lg border border-blue-100">
                    <h2 class="text-2xl font-bold text-blue-800 mb-4">Informasi Alumni</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Nama:</p>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars(
                                $userDetails['nama']
                            ) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">NIM:</p>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars(
                                $userDetails['nim']
                            ) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Program Studi:</p>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars(
                                $userDetails['perguruan']
                            ) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Tahun Lulus:</p>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars(
                                $userDetails['thn_lulus']
                            ) ?></p>
                        </div>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Jawaban Tracer Study</h1>
                
                <div class="space-y-8" id="questionContainer">
                    <?php
                    foreach ($questions as $question):
                        if (isset($answers[$question['id']])):
                            $answerText = $answers[$question['id']]; ?>
                        <div class="question-item border-b border-gray-200 pb-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                <?= htmlspecialchars(
                                    $question['question_text']
                                ) ?>
                            </h3>
                            <div class="answer-content">
                                <?php if (is_array($answerText)) {
                                    if (isset($answerText[0]['sub_question'])) {
                                        echo "<div class='grid gap-4'>";
                                        foreach ($answerText as $subAnswer) {
                                            echo "<div class='p-3 bg-white rounded-lg shadow-sm'>";
                                            echo "<p class='font-medium text-gray-700'>" .
                                                htmlspecialchars(
                                                    $subAnswer['sub_question']
                                                ) .
                                                '</p>';
                                            echo "<p class='text-gray-600 mt-2'>Bagian " .
                                                htmlspecialchars(
                                                    $subAnswer['part']
                                                ) .
                                                ': ';
                                            echo "<span class='text-blue-600'>" .
                                                htmlspecialchars(
                                                    $subAnswer['answer']
                                                ) .
                                                '</span></p>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    } else {
                                        echo "<ul class='list-disc list-inside space-y-2'>";
                                        foreach ($answerText as $answer) {
                                            echo "<li class='text-gray-700'>" .
                                                htmlspecialchars($answer) .
                                                '</li>';
                                        }
                                        echo '</ul>';
                                    }
                                } else {
                                    echo "<p class='text-gray-700'>" .
                                        htmlspecialchars($answerText) .
                                        '</p>';
                                } ?>
                            </div>
                        </div>
                    <?php
                        endif;
                    endforeach;

                    if (empty($answers)): ?>
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                            <p class="text-xl text-gray-500">Belum ada jawaban yang tersedia</p>
                        </div>
                    <?php endif;
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] .
        '/traceritesa/tracer/views/components/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = document.querySelectorAll('.question-item');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            questions.forEach(question => {
                observer.observe(question);
            });
        });
    </script>
</body>
</html> 