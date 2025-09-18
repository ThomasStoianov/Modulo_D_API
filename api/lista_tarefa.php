<?php

global $conn;
require '../conexao.php';

if(!isset($_GET['id'])) {
    http_response_code(422);
    echo json_encode(["Message" => "ID da tarefa não informado"]);
    exit;
}

$tarefa_id = (int)$_GET['id'];

// --- Consulta a tarefa ---
$sql = "SELECT * FROM tarefas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tarefa_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["Message" => "Tarefa não encontrada"]);
    exit;
}

$tarefa = $result->fetch_assoc();

// Retorna JSON
echo json_encode($tarefa, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();

?>