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
use App\Controller\AnswerController;
use App\Helpers\AssetHelper;


SessionHelper::startSession();

if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../src/Controller/AnswerController.php';
    require_once __DIR__ . '/../src/config/Database.php';
    
    $db = new Database();
    $answerController = new AnswerController($db);
    
    $hasAnswers = $answerController->checkUserHasAnswers($_SESSION['user_id']);
    
    if ($hasAnswers) {
        $hash = $answerController->generateHash($_SESSION['user_id']);
        header('Location: /traceritesa/tracer/lihatapcb?q=' . urlencode($hash));
        exit;
    }
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

$csrfToken = generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        error_log("Memulai proses POST request");
        $errors = [];
        
        // Validasi field kosong
        $requiredFields = [
            'nama' => 'Nama',
            'nim' => 'NIM',
            'email' => 'Email',
            'tgl_lahir' => 'Tanggal Lahir',
            'thn_lulus' => 'Tahun Lulus',
            'perguruan' => 'Program Studi',
            'nik' => 'NIK',
            'npwp' => 'NPWP'
        ];
        
        foreach ($requiredFields as $field => $label) {
            if (empty($_POST[$field])) {
                $errors[] = "$label harus diisi";
            }
        }
        
        // Lanjutkan dengan validasi lainnya
        if (!empty($errors)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => implode(", ", $errors)
                ]);
                exit;
            }
            throw new Exception(implode(", ", $errors));
        }
        
        $userController = new UserController();
        $response = $userController->createUser($_POST);
        
        if ($response['status'] === 'success') {
            $_SESSION['user_id'] = $response['user_id'];
            $hash = $userController->generateHash($response['user_id']);
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'redirect' => '/traceritesa/tracer/penunjangmasadepan?q=' . urlencode($hash)
                ]);
                exit;
            }
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => $response['message']
                ]);
                exit;
            }
            throw new Exception($response['message']);
        }
        
    } catch (\Exception $e) {
        error_log("Error: " . $e->getMessage());
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
        
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'message' => $e->getMessage()
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/x-icon" href="<?php echo AssetHelper::url('media/logos.png'); ?>">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tracer Itesa Muhammadiyah</title>
    <meta name="description" content="Tracer Itesa Muhammadiyah">
    <meta name="author" content="maousamanatz">
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
    <script type="module" src="<?php echo AssetHelper::url('dist/assets/main-CSgIVEFG.js'); ?>"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/questions/form.php'; ?>
  </div>    

<?php include $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>

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

<?php if(isset($_SESSION['flash_message'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tampilkan notifikasi
    Alpine.store('notifications').add(
        '<?php echo htmlspecialchars($_SESSION['flash_message']['message']); ?>', 
        '<?php echo $_SESSION['flash_message']['type']; ?>'
    );
    
    // Isi kembali form jika ada data
    <?php if(isset($_SESSION['form_data'])): ?>
    const formData = <?php echo json_encode($_SESSION['form_data']); ?>;
    Object.keys(formData).forEach(key => {
        const field = document.querySelector(`[name="${key}"]`);
        if (field) field.value = formData[key];
    });
    <?php endif; ?>
});
</script>
<?php 
    unset($_SESSION['flash_message']);
    unset($_SESSION['form_data']);
endif; ?>
</body> 
</html> 
