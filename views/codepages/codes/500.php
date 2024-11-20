<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer/vendor/autoload.php';
use App\Helpers\AssetHelper;
$code = 500;
$title = 'Kesalahan Server';
$message = 'Maaf, terjadi kesalahan pada server. Tim kami sedang menanganinya.';
require_once __DIR__ . '/../layouts-err.php';