<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/config/Database.php';

use App\Helpers\SessionHelper;
use App\Controller\AnswerController;

SessionHelper::startSession();

// Dapatkan hash dari parameter URL
$hash = isset($_GET['q']) ? $_GET['q'] : null;

if (!$hash) {
    die('Parameter tidak valid');
}

$answerController = new AnswerController();
$user_id = $answerController->decodeHash($hash);

if (!$user_id) {
    die('Hash tidak valid');
}

$_SESSION['user_id'] = $user_id;
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Terima Kasih - Tracer Study</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      .font-poppins {
        font-family: "Poppins", sans-serif;
      }
    </style>
  </head> 
  <body class="bg-gray-100">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>
    
    <div class="flex items-center justify-center w-full min-h-screen py-12">
      <div class="container flex flex-col items-center justify-center px-4">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-[500px] flex items-center justify-center flex-col">
          <img 
            src="https://itesa.ac.id/wp-content/uploads/2023/11/Institut-Teknologi-Statistika-dan-Bisnis-Muhammadiyah-Semarang-1-1.png" 
            class="w-[210px] mb-6" 
            alt="Logo ITESA"
          >
          <h1 class="text-4xl font-poppins font-bold text-gray-800 mb-6 text-center">
            Terima Kasih
          </h1>
          <p class="text-gray-600 text-center mb-8">
            Jawaban Anda telah berhasil disimpan. Anda dapat melihat ringkasan jawaban Anda dengan mengklik tombol di bawah ini.
          </p>
          <?php
          $hash = $answerController->generateHash($user_id);
          ?>
          <a 
            href="/traceritesa/tracer/lihatapcb?q=<?php echo urlencode($hash); ?>" 
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-6 py-3 rounded-lg transition duration-200"
          >
            Lihat Jawaban
          </a>
          <a 
            href="/traceritesa/tracer/" 
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-6 py-3 rounded-lg transition duration-200"
          >
            Kembali ke Beranda
          </a>
        </div>
      </div>
    </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
  </body>
</html>