<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swssenai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$especialidade = $_POST['especialidade'];

// Inserir os dados no banco do instrutor
$sql = "INSERT INTO instrutores (nome, email, especialidade) 
        VALUES ('$nome', '$email', '$especialidade')";

if ($conn->query($sql) === TRUE) {
    echo "Instrutor cadastrado com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}


// Close connexao tmj
$conn->close();
?>