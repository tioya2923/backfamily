<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Consulta para buscar todas as fotos usando prepared statements
$stmt = $conn->prepare("SELECT * FROM videos");
$stmt->execute();

$result = $stmt->get_result();
$videos = [];

// Verifica se a consulta retornou resultados
if ($result->num_rows > 0) {
  // Percorre todos os resultados
  while($row = $result->fetch_assoc()) {
    // Escapar a saída para segurança
    array_walk_recursive($row, function(&$item) {
      $item = htmlspecialchars($item);
    });
    $videos[] = $row;
  }
} 

// Retorna os dados como JSON
header('Content-Type: application/json');
echo json_encode($videos);
?>
