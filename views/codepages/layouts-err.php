<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/vendor/autoload.php';
use App\Helpers\AssetHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="shortcut icon" href="<?= AssetHelper::url('media/logos.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= AssetHelper::url('css/styles.css') ?>">
    <link rel="stylesheet" href="<?= AssetHelper::url('css/all.min.css') ?>">
</head>
<body class="bg-gray-100 h-screen">
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/loading.php'; ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/navbar.php'; ?>
    <div class="flex items-center justify-center h-[calc(100vh-100px)]">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-[5rem] md:text-[10rem] font-bold text-gray-800 mb-4"><?= htmlspecialchars($code) ?></h1>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4"><?= htmlspecialchars($title) ?></h2>
                    <p class="text-gray-600 mb-8"><?= htmlspecialchars($message) ?></p>
                </div>
                <div class="col-md-12 text-center">
                    <a href="/traceritesa/tracer/" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>  
    </div>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/views/components/footer.php'; ?>
    <script src="<?= AssetHelper::url('dist/assets/main-CSgIVEFG.js') ?>"></script>
</body>
</html> 