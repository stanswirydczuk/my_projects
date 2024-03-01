<?php
session_start();

require '/var/www/prod/vendor/autoload.php';

use MongoDB\Client;

$client = new MongoDB\Client(
    "mongodb://localhost:27017/wai",
    [
        'username' => 'wai_web',
        'password' => 'w@i_w3b',
    ]
);
$database = $client->selectDatabase("wai");
$collection = $database->selectCollection("users");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $collection->findOne(['username' => $username]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['_id'];
        $_SESSION['username'] = $username;
        echo "Login successful! Welcome, $username!";
        echo '<button onclick="goGallery()">Go Back</button>';
    } else {
        header("Location: ../html/login.html?error=Incorrect username or password");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../resources/style.css">
</head>
<body>
    <script>
        function goGallery() {
            window.history.go(-2);
        }
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
