<?php
require_once '../connect/server.php';
require_once '../connect/cors.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $filename = mysqli_real_escape_string($conn, $image['name']);
        // Usando o diretório temporário do Heroku
        $target_dir = '/tmp/';

        if (getimagesize($image['tmp_name']) !== false) {
            if (move_uploaded_file($image['tmp_name'], $target_dir . $filename)) {
                // Aqui você deveria adicionar o código para enviar a imagem para um serviço de armazenamento em nuvem
                // e salvar o URL retornado no banco de dados

                $stmt = $conn->prepare("INSERT INTO fotos (nome, foto, descricao) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nome, $filename, $descricao);

                if ($stmt->execute()) {
                    echo "Foto enviada com sucesso!";
                } else {
                    echo "Erro ao enviar a foto: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Ocorreu um erro ao mover a foto.";
            }
        } else {
            echo "O arquivo não é uma imagem válida.";
        }
    } else {
        echo "Nenhuma fotografia selecionada ou erro no arquivo.";
    }
} else {
    echo "Método de solicitação inválido.";
}

$conn->close();
?>
