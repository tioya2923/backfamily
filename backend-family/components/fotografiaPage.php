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

// Verificar se o id foi fornecido
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No id provided']);
    exit;
}

$id = $_GET['id'];

// Preparar a consulta SQL
$stmt = $conn->prepare("SELECT * FROM fotos WHERE id = ?");
$stmt->bind_param("i", $id);

// Executar a consulta
$stmt->execute();

// Obter o resultado
$result = $stmt->get_result();

// Verificar se a foto existe
if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Image not found']);
    exit;
}

$image = $result->fetch_assoc();

// Obter o URL do objeto S3
$image['foto'] = $s3->getObjectUrl($bucketName, $image['foto']);

// Converter para JSON
echo json_encode($image);

// Fechar a conexão
$stmt->close();
$conn->close();
?>