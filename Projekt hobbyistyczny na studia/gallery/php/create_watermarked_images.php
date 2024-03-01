<?php
function applyWatermark($imagePath, $watermarkText) {
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

    $font = 300;
    $color = imagecolorallocate($image, 255, 255, 255);

    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    $fontHeight = imagefontheight($font);
    $fontWidth = imagefontwidth($font);

    $textWidth = strlen($watermarkText) * $fontWidth;
    $textHeight = $fontHeight;
    $x = ($imageWidth - $textWidth) / 2;
    $y = ($imageHeight - $textHeight) / 2 + $fontHeight;

    imagestring($image, $font, $x, $y, $watermarkText, $color);

    $watermarkDir = '../watermark/';
    setPermissions($watermarkDir);
    $watermarkedImagePath = $watermarkDir . basename($imagePath);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($image, $watermarkedImagePath);
            break;
        case IMAGETYPE_PNG:
            imagepng($image, $watermarkedImagePath);
            break;
        default:
            echo "Unsupported image type";
            return false;
    }

    imagedestroy($image);

    return $watermarkedImagePath;
}
?>
