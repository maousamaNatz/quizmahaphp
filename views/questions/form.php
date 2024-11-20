<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/config/Database.php';
use App\Helpers\SessionHelper;
use App\Controller\UserController;

SessionHelper::startSession();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    $response = $userController->createUser($_POST);

    if ($response['status'] === 'success') {
        $_SESSION['user_id'] = $response['user_id'];
        header('Location: ../../publics/questions.php');
        exit();
    } else {
        $error = $response['message'];
    }
}
?>
    

<section id="formSection" class="w-full flex-col justify-center items-center">
  <div class="flex items-center justify-center">
    <?php if (!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
      <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php endif; ?>
    <div class="w-full md:max-w-4xl md:mx-auto px-2 md:px-4">
      <form id="questionForm" method="POST" class="bg-white shadow-md rounded px-4 md:px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="nama"
            >
              Nama
            </label>
            <input  
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              id="nama"
              name="nama"
              required
              type="text"
              placeholder="Masukkan nama Anda"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="nama"
            >
              tahun Lulus
            </label>
            <input
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              id="tahunLulus"
              name="thn_lulus"
              required
              type="date"
              placeholder="Masukkan tahun lulus Anda"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="nama"
            >
              NIM
            </label>
            <input
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              id="nim"
              name="nim"
              type="number"
              required
              placeholder="Masukkan NIM Anda"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="noTelp"
            >
              Program Studi
            </label>
            <select
              name="perguruan"
              id="perguruan"
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              required
            >
              <option value="">Pilih Program Studi</option>
              <option value="Rekayasa Perangkat Lunak">
                Rekayasa Perangkat Lunak
              </option>
              <option value="Multimedia">Multimedia</option>
              <option value="Teknik Komputer dan Jaringan">
                Teknik Komputer dan Jaringan
              </option>
              <option value="Sistem Informasi">Sistem Informasi</option>
              <option value="Teknik Informatika">Teknik Informatika</option>
            </select>
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="email"
            >
              Email
            </label>
            <input
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              id="email"
              name="email"
              type="email"
              placeholder="Masukkan email Anda"
              required
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="noMahasiswa"
            >
              NIK
            </label>
            <input
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              id="nik"
              name="nik"
              type="number"
              placeholder="Masukkan NIK Anda"
              required
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="noMahasiswa"
            >
              Tgl Lahir
            </label>
            <input
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              id="tglLahir"
              name="tgl_lahir"
              type="date"
              placeholder="Masukkan tanggal lahir Anda"
              required
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="noMahasiswa"
            >
              NPWP
            </label>
            <input
              class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-red-400 hover:border-red-300 focus:ring-1 focus:ring-red-200 shadow-md"
              id="npwp"
              name="npwp"
              type="number"
              placeholder="Masukkan NPWP Anda"
              required
              />
          </div>
        </div>
        <div class="flex gap-4 items-center justify-center mt-6">
          <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="submit"
          >
            Kirim
          </button>
          <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="reset"
          >
            Reset
          </button>
        </div>
      </form>
    </div>
  </div>
</section>