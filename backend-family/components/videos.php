<?php
require '../vendor/autoload.php';
require_once '../connect/server.php';
require_once '../connect/cors.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


$bucketName = 'familia-gouveia';
$IAM_KEY = getenv('AWS_IAM_KEY');
$IAM_SECRET = getenv('AWS_IAM_SECRET');

// Configurar cliente S3
$s3 = S3Client::factory([
    'credentials' => [
        'key' => $IAM_KEY,
        'secret' => $IAM_SECRET,
    ],
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

// Preparar a consulta SQL para selecionar vídeos
$stmt = $conn->prepare("SELECT * FROM videos ORDER BY data_hora DESC");
$stmt->execute();
$result = $stmt->get_result();
$videos = array();

// Obter os URLs dos vídeos do S3 e adicionar ao array
while ($row = $result->fetch_assoc()) {
    $row['video'] = $s3->getObjectUrl($bucketName, $row['video']);
    $videos[] = $row;
}

// Converter para JSON e exibir
echo json_encode($videos);

// Fechar a conexão
$stmt->close();
$conn->close();
?>
