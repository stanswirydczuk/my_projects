<?php
session_start();


if (empty($_SESSION['rememberedImages'])) {
    $_SESSION['rememberedImages'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rememberSelected'])) {
    $selectedImages = isset($_POST['selectedImages']) ? $_POST['selectedImages'] : [];

    $_SESSION['rememberedImages'] = array_unique(array_merge($_SESSION['rememberedImages'], $selectedImages));

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeSelected'])) {
    $selectedImages = isset($_POST['selectedImages']) ? $_POST['selectedImages'] : [];

    $_SESSION['rememberedImages'] = array_diff($_SESSION['rememberedImages'], $selectedImages);
}

header('Location: gallery.php');
exit;
?>
