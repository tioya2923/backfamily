<?php
require '../vendor/autoload.php';
require_once '../connect/server.php';
require_once '../connect/cors.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$bucketName = 'familia-gouveia';
$IAM_KEY = getenv('AWS_IAM_KEY');
$IAM_SECRET = getenv('AWS_IAM_SECRET');

$s3 = S3Client::factory([
    'credentials' => [
        'key' => $IAM_KEY,
        'secret' => $IAM_SECRET,
    ],
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$stmt = $conn->prepare("SELECT * FROM fotos ORDER BY data_hora DESC");
$stmt->execute();
$result = $stmt->get_result();
$fotos = array();

while ($row = $result->fetch_assoc()) {
    $row['foto'] = $s3->getObjectUrl($bucketName, $row['foto']);
    $fotos[] = $row;
}

echo json_encode($fotos);
?>

