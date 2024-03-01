<?php
include 'permissions.php';
$uploadDir = '../images/';
setPermissions($uploadDir);

if (isset($_POST['submit'])) {
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check !== false) {
        if (file_exists($uploadFile)) {
            echo "File already exists.";
        } else {
            if ($_FILES['image']['size'] > 1000000) {
                echo "Error: File is too large. Maximum file size is 1 MB.";
            } else {
                $allowedFormats = ['jpg', 'png'];
                if (in_array($imageFileType, $allowedFormats)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        echo "File uploaded successfully.";
                    } else {
                        echo "Error uploading file.";
                    }
                } else {
                    echo "Error: Invalid file format. Allowed formats: " . implode(', ', $allowedFormats);
                }
            }
        }
    } else {
        echo "Error: File is not an image.";
    }
}
?>
