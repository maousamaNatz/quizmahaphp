<?php
// Cek akses langsung
if (!defined('APP_RUNNING')) {
    require_once __DIR__ . '/../views/codepages/codes/403.php';
    exit();
}

include __DIR__ . '/../views/questions/quest.php';

?>