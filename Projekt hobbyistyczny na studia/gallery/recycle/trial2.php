<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '/var/www/prod/vendor/autoload.php';
require 'permissions.php';
// Include the image processing functions file
include 'create_thumbnails.php';
include 'create_watermarked_images.php';

// Function to get image information from MongoDB
function getImageInfoFromMongoDB($filename)
{
    // Connect to MongoDB (replace with your MongoDB connection details)
    $client = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]
    );
    $collection = $client->wai->imageinfo;

    // Query MongoDB to get information for the given filename
    $result = $collection->findOne(['filename' => $filename]);

    // Return the image information (modify based on your MongoDB schema)
    return [
        'title' => isset($result['title']) ? $result['title'] : '',
        'author' => isset($result['author']) ? $result['author'] : '',
    ];
}

// Function to update or insert a document in MongoDB
function updateOrCreateDocument($collection, $document)
{
    // Check if the document already exists
    $existingDocument = $collection->findOne(['filename' => $document['filename']]);

    if ($existingDocument) {
        // Update the existing document
        $updateResult = $collection->updateOne(
            ['filename' => $document['filename']],
            ['$set' => $document]
        );

        // Check for update success
        if ($updateResult->getModifiedCount() > 0) {
            return true; // Document updated successfully
        } else {
            return false; // Update operation failed
        }
    } else {
        // Insert the new document
        $insertResult = $collection->insertOne($document);

        // Check for insertion success
        if ($insertResult->getInsertedCount() > 0) {
            return true; // Document inserted successfully
        } else {
            return false; // Insert operation failed
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $watermarkText = isset($_POST['watermark']) ? trim($_POST['watermark']) : '';

    if (empty($watermarkText)) {
        echo "Watermark text is required.";
    } else {
        // Directory to store images
        $uploadDir = '../images/';
        setPermissions($uploadDir);

        // Process the uploaded image
        $uploadedFile = $_FILES['image'];
        $originalImagePath = $uploadDir . basename($uploadedFile['name']);

        if (move_uploaded_file($uploadedFile['tmp_name'], $originalImagePath)) {
            // Apply watermark and get the path to the watermarked image
            $watermarkedImagePath = applyWatermark($originalImagePath, $watermarkText);

            // Generate thumbnail
            $thumbnailDir = '../thumbnails/';
            setPermissions($thumbnailDir);
            $thumbnailPath = $thumbnailDir . 'thumbnail_' . basename($uploadedFile['name']);
            generateThumbnail($originalImagePath, $thumbnailPath, 200, 125);

            // Save image information to MongoDB
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

            // Check if the document already exists and update or insert accordingly
            if (updateOrCreateDocument($collection, $imageinfo)) {
                // Get additional information from MongoDB
                $imageInfo = getImageInfoFromMongoDB($imageinfo['filename']);

                // Display image information
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

// Fetch the list of watermarked images for display
$watermarkedImages = glob('../watermark/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Fetch the list of thumbnails for display
$thumbnails = glob('../thumbnails/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Fetch the list of original images for display
$originalImages = glob('../images/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Pagination configuration
$imagesPerPage = 2;
$totalImages = count($thumbnails);

// Calculate the total number of pages
$totalPages = ceil($totalImages / $imagesPerPage);

// Get the current page from the query parameter
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Validate the current page value
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Calculate the offset to fetch the images for the current page
$offset = ($currentPage - 1) * $imagesPerPage;

// Fetch a subset of thumbnails for the current page
$visibleThumbnails = array_slice($thumbnails, $offset, $imagesPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watermarked Images and Thumbnails</title>
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
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <label for="image">Choose an image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="author">Author:</label>
        <input type="text" name="author" id="author" required>

        <label for="watermark">Watermark text:</label>
        <input type="text" name="watermark" id="watermark" required>

        <button type="submit" name="submit">Upload</button>
    </form>

    <div class="gallery">
    <?php foreach ($visibleThumbnails as $thumbnail) : ?>
        <div class="thumbnail">
            <?php
            // Extract the filename from the original image path
            $originalImageFileName = str_replace('thumbnail_', '', basename($thumbnail));

            // Retrieve title and author information from MongoDB
            $imageInfo = getImageInfoFromMongoDB($originalImageFileName);

            // Build the path to the corresponding watermarked image in the watermark directory
            $watermarkedImagePath = '../watermark/' . $originalImageFileName;
            ?>
            <a href="<?php echo $watermarkedImagePath; ?>" target="_blank">
                <img src="<?php echo $thumbnail; ?>" alt="Thumbnail">
            </a>

            <p>Title: <?php echo $imageInfo['title']; ?></p>
            <p>Author: <?php echo $imageInfo['author']; ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Pagination Links -->
<div>
    <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
        <a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
    <?php endfor; ?>
</div>

<h2>Watermarked Images</h2>
<?php foreach ($watermarkedImages as $image) : ?>
    <a href="<?php echo $image; ?>" target="_blank"> <!-- Open in a new tab/window -->
        <img src="<?php echo $image; ?>" alt="Watermarked Image">
    </a>
<?php endforeach; ?>

    <h2>Thumbnailed Images</h2>
    <?php foreach ($thumbnails as $thumbnail) : ?>
        <a href="<?php echo $image; ?>" target="_blank"> <!-- Open in a new tab/window -->
            <img src="<?php echo $thumbnail; ?>" alt="Thumbnail">
        </a>
    <?php endforeach; ?>

    <h2>Original Images</h2>
    <?php foreach ($originalImages as $originalImage) : ?>
        <img src="<?php echo $originalImage; ?>" alt="Original Image">
    <?php endforeach; ?>
</body>
</html>





<div class="account">
        <?php if ($userLoggedIn): ?>
            <!-- Display when the user is logged in -->
            <a id="logout" href="../html/logout.php">LOGOUT</a>
            <a id="register" href="../html/register.html">REGISTER</a>
        <?php else: ?>
            <!-- Display when the user is not logged in -->
            <a id="login" href="../html/login.html">LOGIN</a>
            <a id="register" href="../html/register.html">REGISTER</a>
        <?php endif; ?>
    </div>