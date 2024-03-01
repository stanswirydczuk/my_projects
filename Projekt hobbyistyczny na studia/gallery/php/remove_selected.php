<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeSelected'])) {
    $selectedImages = isset($_POST['selectedImages']) ? $_POST['selectedImages'] : [];

    $_SESSION['rememberedImages'] = array_diff($_SESSION['rememberedImages'], $selectedImages);
}

header('Location: selected_images.php');
exit();
?>
