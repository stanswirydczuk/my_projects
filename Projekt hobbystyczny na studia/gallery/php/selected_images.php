<?php
session_start();
require 'getImage.php';

$rememberedImages = isset($_SESSION['rememberedImages']) ? $_SESSION['rememberedImages'] : [];

$fullImageInfo = [];
foreach ($rememberedImages as $imageFileName) {
    $fullImageInfo[] = getImageInfoFromMongoDB($imageFileName);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Images</title>
    <link rel="stylesheet" href="../../resources/style.css">
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
                    <a href="../../extensibles/anime-studios.xml">More about</a>
                    <a href="gallery.php">Gallery</a>
                    <a href="selected_images.php">Selected Images</a>
                </div>
            </div>
            <a class="main" href="../../index.html">MAIN PAGE</a>
            <a href="../../html/ranking.html">Ranking</a>
        </div>
    </nav>
    <form action="remove_selected.php" method="post">
        <?php foreach ($fullImageInfo as $imageInfo) : ?>
            <div>
                <?php
                $watermarkedImagePath = '../watermark/' . $imageInfo['filename'];
                $thumbnailedImagePath = '../thumbnails/thumbnail_' . $imageInfo['filename'];
                $isChecked = in_array($imageInfo['filename'], $rememberedImages) ? 'checked' : '';
                ?>
                <a href="<?php echo $watermarkedImagePath; ?>" target="_blank">
                    <img src="<?php echo $thumbnailedImagePath; ?>" alt="Thumbnail">
                </a>
                <input type="checkbox" name="selectedImages[]" value="<?php echo $imageInfo['filename']; ?>" <?php echo $isChecked; ?>>
                <p>Title: <?php echo $imageInfo['title']; ?></p>
                <p>Author: <?php echo $imageInfo['author']; ?></p>
            </div>
        <?php endforeach; ?>
        <button type="submit" name="removeSelected">Remove selected</button>
    </form>
</body>
</html>
