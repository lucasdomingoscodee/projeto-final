<?php
header('Content-Type: application/json');
require_once '../conexao.php'; // <-- This is the corrected line

$salas = [];
$sql = "SELECT id_sala, nome_sala FROM salas ORDER BY nome_sala ASC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $salas[] = $row;
    }
}

echo json_encode($salas);
$conn->close();
?>