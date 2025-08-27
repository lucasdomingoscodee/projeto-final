<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swssenai";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$data = $_POST['data'];
$materia = $_POST['materia'];
$periodo = $_POST['periodo'];
$id_sala = $_POST['id_sala'];
$id_instrutores =  $_POST['id_instrutores'];


$sql = "INSERT INTO aulas (data, materia, periodo, id_sala, id_instrutores)
        VALUES ('$data', '$materia', '$periodo', '$id_sala', '$id_instrutores')";

// Executar a query e verificar se foi bem-sucedida
if ($conn->query($sql) === TRUE) {
    echo "Aula cadastrada com sucesso!";
} else {
    echo "Erro ao cadastrar aula: " . $conn->error;
}

// Fechar a conexão
$conn->close();
?>