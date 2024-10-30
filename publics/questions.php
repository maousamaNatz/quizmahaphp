<?php
error_log("questions.php accessed");
require_once __DIR__ . '/../config/db.php';

// Mulai sesi
session_start();

// Koneksi ke database
$database = new Database();
$db = $database->connect();

// Ambil pertanyaan dari database
$query = "SELECT * FROM questions";
$stmt = $db->prepare($query);
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions</title>
</head>
<body>
    <h1>Daftar Pertanyaan</h1>
    <ul>
        <?php foreach ($questions as $question): ?>
            <li>
                <?php echo htmlspecialchars($question['question_text']); ?>
                (Tipe: <?php echo htmlspecialchars($question['type']); ?>)
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
