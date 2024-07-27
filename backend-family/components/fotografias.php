<?php
/*
require_once '../connect/server.php';
require_once '../connect/cors.php';
$stmt = $conn->prepare("SELECT * FROM fotos ORDER BY data_hora DESC");
$stmt->execute();
$result = $stmt->get_result();
$fotos = array();
while ($row = $result->fetch_assoc()) {
  $fotos[] = $row;
}
echo json_encode($fotos);
*/




require_once '../connect/server.php';
require_once '../connect/cors.php';

// caminho para a pasta onde as fotos estão armazenadas
$target_dir = "uploadsFotos/";

// array para armazenar os nomes dos arquivos
$files = [];

// abrir o diretório e ler os arquivos
if ($dir = opendir($target_dir)) {
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            $files[] = $file;
        }
    }
    closedir($dir);
}

// buscar informações das fotos no banco de dados
$stmt = $conn->prepare("SELECT * FROM fotos ORDER BY data_hora DESC");
$stmt->execute();
$result = $stmt->get_result();
$fotos = array();

// combinar as informações do banco de dados com os nomes dos arquivos
while ($row = $result->fetch_assoc()) {
    if (in_array($row['foto'], $files)) { // verificar se o arquivo existe na pasta
        $fotos[] = $row;
    }
}

// enviar a lista combinada como JSON
echo json_encode($fotos);



?>