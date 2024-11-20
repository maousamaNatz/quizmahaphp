<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Helpers\AssetHelper;
use App\Controller\AdminController;
use App\Helpers\SessionHelper;

SessionHelper::startSession();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Tracer Study</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="<?= AssetHelper::url('media/logos.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    <link rel="stylesheet" href="<?= AssetHelper::url('css/styles.css') ?>">
</head>
<body>

<!-- source:https://codepen.io/owaiswiz/pen/jOPvEPB -->
<div class="min-h-screen bg-gray-100 text-gray-900 flex justify-center">
    <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
        <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
            <div class="flex gap-2 items-end justify-center font-poppins mb-10">
                <img src="<?= AssetHelper::url('media/logos.png') ?>"
                    class="w-[50px]" />
                <h1 class="text-[15px] xl:text-3xl font-extrabold">
                    Tracer Study
                </h1>
            </div>
            <div class="mt-12 flex flex-col items-center">
                <h1 class="text-2xl xl:text-3xl font-extrabold">
                    Login
                </h1>
                <div class="w-full flex-1 mt-8">
                    <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>
                    <div class="mx-auto max-w-xs">
                        <form method="POST" action="/traceritesa/tracer/login/process">
                            <input
                                name="username"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                type="text" 
                                placeholder="Username" 
                                required
                            />
                            <input
                                name="password"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white mt-5"
                                type="password" 
                                placeholder="Password" 
                                required
                            />
                            <button type="submit"
                                class="mt-5 flex items-center justify-center tracking-wide font-semibold bg-indigo-500 text-gray-100 w-full py-4 rounded-lg hover:bg-indigo-700 transition-all duration-300 ease-in-out focus:shadow-outline focus:outline-none">
                                <i class="fa fa-sign-in text-xl"></i>
                                <span class="ml-3">
                                    Login
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 bg-slate-100 text-center hidden lg:flex">
            <div class="m-12 xl:m-10 w-full bg-contain bg-center bg-no-repeat"
                style="background-image: url('<?= AssetHelper::url('media/5065069.svg') ?>');">
            </div>
        </div>
    </div>
</div>
</body>
</html>

