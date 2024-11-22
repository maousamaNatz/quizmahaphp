<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\ExportController;
use App\Config\Database;

$db = (new Database())->connect();

if (!$db) {
    die('Koneksi database gagal');
}

$exportController = new ExportController($db);
$exportController->exportToExcel(); 