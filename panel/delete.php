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

if (isset($_GET['f'])) {
    $filename = basename($_GET['f']);
    $src = __DIR__ . "/../uploads/$filename";
    $trash = __DIR__ . "/../uploads/.trash/$filename";
    if (is_file($src)) {
        if (!is_dir(dirname($trash))) mkdir(dirname($trash), 0755, true);
        rename($src, $trash); // move to trash
    }
}
header('Location: dashboard.php');
exit;