<?php
// Konfigurasi error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Definisikan path log yang benar
$logPath = __DIR__ . '/../../logs/error.log';
$logDir = dirname($logPath);

// Buat direktori logs jika belum ada
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// Buat file log jika belum ada
if (!file_exists($logPath)) {
    touch($logPath);
    chmod($logPath, 0666); // Ubah permission agar bisa ditulis
}

ini_set('error_log', $logPath);
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Helpers\SessionHelper;
use App\Controller\AnswerController;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Helpers\LogHelper;
use App\Helpers\AssetHelper;

SessionHelper::startSession();


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan direktori log ada dan bisa ditulis
$logFile = __DIR__ . '/../../logs/error.log';
if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0777, true);
}
ini_set('error_log', $logFile);


// Pastikan $db tersedia dari controller
if (!isset($db)) {
    throw new Exception('Database connection not available');
}

$tokenManager = new CsrfTokenManager(
    new UriSafeTokenGenerator(),
    new NativeSessionTokenStorage()
);

// Generate token baru
$csrfToken = $tokenManager->getToken('question_form')->getValue();

// Validasi CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['_csrf_token']) || 
            !$tokenManager->isTokenValid(new CsrfToken('question_form', $_POST['_csrf_token']))
        ) {
            throw new Exception('Token CSRF tidak valid');
        }
    } catch (Exception $e) {
        error_log("CSRF validation error: " . $e->getMessage());
        die('Token keamanan tidak valid. Silakan coba lagi.');
    }
}

