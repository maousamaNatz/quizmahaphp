<?php

use App\Helpers\AssetHelper;
use App\Helpers\SessionHelper;

// Ambil data admin dari session
$adminUsername = SessionHelper::get('admin_username');
$adminRole = SessionHelper::get('admin_role');
?>

<!-- start navbar -->
<div class="md:fixed md:w-full md:top-0 md:z-20 flex flex-row flex-wrap items-center bg-white p-6 border-b border-gray-300">
    
    <!-- logo -->
    <div class="flex-none w-56 flex flex-row items-center">
      <img src="<?= AssetHelper::url(
          'media/logos.png'
      ) ?>" class="w-10 flex-none">
      <strong class="capitalize ml-1 flex flex-col">
        <span class="text-gray-900 text-nowrap">
            Tracer 
            <span class="text-red-500">
                Itesa
            </span>
        </span>
        <span class="text-gray-500 text-nowrap text-xs">
            Itesa Muhammadiyah Semarang
        </span>
      </strong>

      <button id="sliderBtn" class="flex-none text-right text-gray-900 hidden md:block">
        <i class="fad fa-list-ul"></i>
      </button>
    </div>
    <!-- end logo -->   
    
    <!-- navbar content toggle -->
    <button id="navbarToggle" class="hidden md:block md:fixed right-0 mr-6">
      <i class="fad fa-chevron-double-down"></i>
    </button>
    <!-- end navbar content toggle -->

    <!-- navbar content -->
    <div id="navbar" class="animated md:hidden md:fixed md:top-0 md:w-full md:left-0 md:mt-16 md:border-t md:border-b md:border-gray-200 md:p-10 md:bg-white flex-1 pl-3 flex flex-row flex-wrap justify-between items-center md:flex-col md:items-center">
      
      <!-- right -->
      <div class="flex ml-auto flex-row-reverse items-center"> 

        <!-- user -->
        <div class="dropdown relative md:static">

          <button onclick="toggleDropdown()" class="menu-btn focus:outline-none focus:shadow-outline flex flex-wrap items-center p-2 rounded-md hover:bg-gray-200 transition-all duration-300 ease-in-out">
            <div class="ml-2 capitalize flex ">
              <h1 class="text-sm text-gray-800 font-semibold m-0 p-0 leading-none">
                <?= htmlspecialchars($adminUsername) ?>
                <span class="text-xs text-gray-600">(<?= htmlspecialchars($adminRole) ?>)</span>
              </h1>
              <!-- icon -->
               
              <i class="fad fa-chevron-down ml-2 text-xs leading-none"></i> 
              <!-- end icon -->
            </div>                        
          </button>

          <div id="dropdownMenu" class="text-gray-500 menu hidden md:mt-10 md:w-full rounded bg-white shadow-md absolute z-20 right-0 w-40 mt-5 py-2 animated faster">

            <!-- item -->
            <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out" href="#">
              <i class="fad fa-user-edit text-xs mr-1"></i> 
              Edit Profil
            </a>     
            <!-- end item -->

            <hr>

            <!-- item -->
            <a href="/traceritesa/tracer/logout" class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out">
              <i class="fad fa-sign-out text-xs mr-1"></i> 
              Logout
            </a>     
            <!-- end item -->

          </div>
        </div>
        <!-- end user -->

      </div>
      <!-- end right -->
    </div>
    <!-- end navbar content -->

  </div>
<!-- end navbar -->

<script>
// Toggle Dropdown
function toggleDropdown() {
  document.getElementById('dropdownMenu').classList.toggle('hidden');
}

// Toggle Sidebar
document.getElementById('sliderBtn').addEventListener('click', function() {
  document.getElementById('sidebar').classList.toggle('md:hidden');
  document.getElementById('sidebar').classList.toggle('animated');
});

// Toggle Navbar di Mobile
document.getElementById('navbarToggle').addEventListener('click', function() {
  document.getElementById('navbar').classList.toggle('md:hidden');
});

// Tutup dropdown ketika klik di luar
window.onclick = function(event) {
  if (!event.target.matches('.menu-btn')) {
    var dropdowns = document.getElementById('dropdownMenu');
    if (!dropdowns.classList.contains('hidden')) {
      dropdowns.classList.add('hidden');
    }
  }
}
</script>