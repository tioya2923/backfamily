<?php
// Incluir o ficheiro de conexão
require_once '../connect/server.php';
require_once '../connect/cors.php';

// Incluir a biblioteca do api.video
require_once '../vendor/autoload.php';

$nome = mysqli_real_escape_string($conn, $_POST['nome']);
$descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

// Verifica se algum arquivo foi enviado e se não há erros
if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
  $video = $_FILES['video'];
  $filename = mysqli_real_escape_string($conn, $video['name']);
  $target_dir = "uploadsVideos/";
  $max_size = 1024 * 1024 * 1024;; 
  $allowed_exts = array('mp4', 'mov', 'avi', 'mkv', 'webm'); // extensões permitidas
  $size = filesize($video['tmp_name']);
  $ext = pathinfo($filename, PATHINFO_EXTENSION);

  // Se o tamanho e a extensão forem válidos, move o arquivo para a pasta
  if ($size <= $max_size && in_array($ext, $allowed_exts)) {
    if (move_uploaded_file($video['tmp_name'], $target_dir . $filename)) {
      $sql = "INSERT INTO videos (nome, video, descricao) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('sss', $nome, $filename, $descricao);
      $stmt->execute();

      // Código para enviar o vídeo para o api.video
      // ...

      echo "Vídeo enviado com sucesso!";
    } else {
      echo "Ocorreu um erro ao enviar o vídeo.";
    }
  } else {
    echo "O arquivo de vídeo é inválido ou excede o tamanho máximo permitido.";
  }
} else {
  // Se nenhum arquivo foi enviado ou se houve um erro, exibe a mensagem apropriada
  if (!isset($_FILES['video']) || $_FILES['video']['error'] != 0) {
    echo "Nenhum vídeo selecionado.";
  } else {
    echo "Ocorreu um erro ao enviar o vídeo.";
  }
}
?>
