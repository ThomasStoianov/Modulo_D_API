<?php

$host = "localhost";
$user = "root";
$password = '';
$database = "sp_skills";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$json = file_get_contents('Incluir-Tarefas.json');
$tarefas = json_decode($json, true);

$stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, prazo, equipe, prioridade, status, projeto, responsavel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($tarefas as $tarefa) {
    $stmt->bind_param(
        'sssssssi',
        $tarefa['titulo'],
        $tarefa['descricao'],
        $tarefa['prazo'],
        $tarefa['equipe'],
        $tarefa['prioridade'],
        $tarefa['status'],
        $tarefa['project_id'],
        $tarefa['responsavel']
    );

    $stmt->execute();
    echo "Tarefa inserida com sucesso!";
}

$stmt->close();
$conn->close();

echo "Importado com sucesso!";

?>