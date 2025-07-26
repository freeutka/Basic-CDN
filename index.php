<?php
require_once __DIR__ . '/config.php';

// Function to generate a random filename with extension
function generateRandomName(string $extension, int $length = 10): string {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    $maxIndex = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $maxIndex)];
    }
    return $randomString . '.' . $extension;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panel/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate auth key
    if (!isset($_POST['auth_key']) || $_POST['auth_key'] !== $auth_key) {
        http_response_code(403);
        echo 'Invalid authentication key.';
        exit;
    }

    // Validate uploaded file
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo 'No file uploaded or upload error.';
        exit;
    }

    $file = $_FILES['file'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions, true)) {
        http_response_code(415);
        echo 'File type not allowed.';
        exit;
    }

    // Ensure uploads directory exists
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
        http_response_code(500);
        echo 'Failed to create upload directory.';
        exit;
    }

    // Generate unique filename and move file
    do {
        $new_file_name = generateRandomName($file_ext);
        $destination = $upload_dir . $new_file_name;
    } while (file_exists($destination));

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        http_response_code(500);
        echo 'Failed to upload the file.';
        exit;
    }

    // Output the full URL to the uploaded file
    echo $cdn_base_url . $new_file_name;
    exit;
}

http_response_code(405);
echo 'Method Not Allowed.';
exit;
