<?php
session_start();
require_once __DIR__ . '/../config.php';

if (isset($_SESSION['logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

$discord_auth_url = "https://discord.com/api/oauth2/authorize?client_id=$discord_client_id&redirect_uri=" . urlencode($discord_redirect_uri) . "&response_type=code&scope=identify";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign in</title>
    <link rel="stylesheet" href="style.css">
    <style>
      html, body {
        height: 100%;
      }
      body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
      }
    </style>
</head>
<body>
<div class="container">
    <h2>Sign in</h2>
    <form method="post" autocomplete="off">
        <input name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign in</button>
    </form>
    <?php if ($use_discord_login): ?>
      <a class="button discord-login" href="<?= htmlspecialchars($discord_auth_url) ?>">
        🔗 Sign in with Discord
      </a>
    <?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
</div>
</body>
</html>