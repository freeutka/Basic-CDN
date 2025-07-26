<?php
session_start();
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['logged_in'])) { http_response_code(403); exit; }
if (isset($_GET['f'])) {
    $filename = basename($_GET['f']);
    $src = __DIR__ . "/../uploads/.trash/$filename";
    if (is_file($src)) unlink($src);
}
header('Location: dashboard.php?tab=trash');
exit;
