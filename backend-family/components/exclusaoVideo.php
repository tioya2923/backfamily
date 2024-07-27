<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Verifica se o ID da foto foi fornecido
if (isset($_GET['id'])) {
  // Validação de entrada
  $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
  if ($id === false) {
    echo json_encode(['status' => 'error', 'message' => 'ID da video inválido.']);
    exit;
  }

  // Consulta para eliminar a foto
  $sql = "DELETE FROM videos WHERE id = ?";

  // Prepara a declaração
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    // Erro na preparação da declaração
    error_log('Erro na preparação da declaração: ' . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao eliminar a video.']);
    exit;
  }

  // Liga o parâmetro
  $stmt->bind_param("i", $id);

  // Executa a declaração
  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Vídeo eliminada com sucesso.']);
  } else {
    // Erro na execução da declaração
    error_log('Erro na execução da declaração: ' . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao eliminar a video.']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'ID da video não fornecido.']);
}
?>
