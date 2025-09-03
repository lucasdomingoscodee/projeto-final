<?php
// Set the header to return a JSON response
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swssenai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Falha na conexão: ' . $conn->connect_error]);
    exit();
}

$nome_sala = $_POST['nome_sala'];
$descricao = $_POST['descricao'];
$disponivel = isset($_POST['disponivel']) ? 1 : 0;

$sql = "INSERT INTO salas (nome_sala, descricao, disponivel) VALUES ('$nome_sala', '$descricao', '$disponivel')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Sala cadastrada com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar sala: ' . $conn->error]);
}

$conn->close();
?>