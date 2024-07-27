<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Preparar a consulta SQL
$stmt = $conn->prepare("SELECT * FROM videos ORDER BY data_hora DESC");

// Executar a consulta
$stmt->execute();

// Obter os resultados
$result = $stmt->get_result();

$videos = array();
while ($row = $result->fetch_assoc()) {
  $videos[] = $row;
}

// Converter para JSON
echo json_encode($videos);

// Fechar a conexão
$stmt->close();
$conn->close();
?>
