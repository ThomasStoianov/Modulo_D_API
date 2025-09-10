<?php


$host = "localhost";
$user = "root";
$password = "";
$database = "sp_skills";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Ler o arquivo TXT (que contém JSON)
$json = file_get_contents('novasTarefas.txt');
$tarefas = json_decode($json, true);

if (!$tarefas) {
    die("Erro ao decodificar JSON: " . json_last_error_msg());
}

// Preparar INSERT
$stmt = $conn->prepare("
    INSERT INTO tarefas 
    (titulo, descricao, prazo, equipe, prioridade, status, project_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

foreach ($tarefas as $tarefa) {
    $stmt->bind_param(
        'ssssssi',
        $tarefa['titulo'],
        $tarefa['descricao'],
        $tarefa['prazo'],
        $tarefa['equipe'],
        $tarefa['prioridade'],
        $tarefa['status'],
        $tarefa['projeto_id']
    );
    $stmt->execute();
}


echo "Tarefas importadas com sucesso!";

$stmt->close();
$conn->close();


?>