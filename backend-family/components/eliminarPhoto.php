<?php
// Incluir o ficheiro de conexão
require '../vendor/autoload.php';
require_once '../connect/server.php';
require_once '../connect/cors.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$bucketName = 'familia-gouveia';
$IAM_KEY = getenv('AWS_ACCESS_KEY_ID');
$IAM_SECRET = getenv('AWS_SECRET_ACCESS_KEY');

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'credentials' => [
        'key'    => $IAM_KEY,
        'secret' => $IAM_SECRET,
    ],
]);

// Definir o tipo de conteúdo como JSON
header('Content-Type: application/json');

// Preparar a consulta SQL para evitar injeção de SQL
$stmt = $conn->prepare("SELECT * FROM fotos");

// Executar a consulta
$stmt->execute();
$result = $stmt->get_result();

$photos = [];

// Verifica se a consulta retornou resultados
if ($result->num_rows > 0) {
  // Percorre todos os resultados
  while($row = $result->fetch_assoc()) {
    $photos[] = $row;
  }
}

// Retorna os dados como JSON
echo json_encode($photos);

// Fechar a declaração preparada
$stmt->close();
?>
