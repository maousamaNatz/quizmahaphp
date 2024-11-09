<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
use App\Config\Database;
use App\Helpers\SessionHelper;
use App\Controller\UserController;

SessionHelper::startSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userController = new UserController();
        $response = $userController->createUser($_POST);
        
        if($response['status'] === 'success') {
            $_SESSION['user_id'] = $response['user_id'];
            error_log("User ID set in session: " . $_SESSION['user_id']);
            $user_id = str_replace(array("\r", "\n"), '', $response['user_id']);
            header('Location: /traceritesa/tracer/questions?user_id=' . urlencode($user_id));
            exit();
        } else {
            $error = $response['message'];
            error_log("User creation failed: " . $error);
        }
    } catch (Exception $e) {
        error_log("Exception caught: " . $e->getMessage());
        header('Location: .././views/codepages/500.php');
        exit();
    }
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    
    <link rel="icon" type="image/x-icon" href="/assets/img/logo.png">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tracer itesa Muhammadiyah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap"
      rel="stylesheet"
    />
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
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.0/gsap.min.js"></script>
    <script src="https://unpkg.com/split-type"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
  </head>
  <body class="bg-gray-50 text-gray-800">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>  
    <!-- page 1 -->
    <section
      class="flex w-full flex-col md:flex-row items-center py-20 bg-gray-50"
    >
      <div class="md:w-1/2 p-6">
        <h2 class="text-3xl font-bold mb-4" animate>Tracer Itesa</h2>
        <p class="text-gray-600 mb-8" animate2>
          Tracer ITESA adalah platform interaktif yang membantu siswa SMK
          menentukan langkah karir mereka setelah lulus. Melalui serangkaian
          soal, siswa dapat mengarahkan pilihan mereka, seperti bekerja,
          melanjutkan studi, atau pelatihan khusus. Jawaban yang diberikan akan
          memfilter pertanyaan selanjutnya, menghasilkan rekomendasi karir yang
          sesuai dengan minat dan tujuan mereka. Tracer ITESA memudahkan siswa
          memahami peluang karir dan pendidikan yang tersedia, membantu mereka
          merencanakan masa depan dengan lebih terarah.
        </p>
        <button
          class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg"
        >
          Mulai
        </button>
      </div>
      <div class="md:w-1/2 p-6 flex justify-center items-center">
        <img
          alt="Illustration of people discussing"
          class="rounded-lg shadow-lg"
          width="70%"
          src="https://storage.googleapis.com/a1aa/image/pFZoKAUJwHqfd6NoNJgk7GMWynYsYe9peezUdIN58ZpIPVKOB.jpg"
        />
      </div>
    </section>
    <!-- page 2 -->

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/questions/form.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
    <script src="./views/assets/js/sc.js"></script>
  </body> 
</html> 
