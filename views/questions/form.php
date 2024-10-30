<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/UserController.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    $response = $userController->createUser($_POST);
    
    if($response['status'] === 'success') {
        $_SESSION['user_id'] = $response['user_id'];
        header('Location: ../../publics/questions.php');
        exit();
    } else {
        $error = $response['message'];
    }
}
?>

<section class="w-full h-screen" style="font-family: 'Roboto', sans-serif">
  <div
    class="container mx-auto w-full flex items-center justify-center h-screen px-4 sm:px-6 lg:px-6"
  >
    <div
      class="w-full h-fit bg-slate-100 dark:bg-slate-700 p-5 rounded-lg max-w-lg md:max-w-3xl lg:max-w-[70%]"
    >
      <h1
        class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-4"
      >
        Form Tracer Study
      </h1>
      <?php if (!empty($error)): ?>
          <div class="text-red-500 mb-4"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form
        id="form-question-one"
        action=""
        method="post"
        class="w-full h-full grid grid-cols-1 md:grid-cols-2 gap-4"
      >
        <div class="w-full h-full relative col-span-1 md:col-span-2">
          <label
            for="nama"
            class="input-label absolute left-[10px] top-[35px] text-sm font-medium text-gray-400 dark:text-gray-300 mb-1 transition-all duration-200 ease-in-out"
            >Nama Lengkap</label
          >
          <input
            type="text"
            name="nama"
            id="nama"
            class="input-field w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-600 dark:text-white mt-6 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
            placeholder=""
          />
        </div>
        <div class="w-full h-full relative col-span-1 md:col-span-2">
          <label
            for="nim"
            class="input-label absolute left-[10px] top-[35px] text-sm font-medium text-gray-400 dark:text-gray-300 mb-1 transition-all duration-200 ease-in-out"
            >NIM</label
          >
          <input
            type="number"
            name="nim"
            id="nim"
            class="input-field w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-600 dark:text-white mt-6 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
            placeholder=""
          />
        </div>
        <div class="w-full h-full relative col-span-1 md:col-span-2">
          <label
            for="email"
            class="input-label absolute left-[10px] top-[35px] text-sm font-medium text-gray-400 dark:text-gray-300 mb-1 transition-all duration-200 ease-in-out"
            >Email</label
          >
          <input
            type="email"
            name="email"
            id="email"
            class="input-field w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-600 dark:text-white mt-6 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
            placeholder=""
          />
        </div>
        <div class="w-full h-full relative pt-2">
          <label
            for="tgl_lahir"
            class="block text-sm font-medium text-gray-700 mb-2 transition-all duration-200 ease-in-out"
            >Tanggal Lahir</label
          >
          <input
            type="date"
            name="tgl_lahir"
            id="tgl_lahir"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
            placeholder=""
          />
        </div>
        <div class="w-full h-full relative pt-2">
          <label
            for="thn_lulus"
            class="block text-sm font-medium text-gray-700 mb-2 transition-all duration-200 ease-in-out"
            >Tahun Lulus</label
          >
          <input
            type="date"
            name="thn_lulus"
            id="thn_lulus"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
            placeholder=""
          />
        </div>
        <div class="w-full h-full relative col-span-1 md:col-span-2">
          <label
            for="customSelect"
            class="input-label absolute left-[10px] top-[35px] text-sm font-medium text-gray-400 dark:text-gray-300 mb-1 transition-all duration-200 ease-in-out"
            >pilihan perguruan tinggi</label
          >
          <input
            type="text"
            name="perguruan"
            id="customSelect"
            class="input-field w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-600 dark:text-white mt-6 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder=" "
            readonly
          />
          <div
            class="dropdown hidden absolute w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-600 rounded-md mt-1 z-10 shadow-lg"
          >
            <div
              class="dropdown-item px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-500 dark:text-white"
            >
              D3 statistika
            </div>
            <div
              class="dropdown-item px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-500 dark:text-white"
            >
              S1 sains akturia
            </div>
            <div
              class="dropdown-item px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-500 dark:text-white"
            >
              S1 management retaill
            </div>
            <div
              class="dropdown-item px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-500 dark:text-white"
            >
              S1 Rekayasa Perangkat Lunak/  RPL
            </div>
          </div>
        </div>
        <div class="w-full h-full relative">`
          <label
            for="nik"
            class="input-label absolute left-[10px] top-[35px] text-sm font-medium text-gray-400 dark:text-gray-300 mb-1 transition-all duration-200 ease-in-out"
            >NIK</label
          >
          <input
            type="number"
            name="nik"
            id="nik"
            class="input-field w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-600 dark:text-white mt-6 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
            placeholder=""
          />
        </div>
        <div class="w-full h-full relative">
          <label
            for="npwp "
            class="input-label absolute left-[10px] top-[35px] text-sm font-medium text-gray-400 dark:text-gray-300 mb-1 transition-all duration-200 ease-in-out"
            >NPWP</label
          >
          <input
            type="number"
            name="npwp"
            id="npwp"
            class="input-field w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-600 dark:text-white mt-6 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
            placeholder=""
          />
        </div>
        <div class="w-full h-full col-span-1 md:col-span-2 mt-6">
          <button
            type="submit"
            class="w-full bg-red-500 text-white px-3 py-2 rounded-md"
          >
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
