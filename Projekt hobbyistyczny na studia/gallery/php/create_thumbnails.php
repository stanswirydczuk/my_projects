<?php
function generateThumbnail($imagePath, $thumbnailPath, $width, $height) {
    $imageType = exif_imagetype($imagePath);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($imagePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($imagePath);
            break;
        default:
            echo "Unsupported image type";
            return false;
    }

    $thumbnail = imagecreatetruecolor($width, $height);

    imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

    imagejpeg($thumbnail, $thumbnailPath);

    imagedestroy($image);
    imagedestroy($thumbnail);
}
?>
