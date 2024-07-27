<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Verificar se o id foi fornecido
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No id provided']);
    exit;
}

$id = $_GET['id'];

// Preparar a consulta SQL
$stmt = $conn->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->bind_param("i", $id);

// Executar a consulta
$stmt->execute();

// Obter os resultados
$result = $stmt->get_result();

// Verificar se a foto existe
if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'video not found']);
    exit;
}

$video = $result->fetch_assoc();

// Converter para JSON
echo json_encode($video);

// Fechar a conexão
$stmt->close();
$conn->close();
?>