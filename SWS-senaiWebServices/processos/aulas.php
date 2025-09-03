<?php
// Set the header to return a JSON response
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swssenai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Falha na conexão: ' . $conn->connect_error]));
}

$data = $_POST['data'];
$materia = $_POST['materia'];
$periodo = $_POST['periodo'];
$id_sala = $_POST['id_sala'];
$id_instrutores = $_POST['id_instrutores'];

$sql = "INSERT INTO aulas (data, materia, periodo, id_sala, id_instrutores) VALUES ('$data', '$materia', '$periodo', '$id_sala', '$id_instrutores')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Aula cadastrada com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar aula: ' . $conn->error]);
}

$conn->close();
?>