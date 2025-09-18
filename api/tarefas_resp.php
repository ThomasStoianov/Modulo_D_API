<?php

global $conn;
require "../conexao.php";

$id_responsavel = (int)$_GET['id'];

$sqlTarefa = "SELECT * FROM tarefas WHERE responsavel = ?";
$stmtTarefa = $conn->prepare($sqlTarefa);
$stmtTarefa->bind_param("i", $id_responsavel);
$stmtTarefa->execute();

$resultTarefa = $stmtTarefa->get_result();
$resultTarefa->fetch_all(MYSQLI_ASSOC);

echo json_encode($resultTarefa, JSON_PRETTY_PRINT);



?>