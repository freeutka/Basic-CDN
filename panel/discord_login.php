<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!$use_discord_login) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="style.css">
      <title>Discord Error</title>
    </head>
    <body>
      <div class="container"><div class="error">Discord login is disabled</div></div>
    </body>
    </html>
    <?php exit;
}
if (!isset($_GET['code'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="style.css">
      <title>Discord Error</title>
    </head>
    <body>
      <div class="container"><div class="error">No authorization code</div></div>
    </body>
    </html>
    <?php exit;
}

// Get access_token
$response = file_get_contents("https://discord.com/api/oauth2/token", false, stream_context_create([
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/x-www-form-urlencoded",
        "content" => http_build_query([
            "client_id" => $discord_client_id,
            "client_secret" => $discord_client_secret,
            "grant_type" => "authorization_code",
            "code" => $_GET['code'],
            "redirect_uri" => $discord_redirect_uri,
        ]),
    ]
]));

$token = json_decode($response, true);
if (!isset($token['access_token'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="style.css">
      <title>Discord Error</title>
    </head>
    <body>
      <div class="container"><div class="error">Authorization error</div></div>
    </body>
    </html>
    <?php exit;
}

// Get user data
$user = json_decode(file_get_contents("https://discord.com/api/users/@me", false, stream_context_create([
    "http" => [
        "method" => "GET",
        "header" => "Authorization: Bearer " . $token['access_token'],
    ]
])), true);

// Only allow one Discord ID:
if ($user['id'] !== $allowed_discord_id) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="style.css">
      <title>Discord Error</title>
    </head>
    <body>
      <div class="container"><div class="error">Access denied. You are not an allowed user.</div></div>
    </body>
    </html>
    <?php exit;
}

// Login
$_SESSION['logged_in'] = true;
$_SESSION['discord_user'] = $user;
header('Location: dashboard.php');
exit;
exit;
