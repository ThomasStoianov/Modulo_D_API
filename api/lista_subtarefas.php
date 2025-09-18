<?php

global $conn;
require "../conexao.php";

$subtarefa_id = (int)$_GET['id'];

$sql = "SELECT * FROM subtarefas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $subtarefa_id);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["Message" => "Subtarefa não encontrada"]);
    exit();
}
else {
    $subtarefas = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($subtarefas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

?>