<?php
// Cek akses langsung
if (!defined('APP_RUNNING')) {
    require_once __DIR__ . '/../views/codepages/codes/403.php';
    exit();
}

// Konfigurasi error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Definisikan path log yang benar
$logPath = __DIR__ . '/logs/error.log';
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

require_once __DIR__ . '/../vendor/autoload.php';
use App\Config\Database;
use App\Helpers\SessionHelper;
use App\Controller\UserController;
use App\Helpers\AssetHelper;

SessionHelper::startSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        error_log("Memulai proses POST request");
        
        $userController = new UserController();
        $response = $userController->createUser($_POST);
        
        error_log("Response dari createUser: " . print_r($response, true));
        
        if ($response['status'] === 'success') {
            $_SESSION['user_id'] = $response['user_id'];
            header('Location: /traceritesa/tracer/questions?user_id=' . urlencode($response['user_id']));
            exit();
        } else {
            throw new Exception($response['message']);
        }
        
    } catch (\Exception $e) {
        error_log("Error detail: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        error_log("Request data: " . print_r($_REQUEST, true));
        
        $_SESSION['error'] = $e->getMessage();
        header('Location: /traceritesa/tracer');
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link rel="icon" href="<?php echo AssetHelper::url('media/logos.png'); ?>" />
    <link href="<?php echo AssetHelper::url('css/styles.css'); ?>" rel="stylesheet" />
    <style>
      body {
        font-family: "Roboto", sans-serif;
      }

      /* Mobile first approach */
      #heroSection {
        padding: 2rem 1rem;
      }

      [animate] {
        font-size: 1.5rem; /* Ukuran font lebih kecil untuk mobile */
      }

      [animate2], [animate3] {
        font-size: 0.875rem;
        line-height: 1.5;
      }

      /* Responsive image */
      .hero-image {
        width: 100%;
        max-width: 300px;
        margin: 2rem auto;  
      }

      /* Form styles */
      #formSection {
        opacity: 0;
        transform: translateY(50px);
        padding: 1rem;
      }

      .form-container {
        width: 100%;
        max-width: 100%;
      }

      /* Responsive breakpoints */
      @media (min-width: 640px) {
        
        [animate] {
          font-size: 1.75rem;
        }
      }

      @media (min-width: 768px) {

        #heroSection {
          padding: 3rem 2rem;
        }

        [animate] {
          font-size: 2rem;
        }

        [animate2], [animate3] {
          font-size: 1rem;
        }
      }

      @media (min-width: 1024px) {

        #heroSection {
          padding: 4rem 2rem;
        }
      }

      @media (min-width: 1280px) {
        .container {
          max-width: 1280px;
        }
      }

      [x-cloak] { 
        display: none !important; 
      }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/loading.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/notification.php'; ?>
  <div class="container flex items-center justify-center mx-auto px-4 w-full h-auto md:h-screen">
    <section id="heroSection" class="flex flex-col md:flex-row justify-center items-center w-full gap-8">
      <div class="w-full md:w-1/2 space-y-6">
        <h2 class="text-2xl md:text-4xl font-bold" animate>Tracer Itesa</h2>
        <p class="text-sm md:text-base text-gray-600" animate2>
          Tracer ITESA adalah platform interaktif yang membantu siswa SMK
          menentukan langkah karir mereka setelah lulus. Melalui serangkaian
          soal, siswa dapat mengarahkan pilihan mereka, seperti bekerja,
          melanjutkan studi, atau pelatihan khusus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos sequi mollitia quas, molestias quibusdam quidem incidunt officiis dolores in neque ipsa magni animi deleniti? Necessitatibus recusandae harum reiciendis consequatur est.  
        </p>
        <button id="startButton" class="w-full md:w-auto bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg">Mulai</button>
      </div>
      <div class="w-full md:w-1/2 flex justify-center">
        <img alt="Illustration" class="hero-image rounded-lg shadow-lg" width="70%"
             src="https://storage.googleapis.com/a1aa/image/pFZoKAUJwHqfd6NoNJgk7GMWynYsYe9peezUdIN58ZpIPVKOB.jpg"/>
      </div>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/questions/form.php'; ?>
  </div>    

<?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
<script type="module" src="<?php echo AssetHelper::url('dist/assets/main-DlMiVXK5.js'); ?>"></script>

<?php if (isset($error) && !empty($error)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Alpine.store('notifications').add('<?php echo htmlspecialchars($error); ?>', 'error');
  });
</script>
<?php endif; ?>

<?php if (isset($success) && !empty($success)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Alpine.store('notifications').add('<?php echo htmlspecialchars($success); ?>', 'success');
  });
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Reset validasi
      document.querySelectorAll('input, select').forEach(field => {
        field.classList.remove('border-red-500', 'ring-red-200');
        field.classList.add('border-slate-200');
      });

      let isValid = true;
      let emptyFields = [];

      // Validasi field wajib
      const requiredFields = {
        nama: "Nama",
        nim: "NIM", 
        email: "Email",
        tgl_lahir: "Tanggal Lahir",
        thn_lulus: "Tahun Lulus",
        perguruan: "Program Studi"
      };

      Object.entries(requiredFields).forEach(([fieldName, label]) => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
          field.classList.remove('border-slate-200');
          field.classList.add('border-red-500', 'ring-red-200');
          isValid = false;
          emptyFields.push(label);
        }
      });

      if (!isValid) {
        Alpine.store('notifications').add(`Field berikut harus diisi: ${emptyFields.join(", ")}`, 'error');
        return;
      }

      // Validasi email
      const emailField = document.querySelector('[name="email"]');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(emailField.value)) {
        emailField.classList.add('border-red-500', 'ring-red-200');
        Alpine.store('notifications').add('Format email tidak valid', 'error');
        return;
      }

      // Submit form jika valid
      form.submit();
    });
  }
});

</script>
  
</body> 
</html> 
