<?php
function applyWatermark($imagePath, $watermarkText) {
    $image = imagecreatefromjpeg($imagePath); // Assuming all uploaded images are JPEG

    // Set the font and color for the watermark
    $font = 5; // Use GD default font
    $color = imagecolorallocate($image, 255, 255, 255); // White color

    // Calculate the position to center the watermark
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);
    
    // Get font dimensions
    $fontHeight = imagefontheight($font);
    $fontWidth = imagefontwidth($font);
    
    // Calculate position to center the watermark
    $textWidth = strlen($watermarkText) * $fontWidth;
    $textHeight = $fontHeight;
    $x = ($imageWidth - $textWidth) / 2;
    $y = ($imageHeight - $textHeight) / 2 + $fontHeight; // Adjusting for better vertical alignment

    // Apply the text watermark
    imagestring($image, $font, $x, $y, $watermarkText, $color);

    // Save the watermarked image
    $watermarkDir = '../watermark/';
    $watermarkedImagePath = $watermarkDir . basename($imagePath);
    imagejpeg($image, $watermarkedImagePath);

    // Free up memory
    imagedestroy($image);

    return $watermarkedImagePath;
}
?>