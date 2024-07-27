<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

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
