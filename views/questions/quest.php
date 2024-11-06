<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Pastikan autoload Composer di-include

use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;

// Start session
session_start();

// CSRF Token Manager
$csrfTokenManager = new CsrfTokenManager(
    new UriSafeTokenGenerator(),
    new NativeSessionTokenStorage()
);

// Generate CSRF token
$csrfToken = $csrfTokenManager->getToken('form_intention')->getValue();

// Database connection
$database = new Database();
$db = $database->connect();

// Function to check if sub_question_id exists
function isValidSubQuestionId($db, $sub_question_id) {
    $checkQuery = "SELECT COUNT(*) FROM sub_questions WHERE id = :sub_question_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':sub_question_id', $sub_question_id, PDO::PARAM_INT);
    $checkStmt->execute();
    return $checkStmt->fetchColumn() > 0;
}

// Process form data if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!$csrfTokenManager->isTokenValid(new \Symfony\Component\Security\Csrf\CsrfToken('form_intention', $_POST['_csrf_token']))) {
        die('Invalid CSRF token');
    }

    if (!isset($_SESSION['user_id'])) {
        error_log("User ID is not set in session.");
    } else {
        $user_id = $_SESSION['user_id'];
        $answers = [];

        // Sanitize input data
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $answers[$key] = is_array($value) ? array_map('htmlspecialchars', array_map('trim', $value)) : htmlspecialchars(trim($value));
            }
        }

        // Debugging: Print all POST data
        error_log(print_r($answers, true));

        // Prepare insert query
        $query = "INSERT INTO user_answers (user_id, sub_question_id, answer_option_id, answer_text) VALUES (:user_id, :sub_question_id, :answer_option_id, :answer_text)";
        $stmt = $db->prepare($query);

        foreach ($answers as $sub_question_id => $answer) {
            if (strpos($sub_question_id, 'question_') === 0) {
                $sub_question_id = str_replace('question_', '', $sub_question_id);
            }

            if (!isValidSubQuestionId($db, $sub_question_id)) {
                error_log("Invalid sub_question_id: " . $sub_question_id);
                continue;
            }

            if (is_array($answer)) {
                foreach ($answer as $option_id) {
                    if (!isValidOptionId($db, $option_id)) {
                        error_log("Invalid answer_option_id: " . $option_id);
                        continue;
                    }

                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->bindParam(':sub_question_id', $sub_question_id, PDO::PARAM_INT);
                    $stmt->bindParam(':answer_option_id', $option_id, PDO::PARAM_INT);
                    $answer_text = $_POST['other_' . htmlspecialchars($sub_question_id)] ?? null;
                    $stmt->bindParam(':answer_text', $answer_text, PDO::PARAM_STR);
                    $stmt->execute();
                }
            } else {
                if (!isValidOptionId($db, $answer)) {
                    error_log("Invalid answer_option_id: " . $answer);
                    continue;
                }

                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':sub_question_id', $sub_question_id, PDO::PARAM_INT);
                $stmt->bindParam(':answer_option_id', $answer, PDO::PARAM_INT);
                $answer_text = $_POST['other_' . htmlspecialchars($sub_question_id)] ?? null;
                $stmt->bindParam(':answer_text', $answer_text, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }
}

// Function to check if option_id exists
function isValidOptionId($db, $option_id) {
    $checkQuery = "SELECT COUNT(*) FROM question_options WHERE id = :option_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':option_id', $option_id, PDO::PARAM_INT);
    $checkStmt->execute();
    return $checkStmt->fetchColumn() > 0;
}

// Fetch questions and options from database
$query = "SELECT * FROM questions";
$stmt = $db->prepare($query);
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tracer itesa Muhammadiyah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link rel="icon" href="" />
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
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
<?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>
    <!-- section soal soal -->
    <section class="flex flex-col items-center justify-center py-20 bg-gray-100">
        <form method="post" action="" class="w-full max-w-5xl px-4">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken); ?>">
            <div class="bg-gray-100 p-6 rounded-lg my-3 w-full">
                <?php foreach ($questions as $question): ?>
                    <h2 class="text-lg font-semibold mb-2">Question <?= htmlspecialchars($question['id']); ?></h2>
                    <p class="text-gray-700 mb-4"><?= htmlspecialchars($question['question_text']); ?></p>
                    <div class="space-y-4">
                        <?php if ($question['type'] == 'paired_scale'): ?>
                            <!-- Paired scale logic -->
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
                        <?php else: ?>
                            <!-- Other question types -->
                            <?php
                            $queryOptions = "SELECT * FROM question_options WHERE sub_question_id = :sub_question_id";
                            $stmtOptions = $db->prepare($queryOptions);
                            $stmtOptions->bindParam(':sub_question_id', $question['id']);
                            $stmtOptions->execute();
                            $options = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);

                            if (empty($options) || $question['type'] == 'text'): ?>
                                <div class="flex items-center justify-start mt-2">
                                    <input
                                        type="text"
                                        name="other_<?= htmlspecialchars($question['id']); ?>"
                                        placeholder="Silakan isi jawaban Anda"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                            <?php else: ?>
                                <?php foreach ($options as $option): ?>
                                    <div class="checkbox-wrapper-1">
                                        <input id="option_<?= $option['id'] ?>" class="substituted" type="<?= $question['type'] == 'scale' ? 'radio' : 'checkbox' ?>" name="question_<?= htmlspecialchars($question['id']); ?><?= $question['type'] == 'multiple_choice' ? '[]' : '' ?>" value="<?= htmlspecialchars($option['id']); ?>" aria-hidden="true" />
                                        <label for="option_<?= $option['id'] ?>"><?= htmlspecialchars($option['option_text']); ?></label>
                                    </div>
                                    <?php if ($option['is_text_input_required']): ?>
                                        <div class="flex items-center justify-start mt-2">
                                            <input
                                                type="text"
                                                name="other_<?= htmlspecialchars($question['id']); ?>"
                                                placeholder="Silakan sebutkan"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
        document.querySelector(".mobile-menu").addEventListener("click", function () {
            const nav = document.querySelector("nav");
            nav.classList.toggle("hidden");
        });

        document.querySelectorAll('input[type="radio"]').forEach((radio) => {
            radio.addEventListener("change", function () {
                document.querySelectorAll("label").forEach((label) => {
                    label.classList.remove("text-blue-600");
                });

                document.querySelectorAll("label span").forEach((span) => {
                    span.classList.remove("font-medium", "text-blue-600", "font-semibold");
                });

                const span = this.nextElementSibling;
                span.classList.add("font-medium");
                const letterSpan = span.querySelector("span");
                if (letterSpan) {
                    letterSpan.classList.add("text-blue-600", "font-semibold", "mr-2");
                }
            });
        });
    </script>
</body>
</html>