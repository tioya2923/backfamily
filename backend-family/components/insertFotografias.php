<?php
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Certifique-se de que o método de solicitação é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escapar entradas para prevenir SQL Injection
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

    // Verificar se o arquivo foi enviado sem erros
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $filename = mysqli_real_escape_string($conn, $image['name']);
        $target_dir = "uploadsFotos/";

        // Verificar se o arquivo é uma imagem
        if (getimagesize($image['tmp_name']) !== false) {
            // Mover o arquivo enviado para o diretório de destino
            if (move_uploaded_file($image['tmp_name'], $target_dir . $filename)) {
                // Preparar a consulta SQL para evitar SQL Injection
                $stmt = $conn->prepare("INSERT INTO fotos (nome, foto, descricao) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nome, $filename, $descricao);

                // Executar a consulta e verificar se foi bem-sucedida
                if ($stmt->execute()) {
                    echo "Foto enviada com sucesso!";
                } else {
                    // Alteração aqui para mostrar o erro do MySQL
                    echo "Erro ao enviar a foto: " . mysqli_error($conn);
                }

                // Fechar a declaração preparada
                $stmt->close();
            } else {
                echo "Ocorreu um erro ao mover a foto. " . mysqli_error($conn);
                
            }
        } else {
            echo "O arquivo não é uma imagem válida." . mysqli_error($conn);
        }
    } else {
        echo "Nenhuma fotografia selecionada ou erro no arquivo." . mysqli_error($conn);
    }
} else {
    echo "Método de solicitação inválido." . mysqli_error($conn);
}

// Fechar a conexão
$conn->close();
?>
