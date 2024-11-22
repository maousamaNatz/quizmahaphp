<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Helpers\AssetHelper;
use App\Middleware\AdminAuthMiddleware;
use App\Controller\AnswerController;

// Cek autentikasi
AdminAuthMiddleware::authenticate();

$answerController = new AnswerController();
$answers = $answerController->getAllAnswersWithUserDetails();

$itemsPerPage = 10; // Jumlah item per halaman
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalItems = count($answers);
$totalPages = ceil($totalItems / $itemsPerPage);

// Validasi halaman saat ini
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Slice array untuk mendapatkan data sesuai halaman
$start = ($currentPage - 1) * $itemsPerPage;
$paginatedAnswers = array_slice($answers, $start, $itemsPerPage);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <link rel="shortcut icon" href="<?= AssetHelper::url(
      'media/logos.png'
  ) ?>" type="image/x-icon">  
  <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
  <link rel="stylesheet" href="<?= AssetHelper::url('css/style.css') ?>">  
  <style>
    [animate], [animate2] {
  font-size: inherit;
  transform: none;
}
.split-text {
  font-size: inherit;
    line-height: inherit;
  }
  </style>
  <title>Dashboard</title>
</head>
<body class="bg-gray-100 overflow-hidden">
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbardash.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/notification.php'; ?>
<!-- strat wrapper -->
<div class="h-screen flex flex-row flex-wrap">
  
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/sidebar.php'; ?>
  <!-- end sidbar -->

  <!-- strat content -->
  <div class="bg-gray-100 flex-1 p-6 md:mt-16 overflow-y-auto overflow-x-hidden w-full h-full"> 
    <!-- End General Report -->

    <div class="flex items-center justify-between p-3">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">view question</h1>
        <a href="/traceritesa/tracer/questions" class="bg-blue-500 text-white px-4 py-2 rounded-md">Kembali</a>
    </div>
    <section class="px-4 mx-auto h-full w-full">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="w-full flex items-center justify-between">
          <form action="" class="flex items-center justify-center relative w-full max-w-2xl">
            <label for="" class="absolute left-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 ">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </label>
            <input type="text" name="search" id="search" class="pl-10 font-medium font-poppins w-full p-2 rounded-md border border-gray-300" placeholder="Search...">
          </form>
          <div class="flex items-center justify-end w-auto h-auto mt-4">
              <a href="/traceritesa/tracer/export.php" class="flex items-center justify-center gap-2 w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 ">
                  <svg width="150px" viewBox="-9.66 0 103.8 103.8" xmlns="http://www.w3.org/2000/svg" class="h-auto"> 
                    <g id="Group_2" data-name="Group 2" transform="translate(-472 -231.9)">
                      <path id="Path_47" data-name="Path 47" d="M522.1,246.8H499.3c-11.6,0-21.1,7.6-21.1,15.2" fill="none" stroke="#ffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="4"/>
                      <line id="Line_22" data-name="Line 22" y1="22.7" transform="translate(551 255.1)" fill="none" stroke="#ffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="4"/>
                      <path id="Path_48" data-name="Path 48" d="M532.6,233.9H509.8c-11.6,0-21.1,6.7-21.1,15" fill="none" stroke="#ffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="4"/>
                      <path id="Path_49" data-name="Path 49" d="M534.7,256.7H490.3A16.3,16.3,0,0,0,474,273v44.4a16.3,16.3,0,0,0,16.3,16.3h44.4A16.3,16.3,0,0,0,551,317.4V273A16.3,16.3,0,0,0,534.7,256.7Z" fill="none" stroke="#ffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="4"/>
                      <path id="Path_50" data-name="Path 50" d="M540.7,242.3a3.567,3.567,0,0,1,4.2,4.2,3.518,3.518,0,0,1-2.8,2.8,3.567,3.567,0,0,1-4.2-4.2A3.518,3.518,0,0,1,540.7,242.3Z" fill="#ffff" stroke="#ffff" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"/>
                      <path id="Path_51" data-name="Path 51" d="M551.2,233.5a3.567,3.567,0,0,1,4.2,4.2,3.518,3.518,0,0,1-2.8,2.8,3.567,3.567,0,0,1-4.2-4.2A3.651,3.651,0,0,1,551.2,233.5Z" fill="#ffff" stroke="#ffff" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"/>
                      <line id="Line_23" data-name="Line 23" x2="23.1" transform="translate(491.1 278.8)" fill="none" stroke="#ffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="4"/>
                      <line id="Line_24" data-name="Line 24" x2="36.1" transform="translate(491.1 295.2)" fill="none" stroke="#ffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="4"/>
                    </g>
                  </svg>
                  <span>Eksport</span>
              </a>
          </div>
        </div>
    </div>
  
    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="inline-flex overflow-hidden bg-white border divide-x rounded-lg  rtl:flex-row-reverse  ">
            <button data-filter="all" class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 bg-gray-100 sm:text-sm  ">
                Semua
            </button>

            <button data-filter="bekerja" class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm  hover:bg-gray-100">
                Bekerja
            </button>

            <button data-filter="wirausaha" class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm  hover:bg-gray-100">
                Wirausaha
            </button>

            <button data-filter="belum_bekerja" class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm  hover:bg-gray-100">
                Belum Bekerja
            </button>
        </div>
    </div>

    <div class="flex flex-col mt-6">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden border border-gray-200  md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 ">
                        <thead class="bg-gray-50 ">
                            <tr>
                                <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 ">
                                    <button class="flex items-center gap-x-3 focus:outline-none">
                                        <span>Name & NIM</span>

                                        <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                            <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                            <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                        </svg>
                                    </button>
                                </th>

                                <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
                                    Status Kerja
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
                                    Perguruan
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">License use</th>

                                <th scope="col" class="relative py-3.5 px-4">
                                    <span class="sr-only">
                                        Lihat Jawaban
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200  ">
                            <?php foreach ($paginatedAnswers as $answer): 
                                $hash = $answerController->generateHash($answer['user_id']);
                                $statusKerja = isset($answer['status_kerja']) ? $answer['status_kerja'] : '';
                                $statusFilter = '';
                                
                                // Tentukan filter berdasarkan jawaban pertanyaan nomor 1
                                if (strpos(strtolower($statusKerja), 'bekerja') !== false) {
                                    $statusFilter = 'bekerja';
                                } elseif (strpos(strtolower($statusKerja), 'wirausaha') !== false) {
                                    $statusFilter = 'wirausaha';
                                } else {
                                    $statusFilter = 'belum_bekerja';
                                }
                            ?>
                            <tr data-status="<?= htmlspecialchars($statusFilter) ?>">
                                <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                    <div>
                                        <h2 class="font-medium text-gra  htmlspecialchars($answer['nama']) ?></h2>
                                        <p class="text-sm font-normal text-gray-600 "><?= htmlspecialchars($answer['nim']) ?></p>
                                    </div>
                                </td>
                                <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                    <div class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 ">
                                        <?= htmlspecialchars($answer['status_kerja']) ?>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                    <div>
                                        <h4 class="text-gray-700 "><?= htmlspecialchars($answer['perguruan']) ?></h4>
                                        <p class="text-gray-500 ">Tahun Lulus: <?= htmlspecialchars($answer['thn_lulus']) ?></p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                    <a href="/traceritesa/tracer/lihatapcb?q=<?= urlencode($hash) ?>" 
                                       class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 transition-colors">
                                        Lihat Jawaban
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($paginatedAnswers)): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data jawaban yang tersedia
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center justify-between w-full">
        <div class="text-sm text-gray-500 w-auto">
            Halaman <span class="font-medium text-gray-700"><?= $currentPage ?> dari <?= $totalPages ?></span> 
        </div>

        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
            <a href="?page=<?= max(1, $currentPage - 1) ?>" 
               class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 <?= $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                <span class="sr-only">Previous</span>
                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                </svg>
            </a>

            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $startPage + 4);
            
            if ($startPage > 1): ?>
                <a href="?page=1" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</a>
                <?php if ($startPage > 2): ?>
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                <?php endif;
            endif;

            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="?page=<?= $i ?>" 
                   class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?= $i === $currentPage ? 'bg-indigo-600 text-white' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50' ?> focus:z-20 focus:outline-offset-0">
                    <?= $i ?>
                </a>
            <?php endfor;

            if ($endPage < $totalPages): 
                if ($endPage < $totalPages - 1): ?>
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                <?php endif; ?>
                <a href="?page=<?= $totalPages ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><?= $totalPages ?></a>
            <?php endif; ?>

            <a href="?page=<?= min($totalPages, $currentPage + 1) ?>" 
               class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 <?= $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : '' ?>">
                <span class="sr-only">Next</span>
                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </a>
        </nav>
    </div>
