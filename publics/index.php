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
        
        if ($response['status'] === 'success') {
            $_SESSION['user_id'] = $response['user_id'];
            $user_id = str_replace(array("\r", "\n"), '', $response['user_id']);
            header('Location: /traceritesa/tracer/questions?user_id=' . urlencode($user_id));
            exit();
        } else {
            $error = $response['message'];
        }
    } catch (Exception $e) {
        error_log("Exception caught: " . $e->getMessage());
        header('Location: .././views/codepages/500.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/x-icon" href="/assets/img/logo.png">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tracer Itesa Muhammadiyah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
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
      [x-cloak] { 
        display: none !important; 
      }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
<div x-data="{ 
  showNotification: false,
  message: '',
  type: '',
  showNotif(msg, typ) {
    this.message = msg;
    this.type = typ;
    this.showNotification = true;
    setTimeout(() => this.showNotification = false, 3000);
  }
}" class="relative">
  
  <div x-show="showNotification" x-cloak x-transition:enter="transform ease-out duration-300 transition"
       x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
       x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
       x-transition:leave="transition ease-in duration-100"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed top-4 right-4 z-50">
    
    <div class="max-w-sm w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
         :class="{
           'bg-green-50': type === 'success',
           'bg-red-50': type === 'error'
         }">
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <template x-if="type === 'success'">
              <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </template>
            <template x-if="type === 'error'">
              <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </template>
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p x-text="message" class="text-sm font-medium"
               :class="{
                 'text-green-800': type === 'success',
                 'text-red-800': type === 'error'
               }"></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>  

<section class="flex w-full flex-col md:flex-row items-center py-20 bg-gray-50">
  <div class="md:w-1/2 p-6">
    <h2 class="text-3xl font-bold mb-4" animate>Tracer Itesa</h2>
    <p class="text-gray-600 mb-8" animate2>
      Tracer ITESA adalah platform interaktif yang membantu siswa SMK
      menentukan langkah karir mereka setelah lulus. Melalui serangkaian
      soal, siswa dapat mengarahkan pilihan mereka, seperti bekerja,
      melanjutkan studi, atau pelatihan khusus.
    </p>
    <button class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg">Mulai</button>
  </div>
  <div class="md:w-1/2 p-6 flex justify-center items-center">
    <img alt="Illustration" class="rounded-lg shadow-lg" width="70%"
         src="https://storage.googleapis.com/a1aa/image/pFZoKAUJwHqfd6NoNJgk7GMWynYsYe9peezUdIN58ZpIPVKOB.jpg"/>
  </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/questions/form.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
<script type="module" src="/traceritesa/tracer/views/assets/dist/assets/main-CItksFLW.js"></script>

<?php if (isset($error) && !empty($error)): ?>
<script>
  document.querySelector('[x-data]').__x.$data.showNotif('<?php echo htmlspecialchars($error); ?>', 'error');
</script>
<?php endif; ?>
</body> 
</html> 
