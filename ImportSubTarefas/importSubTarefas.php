<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "sp_skills";

$conn = mysqli_connect($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$json = file_get_contents('Incluir-Subtarefas.json');
if ($json === false) {
    die("Erro ao ler o arquivo JSON!");
}

$subTarefas = json_decode($json, true);
if ($subTarefas === null) {
    die("Erro ao decodificar JSON: " . json_last_error_msg());
}

$stmt = $conn->prepare("INSERT INTO subTarefas (tarefa_id, titulo, descricao, prazo, equipe, prioridade, status, project_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($subTarefas as $subTarefa) {
    $stmt->bind_param(
        'issssssi',
        $subTarefa['ID_tarefa'],   // aqui é o campo do JSON
        $subTarefa['titulo'],
        $subTarefa['descricao'],
        $subTarefa['prazo'],
        $subTarefa['equipe'],
        $subTarefa['prioridade'],
        $subTarefa['status'],
        $subTarefa['project_id']
    );
    $stmt->execute();
}



$stmt->execute();
$stmt->close();
$conn->close();

echo "Subtarefas importadas com sucesso!";

?>