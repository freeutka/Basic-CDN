<?php
// Set your authentication key
$authKey = 'Put auth key here';
// Function to generate a random name for the uploaded file
function generateRandomName($extension) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    $length = 10;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString . '.' . $extension;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the authentication key is provided and correct
    if (isset($_POST['auth_key']) && $_POST['auth_key'] === $authKey) {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_type = $file['type'];
            $file_size = $file['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            // Check if the file extension is allowed
            if (in_array($file_ext, $allowed_extensions)) {
                // Generate a random name for the file
                $new_file_name = generateRandomName($file_ext);
                $path = 'uploads/' . $new_file_name;
                // Move the uploaded file to the destination folder
                if (move_uploaded_file($file_tmp, $path)) {
                    // Generate the URL to view the uploaded file
                    $file_url = 'https://cdn.sillychat.tech/uploads/' . $new_file_name;
                    echo $file_url;
                } else {
                    echo 'Failed to upload the file.';
                }
            } else {
                echo 'File type not allowed.';
            }
        } else {
            echo 'No file uploaded.';
        }
    } else {
        echo 'Invalid authentication key.';
    }
}
