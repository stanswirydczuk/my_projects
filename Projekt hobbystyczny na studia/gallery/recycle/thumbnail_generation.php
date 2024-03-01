<?php
function generateThumbnail($imagePath, $thumbnailPath, $width, $height) {
    $image = imagecreatefromjpeg($imagePath); // Assuming all uploaded images are JPEG
    $thumbnail = imagecreatetruecolor($width, $height);

    // Resize the image to the specified dimensions
    imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

    // Save the thumbnail
    imagejpeg($thumbnail, $thumbnailPath);

    // Free up memory
    imagedestroy($image);
    imagedestroy($thumbnail);
}
?>