// Di bagian form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new Database();
        $connection = $db->connect();
        
        if (!$connection) {
            throw new Exception("Koneksi database gagal");
        }
        
        error_log("POST Data: " . print_r($_POST, true));
        
        $answerController = new AnswerController();
        $result = $answerController->saveAnswer($_POST);
        
        error_log("Save result: " . print_r($result, true));
        
        if ($result['status'] === 'success') {
            $_SESSION['user_id'] = $result['user_id'];
            header('Location: /traceritesa/tracer/views/questions/result.php');
            exit;
        } else {
            throw new \Exception($result['message']);
        }
        
    } catch (\Exception $e) {
        error_log("Error saat menyimpan jawaban: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        error_log("POST data: " . print_r($_POST, true));
        
        header('Location: /traceritesa/tracer/views/codepages/codes/500.php');
        exit();
    }
}
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tracer itesa Muhammadiyah</title>
    <link rel="stylesheet" href="<?php echo AssetHelper::url('css/styles.css'); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link rel="icon" href="<?php echo AssetHelper::url('media/logos.png'); ?>" />
    <style>
        body {
            font-family: "Roboto", sans-serif;
        }
        @media (max-width: 768px) {
            .mobile-menu {
                display: block;
            }
            .desktop-menu {
                display: none;
            }
        }
        .checkbox-wrapper-1 *,
        .checkbox-wrapper-1 ::after,
        .checkbox-wrapper-1 ::before {
            box-sizing: border-box;
        }
        .checkbox-wrapper-1 [type=checkbox].substituted,
        .checkbox-wrapper-1 [type=radio].substituted {
            margin: 0;
            width: 0;
            height: 0;
            display: inline;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .checkbox-wrapper-1 [type=checkbox].substituted + label:before,
        .checkbox-wrapper-1 [type=radio].substituted + label:before {
            content: "";
            display: inline-block;
            vertical-align: top;
            height: 1.15em;
            width: 1.15em;
            margin-right: 0.6em;
            color: rgba(0, 0, 0, 0.275);
            border: solid 0.06em;
            box-shadow: 0 0 0.04em, 0 0.06em 0.16em -0.03em inset, 0 0 0 0.07em transparent inset;
            border-radius: 0.2em;
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xml:space="preserve" fill="white" viewBox="0 0 9 9"><rect x="0" y="4.3" transform="matrix(-0.707 -0.7072 0.7072 -0.707 0.5891 10.4702)" width="4.3" height="1.6" /><rect x="2.2" y="2.9" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 12.1877 2.9833)" width="6.1" height="1.7" /></svg>') no-repeat center, white;
            background-size: 0;
            will-change: color, border, background, background-size, box-shadow;
            transform: translate3d(0, 0, 0);
            transition: color 0.1s, border 0.1s, background 0.15s, box-shadow 0.1s;
        }
        .checkbox-wrapper-1 [type=checkbox].substituted:enabled:active + label:before,
        .checkbox-wrapper-1 [type=radio].substituted:enabled:active + label:before,
        .checkbox-wrapper-1 [type=checkbox].substituted:enabled + label:active:before,
        .checkbox-wrapper-1 [type=radio].substituted:enabled + label:active:before {
            box-shadow: 0 0 0.04em, 0 0.06em 0.16em -0.03em transparent inset, 0 0 0 0.07em rgba(0, 0, 0, 0.1) inset;
            background-color: #f0f0f0;
        }
        .checkbox-wrapper-1 [type=checkbox].substituted:checked + label:before,
        .checkbox-wrapper-1 [type=radio].substituted:checked + label:before {
            background-color: #3B99FC;
            background-size: 0.75em;
            color: rgba(0, 0, 0, 0.075);
        }
        .checkbox-wrapper-1 [type=checkbox].substituted:checked:enabled:active + label:before,
        .checkbox-wrapper-1 [type=radio].substituted:checked:enabled:active + label:before,
        .checkbox-wrapper-1 [type=checkbox].substituted:checked:enabled + label:active:before,
        .checkbox-wrapper-1 [type=radio].substituted:checked:enabled + label:active:before {
            background-color: #0a7ffb;
            color: rgba(0, 0, 0, 0.275);
        }
        .checkbox-wrapper-1 [type=checkbox].substituted:focus + label:before,
        .checkbox-wrapper-1 [type=radio].substituted:focus + label:before {
            box-shadow: 0 0 0.04em, 0 0.06em 0.16em -0.03em transparent inset, 0 0 0 0.07em rgba(0, 0, 0, 0.1) inset, 0 0 0 3.3px rgba(65, 159, 255, 0.55), 0 0 0 5px rgba(65, 159, 255, 0.3);
        }
        .checkbox-wrapper-1 [type=checkbox].substituted:focus:active + label:before,
        .checkbox-wrapper-1 [type=radio].substituted:focus:active + label:before,
        .checkbox-wrapper-1 [type=checkbox].substituted:focus + label:active:before,
        .checkbox-wrapper-1 [type=radio].substituted:focus + label:active:before {
            box-shadow: 0 0 0.04em, 0 0 0.16em -0.03em transparent inset, 0 0 0 0.07em rgba(0, 0, 0, 0.1) inset, 0 0 0 3.3px rgba(65, 159, 255, 0.55), 0 0 0 5px rgba(65, 159, 255, 0.3);
        }
        .checkbox-wrapper-1 [type=checkbox].substituted:disabled + label:before,
        .checkbox-wrapper-1 [type=radio].substituted:disabled + label:before {
            opacity: 0.5;
        }
        .checkbox-wrapper-1 [type=checkbox].substituted + label,
        .checkbox-wrapper-1 [type=radio].substituted + label {
            -webkit-user-select: none;
            user-select: none;
        }
        .question-item {
            transition: all 0.3s ease-in-out;
        }
        .question-item[style*="display: none"] {
            opacity: 0;
            transform: translateY(-20px);
        }
        .question-item[style*="display: block"] {
            opacity: 1;
            transform: translateY(0);
        }
        .question-item[style*="display: none"] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/loading.php'; ?>
<?php if (isset($error)): ?>
    <div class="absolute top-0 left-0 w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" >
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"><?php echo isset($error) ? htmlspecialchars($error) : 'Terjadi kesalahan sistem'; ?></span>
    </div>
