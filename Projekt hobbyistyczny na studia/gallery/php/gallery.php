<?php
session_start();

require '/var/www/prod/vendor/autoload.php';
require 'permissions.php';
include 'create_thumbnails.php';
include 'create_watermarked_images.php';
include 'login.php';
include 'register.php';

$userLoggedIn = isset($_SESSION['user_id']);

if ($userLoggedIn) {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
    echo "Welcome, $username! <a href='logout.php'>Logout</a>";
} else {
    echo "You are logged out.";
}

include 'getImage.php';

function updateOrCreateDocument($collection, $document)
{
    $existingDocument = $collection->findOne(['filename' => $document['filename']]);
    if ($existingDocument) {
        $updateResult = $collection->updateOne(
            ['filename' => $document['filename']],
            ['$set' => $document]
        );
        if ($updateResult->getModifiedCount() > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        $insertResult = $collection->insertOne($document);
        if ($insertResult->getInsertedCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $watermarkText = isset($_POST['watermark']) ? trim($_POST['watermark']) : '';

    if (empty($watermarkText)) {
        echo "Watermark text is required.";
    } else {
        $uploadDir = '../images/';
        setPermissions($uploadDir);
        $uploadedFile = $_FILES['image'];
        $originalImagePath = $uploadDir . basename($uploadedFile['name']);
        
        $check = getimagesize($uploadedFile['tmp_name']);
        if ($check === false) {
            echo "Invalid image file.";
        } elseif ($uploadedFile['size'] > 1000000) {
            echo "File size exceeds the limit (1MB).";
        } elseif (!in_array(strtolower(pathinfo($originalImagePath, PATHINFO_EXTENSION)), ['jpg', 'png'])) {
            echo "Invalid file format. Allowed formats: JPG, PNG.";
        } else {
            if (move_uploaded_file($uploadedFile['tmp_name'], $originalImagePath)) {
                $watermarkedImagePath = applyWatermark($originalImagePath, $watermarkText);

                $thumbnailDir = '../thumbnails/';
                setPermissions($thumbnailDir);
                $thumbnailPath = $thumbnailDir . 'thumbnail_' . basename($uploadedFile['name']);
                generateThumbnail($originalImagePath, $thumbnailPath, 200, 125);

                $client = new MongoDB\Client(
                    "mongodb://localhost:27017/wai",
                    [
                        'username' => 'wai_web',
                        'password' => 'w@i_w3b',
                    ]
                );
                $collection = $client->wai->imageinfo;

                $imageinfo = [
                    'filename' => basename($uploadedFile['name']),
                    'title' => isset($_POST['title']) ? $_POST['title'] : '',
                    'author' => isset($_POST['author']) ? $_POST['author'] : '',
                ];

                if (updateOrCreateDocument($collection, $imageinfo)) {
                    $imageInfo = getImageInfoFromMongoDB($imageinfo['filename']);

                    echo "Image processed successfully.<br>";
                    echo "Title: " . $imageInfo['title'] . "<br>";
                    echo "Author: " . $imageInfo['author'];
                } else {
                    echo "Error updating or inserting document.";
                }
            } else {
                echo "Error uploading file.";
            }
        }
    }
}

$watermarkedImages = glob('../watermark/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$thumbnails = glob('../thumbnails/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$originalImages = glob('../images/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

$imagesPerPage = 2;
$totalImages = count($thumbnails);
$totalPages = ceil($totalImages / $imagesPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $imagesPerPage;
$visibleThumbnails = array_slice($thumbnails, $offset, $imagesPerPage);

if (isset($_GET['message'])) {
    echo htmlspecialchars($_GET['message']);
}

$userLoggedIn = isset($_SESSION['user_id']);
$rememberedImages = isset($_SESSION['rememberedImages']) ? $_SESSION['rememberedImages'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watermarked Images and Thumbnails</title>
    <link rel="stylesheet" href="../../resources/style.css">
    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
        }

        .thumbnail {
            margin: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="navbar">
            <div class="dropdown">
                <button class="dropbtn">Fun facts
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><style>svg{fill:#fafcff}</style><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>
                </button>
                <div class="dropdown-content">
                    <a href="../../html/funfacts.html">Fun facts</a>
                    <a href="../../extensibles/one.xml">More about</a>
                    <a href="gallery.php">Gallery</a>
                    <a href="selected_images.php">Selected Images</a>
                </div>
            </div>
            <a class="main" href="../../index.html">MAIN PAGE</a>
            <a href="../../html/ranking.html">Ranking</a>
        </div>
    </nav>

    <?php
    if ($userLoggedIn) {
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
        echo '<div class="account">
        <a id="logout" href="../php/logout.php">LOGOUT</a>
        <a id="register" href="../html/register.html">REGISTER</a>
    </div>';
    } else {
        echo '<div class="account">
        <a id="login" href="../html/login.html">LOGIN</a>
        <a id="register" href="../html/register.html">REGISTER</a>
    </div>';
    }
    ?>
</br></br></br></br></br>
<form class="phpform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <div class="border">
        <label for="image">Choose an image (Max size: 1MB, Formats: JPG, PNG):</label>
        <input type="file" name="image" id="image" accept="image/jpeg, image/png" required>
    </div>
    <div class="border">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
    </div>
    <div class="border">
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" required>
    </div>
    <div class="border">
        <label for="watermark">Watermark text:</label>
        <input type="text" name="watermark" id="watermark" required>
    </div>
    <button type="submit" name="submit">Upload</button>
</form>

<form action="remember_selected.php" method="post">
        <div class="gallery">
            <?php foreach ($visibleThumbnails as $thumbnail) : ?>
                <div class="thumbnail">
                    <?php
                    $originalImageFileName = str_replace('thumbnail_', '', basename($thumbnail));
                    $imageInfo = getImageInfoFromMongoDB($originalImageFileName);
                    $watermarkedImagePath = '../watermark/' . $originalImageFileName;

              
                    $isChecked = in_array($originalImageFileName, $rememberedImages);
                    ?>
                    <input type="checkbox" name="selectedImages[]" value="<?php echo $originalImageFileName; ?>" <?php echo $isChecked ? 'checked' : ''; ?>>
                    <a href="<?php echo $watermarkedImagePath; ?>" target="_blank">
                        <img src="<?php echo $thumbnail; ?>" alt="Thumbnail">
                    </a>
                    <p>Title: <?php echo $imageInfo['title']; ?></p>
                    <p>Author: <?php echo $imageInfo['author']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" name="rememberSelected">Remember Selected</button>
    </form>

<?php

if (isset($_POST['rememberSelected'])) {
    $_SESSION['rememberedImages'] = $_POST['selectedImages'];
}
?>

<div>
    <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
        <a class="pagination" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
    <?php endfor; ?>
</div>
</body>
</html>
