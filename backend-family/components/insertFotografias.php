<?php
require_once '../connect/server.php';
require_once '../connect/cors.php';
$nome = mysqli_real_escape_string($conn, $_POST['nome']);
$descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
  $image = $_FILES['image'];
  $filename = mysqli_real_escape_string($conn, $image['name']);
  $target_dir = "uploadsFotos/";
  if (move_uploaded_file($image['tmp_name'], $target_dir . $filename)) {
    $sql = "INSERT INTO fotos (nome, foto, descricao) VALUES ('$nome', '$filename', '$descricao')";
    $result = mysqli_query($conn, $sql);
    echo "Foto enviada com sucesso!";
  } else {
    echo "Ocorreu um erro ao enviar a foto.";
  }
} else {
  echo "Nenhuma fotografia selecionada.";
}
?>
