<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Verifica se o ID da foto foi fornecido
if (isset($_GET['id'])) {
  // Sanitiza o ID da foto
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  // Consulta para eliminar a foto
  $sql = "DELETE FROM fotos WHERE id = ?";

  // Prepara a declaração
  $stmt = $conn->prepare($sql);

  // Liga o parâmetro
  $stmt->bind_param("i", $id);

  // Executa a declaração
  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Foto eliminada com sucesso.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao eliminar a foto.']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'ID da foto não fornecido.']);
}


// Verifica se o ID da foto foi fornecido
if (isset($_GET['id'])) {
  // Sanitiza o ID da foto
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  // Verifica se os dados da foto foram fornecidos
  if (isset($_POST['foto'])) {
    // Sanitiza os dados da foto
    $foto = filter_input(INPUT_POST, 'foto', FILTER_SANITIZE_STRING);

    // Consulta para editar a foto
    $sql = "UPDATE fotos SET foto = ? WHERE id = ?";

    // Prepara a declaração
    $stmt = $conn->prepare($sql);

    // Liga o parâmetro
    $stmt->bind_param("si", $foto, $id);

    // Executa a declaração
    if ($stmt->execute()) {
      echo json_encode(['status' => 'success', 'message' => 'Foto editada com sucesso.']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Erro ao editar a foto.']);
    }
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Dados da foto não fornecidos.']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'ID da foto não fornecido.']);
}
?>
