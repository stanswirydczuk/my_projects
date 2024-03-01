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

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    $existingUser = $collection->findOne(['username' => $username]);

    if ($existingUser) {
        echo "Username already taken. Choose a different username.";
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $newUser = [
        'email' => $email,
        'username' => $username,
        'password' => $hashedPassword,
    ];

    $insertResult = $collection->insertOne($newUser);

    if ($insertResult->getInsertedCount() > 0) {
        echo "Registration successful!";
        echo '<button onclick="goBack()">Go Back</button>';
    } else {
        echo "Error registering the user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../../resources/style.css">
</head>
<body>
    <script>
        function goBack() {
            window.history.go(-2);
        }
    </script>
</body>
</html>
