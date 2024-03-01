<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = '../images/';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Your form handling logic goes here
    
    // For example, you can process the uploaded file
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading file.";
    }
}

// Fetch the list of images for the gallery
$images = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>


    <!-- <script src="../../plugin/jquery-1.8.3.min.js"></script>
    <script src="../../jquery-ui/jquery-ui.js"></script> -->
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
                    <a href="gallery/html/gallery.html">Gallery</a>
                </div>
            </div>
            <a class="main" href="../../index.html">MAIN PAGE</a>
            <a href="../../html/ranking.html">Ranking</a>
        </div>
    </nav>

    <account>
        <div class="account"> 
            <a href="login.html">LOGIN</a>
            <a href="register.html">REGISTER</a>
        </div>
    </account>

    <h2>Image Upload</h2>
    <form action="../php/upload.php" method="post" enctype="multipart/form-data">
        <label for="image">Choose an image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <button type="submit" name="submit">Upload</button>
    </form>

    <div class="gallery">
        <?php foreach ($images as $image) : ?>
            <img src="../images/<?php echo basename($image); ?>" alt="Gallery Image">
        <?php endforeach; ?>
    </div>

    <!-- Your remaining HTML content here -->
</body>
</html>