<?php endif; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>
    <!-- section soal soal -->
    <section class="flex flex-col items-center justify-center py-20 bg-gray-100">
        <form method="post" action="" class="w-full max-w-5xl px-4 form" id="questionForm">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken); ?>">
            <div class="bg-gray-100 p-6 rounded-lg my-3 w-full">    
                <?php foreach ($questions as $question): ?>
                    <div class="space-y-4 question-item mb-6" 
                         data-question-id="<?= $question['id'] ?>" 
                         data-question-type="<?= $question['type'] ?>">
                        <h2 class="text-lg font-semibold mb-2">Question <?= htmlspecialchars($question['id']); ?></h2>
                        <p class="text-gray-700 mb-4"><?= htmlspecialchars($question['question_text']); ?></p>
                        <?php if ($question['type'] == 'paired_scale'): ?>
                            <?php
                            $querySubQuestions = "SELECT * FROM sub_questions WHERE question_id = :question_id ORDER BY part";
                            $stmtSubQuestions = $db->prepare($querySubQuestions);
                            $stmtSubQuestions->bindParam(':question_id', $question['id']);
                            $stmtSubQuestions->execute();
                            $subQuestions = $stmtSubQuestions->fetchAll(PDO::FETCH_ASSOC);
                            ?>  
                            <div class="overflow-x-auto bg-white p-4 rounded-lg">
                                <table class="min-w-full border-collapse">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-center border-b border-gray-300">A</th>
                                            <th colspan="6" class="text-center border-b border-gray-300">B</th>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-center border-b border-gray-300">Sangat Rendah - Sangat Tinggi</th>
                                            <th colspan="6" class="text-center border-b border-gray-300">Sangat Rendah - Sangat Tinggi</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center border-b border-gray-300">1</th>
                                            <th class="text-center border-b border-gray-300">2</th>
                                            <th class="text-center border-b border-gray-300">3</th>
                                            <th class="text-center border-b border-gray-300">4</th>
                                            <th class="text-center border-b border-gray-300">5</th>
                                            <th class="text-center border-b border-gray-300">Kompetensi</th>
                                            <th class="text-center border-b border-gray-300">1</th>
                                            <th class="text-center border-b border-gray-300">2</th>
                                            <th class="text-center border-b border-gray-300">3</th>
                                            <th class="text-center border-b border-gray-300">4</th>
                                            <th class="text-center border-b border-gray-300">5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $uniqueSubQuestions = array();
                                        foreach ($subQuestions as $subQuestion) {
                                            if (!isset($uniqueSubQuestions[$subQuestion['sub_question_text']])) {
                                                $uniqueSubQuestions[$subQuestion['sub_question_text']] = true;
                                    ?>
                                            <tr data-name="<?= htmlspecialchars($subQuestion['sub_question_text']); ?>">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <td class="border-b py-2 text-center border-gray-300">
                                                    <div class="checkbox-wrapper-1">
                                                        <input class="substituted" id="<?= htmlspecialchars($subQuestion['sub_question_text']); ?>-a<?= $i ?>" type="checkbox" name="question_<?= htmlspecialchars($question['id']); ?>[]" value="<?= htmlspecialchars($subQuestion['id']); ?>" />
                                                        <label for="<?= htmlspecialchars($subQuestion['sub_question_text']); ?>-a<?= $i ?>"><span></span><span></span></label>
                                                    </div>
                                                </td>
                                                <?php endfor; ?>
                                                <td class="border-b text-center border-gray-300"><?= htmlspecialchars($subQuestion['sub_question_text']); ?></td>
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <td class="border-b py-2 text-center border-gray-300">
                                                    <div class="checkbox-wrapper-1">
                                                        <input class="substituted" id="<?= htmlspecialchars($subQuestion['sub_question_text']); ?>-b<?= $i ?>" type="checkbox" name="question_<?= htmlspecialchars($question['id']); ?>[]" value="<?= htmlspecialchars($subQuestion['id']); ?>" />
                                                        <label for="<?= htmlspecialchars($subQuestion['sub_question_text']); ?>-b<?= $i ?>"><span></span><span></span></label>
                                                    </div>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php 
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php elseif ($question['type'] == 'double_text_input'): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <div class="flex items-center justify-start">
                                    <input
                                        type="text"
                                        name="question_<?= htmlspecialchars($question['id']); ?>_1"
                                        placeholder="Masukkan nama kota"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                                <div class="flex items-center justify-start">
                                    <input
                                        type="text"
                                        name="question_<?= htmlspecialchars($question['id']); ?>_2"
                                        placeholder="Masukkan nama provinsi"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Other question types -->
                            <?php
                            $queryOptions = "SELECT * FROM question_options WHERE sub_question_id = :sub_question_id";
                            $stmtOptions = $db->prepare($queryOptions);
                            $stmtOptions->bindParam(':sub_question_id', $question['id']);
                            $stmtOptions->execute();
                            $options = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);

                            // Tambahkan array untuk melacak pertanyaan yang sudah ditampilkan
                            static $displayedQuestions = [];
                            // Skip jika pertanyaan sudah ditampilkan sebelumnya
                            if (in_array($question['id'], $displayedQuestions)) {
                                continue;
                            }

                            // Tambahkan pertanyaan ke array yang sudah ditampilkan
                            $displayedQuestions[] = $question['id'];

                            if (empty($options) || $question['type'] == 'text'): ?>
                                <div class="flex items-center justify-start mt-2">
                                    <input
                                        type="text"
                                        name="other_<?= htmlspecialchars($question['id']); ?>"
                                        placeholder="Silakan isi jawaban Anda"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                                </div>
                            <?php else: ?>
                                <?php foreach ($options as $option): ?>
                                    <div class="checkbox-wrapper-1">
                                        <input id="option_<?= $option['id'] ?>" 
                                               class="substituted" 
                                               type="<?= $question['type'] === 'multiple_choice' ? 'checkbox' : 'radio' ?>" 
                                               name="question_<?= htmlspecialchars($question['id']); ?><?= $question['type'] === 'multiple_choice' ? '[]' : '' ?>" 
                                               value="<?= htmlspecialchars($option['id']); ?>" />
                                        <label for="option_<?= $option['id'] ?>">
                                            <?= htmlspecialchars($option['option_text']); ?>
                                        </label>
                                    </div>  
                                    <?php if ($option['is_text_input_required']): ?>
                                        <div class="flex items-center justify-start mt-2">
                                            <input
                                                type="text"
                                                name="other_<?= htmlspecialchars($question['id']); ?>"
                                                placeholder="Silakan sebutkan"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100"
                                                disabled
                                            />
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="mt-6 px-4 py-2 bg-blue-500 text-white rounded-md">Submit</button>
            </div>
        </form>
    </section>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
    <script>
        // Tambahkan ini sebelum Alpine.js dimuat
        document.addEventListener('alpine:init', () => {
            Alpine.store('notifications', {
                items: []
            });
        });
    </script>
    <script type="module" src="<?php echo AssetHelper::url('node_modules/alpinejs/dist/cdn.min.js'); ?>"></script>
    <script type="module" src="<?php echo AssetHelper::url('dist/assets/main-DlMiVXK5.js'); ?>"></script>
    <script src="<?php echo AssetHelper::url('js/quest.js'); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('questionForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const firstQuestionAnswer = document.querySelector('input[name="question_1"]:checked')?.value;
                    if (!firstQuestionAnswer) {
                        alert('Mohon jawab pertanyaan pertama');
                        return;
                    }

                    let isValid = true;
                    const unansweredQuestions = [];
                    
                    // Hanya validasi pertanyaan yang terlihat
                    document.querySelectorAll('.question-item[style*="display: block"]').forEach((item) => {
                        const questionId = item.getAttribute('data-question-id');
                        let hasAnswer = false;
                        
                        // Cek tipe pertanyaan
                        const questionType = item.getAttribute('data-question-type');
                        
                        if (questionType === 'double_text_input') {
                            const input1 = item.querySelector(`input[name="question_${questionId}_1"]`);
                            const input2 = item.querySelector(`input[name="question_${questionId}_2"]`);
                            
                            if (input1.value.trim() !== '' && input2.value.trim() !== '') {
                                hasAnswer = true;
                            }
                        } else {
                            // Logika validasi yang sudah ada untuk tipe lainnya
                            const inputs = item.querySelectorAll('input[type="radio"], input[type="checkbox"], input[type="text"]');
                            inputs.forEach(input => {
                                if (input.type === 'text' && input.value.trim() !== '') {
                                    hasAnswer = true;
                                } else if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) {
                                    hasAnswer = true;
                                }
                            });
                        }

                        if (!hasAnswer) {
                            isValid = false;
                            unansweredQuestions.push(`Pertanyaan ${questionId}`);
                        }
                    });

                    if (!isValid) {
                        alert(`Mohon jawab semua pertanyaan berikut: ${unansweredQuestions.join(', ')}`);
                        return;
                    }

                    form.submit();
                });
            }
        });
    </script>
</body>
</html>
