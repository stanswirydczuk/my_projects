<?php
require '/var/www/prod/vendor/autoload.php'; 
use MongoDB\Client;

function getImageInfoFromMongoDB($filename)
{
    $client = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]
    );

    $collection = $client->wai->imageinfo;

    $imageInfo = $collection->findOne(['filename' => $filename]);

    return $imageInfo;
}
?>