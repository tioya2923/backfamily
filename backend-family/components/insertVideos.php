<?php
require_once '../connect/server.php';
require_once '../connect/cors.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video = $_FILES['video'];
        $filename = mysqli_real_escape_string($conn, $video['name']);
        $target_dir = __DIR__ . '/../uploadsVideos/';
        $max_size = 1024 * 1024 * 1024;
        $allowed_exts = array('mp4', 'mov', 'avi', 'mkv', 'webm');
        $size = filesize($video['tmp_name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if ($size <= $max_size && in_array($ext, $allowed_exts)) {
            if (move_uploaded_file($video['tmp_name'], $target_dir . $filename)) {
                $stmt = $conn->prepare("INSERT INTO videos (nome, video, descricao) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $nome, $filename, $descricao);

                if ($stmt->execute()) {
                    echo "Vídeo enviado com sucesso!";
                } else {
                    echo "Ocorreu um erro ao enviar o vídeo: " . $stmt->error;
                }
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

$conn->close();
?>
