<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Tracer Study ITESA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-[10rem] font-bold text-white leading-none"><?= $code ?></h1>
        <h2 class="text-2xl font-semibold text-white mb-4"><?= $title ?></h2>
        <p class="text-gray-400 mb-8"><?= $message ?: 'Terjadi kesalahan pada sistem' ?></p>
        <a href="/traceritesa/tracer" class="inline-block bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html> 