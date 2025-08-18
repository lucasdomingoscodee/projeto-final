<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swssenai";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
// Inserir os dados no banco da sala
$numero = $_POST['numero'];
$capacidade = $_POST['capacidade'];
$tipo = $_POST['tipo'];
$descricao = $_POST['descricao'];
$disponivel = isset($_POST['disponivel']) ? 1 : 0;

$sql = "INSERT INTO salas (numero, capacidade, tipo, descricao, disponivel)
        VALUES ('$numero', '$capacidade', '$tipo', '$descricao', '$disponivel')";

if ($conn->query($sql) === TRUE) {
    echo "Sala cadastrada com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}


// Close connexao tmj
$conn->close();
?>