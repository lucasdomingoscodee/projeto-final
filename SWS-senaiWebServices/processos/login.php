<?php
$conn = new mysqli("localhost", "root", "", "swssenai");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$usuario = $_POST['user'];
$senha   = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE user = '$usuario' AND senha = '$senha'";
$result = $conn->query($sql);

// Fecha a conexão aqui, antes dos redirecionamentos
$conn->close();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if ($row['tipo'] === 'admin') {
        header("Location: ../home.php");
        exit;
    } else {
        header("ainda nao tem");
        exit;
    }
} else {
    header("Location: ../index.php?erro=Usuário ou senha inválidos!");
    exit;
}
?>