<?php
use App\Helpers\AssetHelper;

?>
<!-- component -->
<nav class="relative px-4 py-4 flex justify-between items-center bg-white">
  <a class="text-2xl font-bold flex items-center gap-2 leading-none" href="#">
      <img src="<?= AssetHelper::url('media/logos.png') ?>" alt="logo" class="h-12">  
      <span>
        Tracer <span class="text-red-500">Itesa</span>
      </span>
  </a>
  <div class="lg:hidden">
    <button class="navbar-burger flex items-center text-blue-600 p-3">
      <svg class="block h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <title>Mobile menu</title>
        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
      </svg>
    </button>
  </div>
  <ul class="hidden absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 lg:flex lg:mx-auto lg:flex lg:items-center lg:w-auto lg:space-x-6">
  <li class="mb-1">
          <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="#">Home</a>
        </li>
        <li class="mb-1">
          <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="#">Itesa.ac.id</a>
        </li>
        <li class="mb-1">
          <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="#">PMB</a>
        </li>
  </ul>
  <a class="hidden lg:inline-block lg:ml-auto lg:mr-3 py-2 px-6 bg-gray-50 bg-red-500 hover:bg-red-600 text-sm text-white font-bold  rounded-xl transition duration-200" href="#">Sign In</a>
</nav>
<!-- mobile menu -->
<div class="navbar-menu relative z-50 hidden transition-all duration-300">
  <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>
  <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-6 px-6 bg-white border-r overflow-y-auto">
    <div class="flex items-center mb-8">
      <button class="navbar-close">
        <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div>
      <ul>
        <li class="mb-1">
          <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="#">Home</a>
        </li>
        <li class="mb-1">
          <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="#">Itesa.ac.id</a>
        </li>
        <li class="mb-1">
          <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="#">PMB</a>
        </li>
      </ul>
    </div>
    <div class="mt-auto">
      <div class="pt-6">
        <a class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold leading-none bg-gray-50 bg-red-500 hover:bg-red-600 text-white rounded-xl" href="#">Sign in</a>
      </div>
      <p class="my-4 text-xs text-center text-gray-400">
        Copyright By <a href="https://github.com/maousamaNatz" class="hover:decoration-1 hover:text-gray-500">Natz</a>
        <span class="apcb"></span>
      </p>
    </div>
  </nav>
</div>

<script>
// Burger menus
document.addEventListener('DOMContentLoaded', function() {
    // open
    const burger = document.querySelectorAll('.navbar-burger');
    const menu = document.querySelectorAll('.navbar-menu');

    if (burger.length && menu.length) {
        for (var i = 0; i < burger.length; i++) {
            burger[i].addEventListener('click', function() {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    // close
    const close = document.querySelectorAll('.navbar-close');
    const backdrop = document.querySelectorAll('.navbar-backdrop');

    if (close.length) {
        for (var i = 0; i < close.length; i++) {
            close[i].addEventListener('click', function() {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    if (backdrop.length) {
        for (var i = 0; i < backdrop.length; i++) {
            backdrop[i].addEventListener('click', function() {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            menu.forEach(function(element) {
                if (!element.classList.contains('hidden')) {
                    element.classList.add('hidden');
                }
            });
        }
    });
});
</script>