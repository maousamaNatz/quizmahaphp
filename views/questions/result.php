<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <style>
      .font-poppins {
        font-family: "poppins", sans-serif;
      }
    </style>
  </head>
  <body>
    <header class="flex justify-between items-center p-6 bg-white shadow-md">
      <div class="text-2xl font-bold text-gray-800">
        Tracer
        <span class="text-red-500"> Itesa </span>
      </div>
      <nav
        class="desktop-menu space-x-8 absolute top-0 right-0 w-full h-full md:w-auto md:h-auto bg-red-500 md:bg-transparent md:relative md:block z-10"
      >
        <a class="text-red-500" href="#"> Home </a>
        <a class="text-gray-800" href="#"> About Us </a>
        <a class="text-gray-800" href="#"> Services </a>
        <a class="text-gray-800" href="#"> Contact Us </a>
      </nav>
      <button class="mobile-menu md:hidden">
        <i class="fas fa-bars"> </i>
      </button>
    </header>
    <div class="flex items-center justify-center w-full h-full">
      <div
        class="container flex flex-col items-center justify-center h-full px-4"
      >
        <div
          class="bg-slate-200 p-5 rounded-md w-full max-w-[500px] flex items-center justify-center flex-col"
        >
        <img src="./animation love GIF by POST-WEB - Find & Share on GIPHY.gif" class="w-[210px]" alt="">
          <h1 class="text-[3em] font-poppins font-bold text-gray-700">
            Trimakasih
          </h1>
          <a href="" class="">Lihat jawaban</a>
        </div>
      </div>
    </div>
<?php include "../components/footer.php"?>
  </body>
</html>