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

$nome = $_POST['nome'];
$email = $_POST['email'];
$especialidade = $_POST['especialidade'];

$sql = "INSERT INTO instrutores (nome, email, especialidade) VALUES ('$nome', '$email', '$especialidade')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Instrutor cadastrado com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar: ' . $conn->error]);
}

$conn->close();
?>