</section>

  </div>
  <!-- end content -->

</div>
<!-- end wrapper -->

<!-- script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="<?= AssetHelper::url('dist/assets/main-5r8mEzWs.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil elemen yang diperlukan
    const searchInput = document.getElementById('search');
    const filterButtons = document.querySelectorAll('.inline-flex.overflow-hidden button');
    const tableBody = document.querySelector('tbody');
    const tableRows = Array.from(tableBody.getElementsByTagName('tr'));
    const sortButton = document.querySelector('th button');

    // Fungsi untuk filter berdasarkan pencarian
    function filterBySearch(searchTerm) {
        tableRows.forEach(row => {
            const nama = row.querySelector('td:first-child h2')?.textContent || '';
            const nim = row.querySelector('td:first-child p')?.textContent || '';
            const statusKerja = row.getAttribute('data-status');
            
            const matchSearch = nama.toLowerCase().includes(searchTerm.toLowerCase()) ||
                              nim.toLowerCase().includes(searchTerm.toLowerCase());
            const matchFilter = currentFilter === 'all' || statusKerja === currentFilter;
            
            row.style.display = matchSearch && matchFilter ? '' : 'none';
        });
    }

    // Event listener untuk input pencarian
    searchInput.addEventListener('input', (e) => {
        filterBySearch(e.target.value);
        // Reset halaman ke 1 saat pencarian
        updateURLParameter('page', '1');
    });

    // Fungsi untuk filter berdasarkan status
    function filterByStatus(status) {
        tableRows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(2)');
            const statusText = statusCell.textContent.trim().toLowerCase();
            
            if (status === 'all') {
                row.style.display = '';
            } else if (status === 'monitored' && statusText.includes('customer')) {
                row.style.display = '';
            } else if (status === 'unmonitored' && statusText.includes('churned')) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Event listener untuk tombol filter
    filterButtons.forEach(button => { 
        button.addEventListener('click', () => {
            // Hapus kelas aktif dari semua tombol
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-gray-100', '');
                btn.classList.add('hover:bg-gray-100');
            });

            // Tambah kelas aktif ke tombol yang diklik
            button.classList.add('bg-gray-100', '');
            button.classList.remove('hover:bg-gray-100');

            // Filter berdasarkan teks tombol
            const filterText = button.textContent.trim().toLowerCase();
            switch(filterText) {
                case 'view all':
                    filterByStatus('all');
                    break;
                case 'monitored':
                    filterByStatus('monitored');
                    break;
                case 'unmonitored':
                    filterByStatus('unmonitored');
                    break;
            }
            // Reset halaman ke 1 saat filter berubah
            updateURLParameter('page', '1');
        });
    });

    // Variabel untuk tracking arah pengurutan
    let sortDirection = 'asc';

    // Fungsi untuk mengurutkan tabel
    function sortTable() {
        const sortedRows = tableRows.sort((a, b) => {
            const aText = a.querySelector('td:first-child h2').textContent.toLowerCase();
            const bText = b.querySelector('td:first-child h2').textContent.toLowerCase();
            
            if (sortDirection === 'asc') {
                return aText.localeCompare(bText);
            } else {
                return bText.localeCompare(aText);
            }
        });

        // Kosongkan dan isi ulang tbody
        tableBody.innerHTML = '';
        sortedRows.forEach(row => tableBody.appendChild(row));
        
        // Toggle arah pengurutan
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
    }

    // Event listener untuk tombol pengurutan
    sortButton.addEventListener('click', sortTable);

    // Tambahkan di dalam event listener DOMContentLoaded yang sudah ada
    function updateURLParameter(key, value) {
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.set(key, value);
        const newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
        history.pushState(null, '', newRelativePathQuery);
    }
});
</script>

<!-- end script -->

<?php if (isset($success)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Alpine.store('notifications').add('<?php echo htmlspecialchars($success); ?>', 'success');
  });
</script>
<?php endif; ?>

<?php if (isset($error)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Alpine.store('notifications').add('<?php echo htmlspecialchars($error); ?>', 'error');
  });
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const tableRows = document.querySelectorAll('tbody tr');

    let currentFilter = 'all';

    function filterTable(status) {
        currentFilter = status;
        tableRows.forEach(row => {
            const statusKerja = row.getAttribute('data-status');
            
            if (status === 'all' || statusKerja === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Hapus kelas aktif dari semua tombol
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-gray-100');
                btn.classList.add('hover:bg-gray-100');
            });

            // Tambah kelas aktif ke tombol yang diklik
            button.classList.add('bg-gray-100');
            button.classList.remove('hover:bg-gray-100');

            // Filter tabel berdasarkan data-filter
            const filterValue = button.getAttribute('data-filter');
            filterTable(filterValue);
        });
    });
});
</script>

</body>
</html>

