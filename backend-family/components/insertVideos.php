<?php
require '../vendor/autoload.php';
require_once '../connect/server.php';
require_once '../connect/cors.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// Carregar variáveis de ambiente do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$bucketName = 'familia-gouveia';



$IAM_KEY = getenv('AWS_ACCESS_KEY_ID');
$IAM_SECRET = getenv('AWS_SECRET_ACCESS_KEY');



// Configurar cliente S3
$s3 = S3Client::factory([
    'credentials' => [
        'key' => $IAM_KEY,
        'secret' => $IAM_SECRET,
    ],
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video = $_FILES['video'];
        $filename = mysqli_real_escape_string($conn, $video['name']);
        
        try {
            // Gerar chave única para o arquivo no S3
            $key = "uploads/{$filename}";
            
            // Fazer upload do vídeo para o S3
            $result = $s3->putObject([
                'Bucket' => $bucketName,
                'Key'    => $key,
                'SourceFile' => $video['tmp_name'],
                'ACL'    => 'public-read', // Definir permissões de leitura pública
                'ContentType' => 'video/mp4' // Mudar conforme o tipo do vídeo
            ]);

            // Preparar e executar a inserção no banco de dados
            $stmt = $conn->prepare("INSERT INTO videos (nome, video, descricao) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $nome, $key, $descricao);

            if ($stmt->execute()) {
                echo "Vídeo enviado com sucesso!";
            } else {
                echo "Ocorreu um erro ao enviar o vídeo: " . $stmt->error;
            }
            $stmt->close();
        } catch (S3Exception $e) {
            echo "Houve um erro ao fazer upload no S3: " . $e->getMessage();
        }
    } else {
        echo "Nenhum vídeo selecionado ou erro no arquivo.";
    }
} else {
    echo "Método de solicitação inválido.";
}

$conn->close();
?>
