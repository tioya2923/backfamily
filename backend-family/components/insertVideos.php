<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Incluir a biblioteca do api.video
require_once '../vendor/autoload.php';

// Certifique-se de que o método de solicitação é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

    // Verifica se algum arquivo foi enviado e se não há erros
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video = $_FILES['video'];
        $filename = mysqli_real_escape_string($conn, $video['name']);
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploadsVideos/";
        $max_size = 1024 * 1024 * 1024; // Tamanho máximo do arquivo
        $allowed_exts = array('mp4', 'mov', 'avi', 'mkv', 'webm'); // Extensões permitidas
        $size = filesize($video['tmp_name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Se o tamanho e a extensão forem válidos, move o arquivo para a pasta
        if ($size <= $max_size && in_array($ext, $allowed_exts)) {
            if (move_uploaded_file($video['tmp_name'], $target_dir . $filename)) {
                // Preparar a consulta SQL para evitar SQL Injection
                $stmt = $conn->prepare("INSERT INTO videos (nome, video, descricao) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $nome, $filename, $descricao);

                // Executar a consulta e verificar se foi bem-sucedida
                if ($stmt->execute()) {
                    // Código para enviar o vídeo para o api.video
                    // ...

                    echo "Vídeo enviado com sucesso!";
                } else {
                    echo "Ocorreu um erro ao enviar o vídeo: " . $stmt->error;
                }

                // Fechar a declaração preparada
                $stmt->close();
            } else {
                echo "Ocorreu um erro ao mover o vídeo.";
            }
        } else {
            echo "O arquivo de vídeo é inválido ou excede o tamanho máximo permitido.";
        }
    } else {
        echo "Nenhum vídeo selecionado ou erro no arquivo.";
    }
} else {
    echo "Método de solicitação inválido.";
}

// Fechar a conexão
$conn->close();
?>
