<?php
header('Content-Type: application/json');
require_once '../conexao.php'; // <-- This is the corrected line

$instrutores = [];
$sql = "SELECT id_instrutores, nome FROM instrutores ORDER BY nome ASC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $instrutores[] = $row;
    }
}

echo json_encode($instrutores);
$conn->close();
?>