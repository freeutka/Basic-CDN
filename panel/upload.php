<?php
session_start();
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['logged_in'])) {
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="style.css">
      <title>Error</title>
    </head>
    <body>
      <div class="container"><div class="error">No access</div></div>
    </body>
    </html>
    <?php exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="style.css">
      <title>Upload error</title>
    </head>
    <body>
      <div class="container"><div class="error">Upload error</div></div>
    </body>
    </html>
    <?php exit;
}

$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$target = __DIR__ . '/../uploads/' . uniqid() . '.' . $ext;
move_uploaded_file($_FILES['file']['tmp_name'], $target);

header('Location: dashboard.php');
exit;