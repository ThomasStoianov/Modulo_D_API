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

$stmt = $conn->prepare("INSERT INTO subTarefas (titulo, descricao, prazo, equipe, prioridade, status, project_id, id_tarefa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($subTarefas as $subTarefa) {
    $stmt->bind_param(
        'sssssssi',
        $subTarefa['titulo'],
        $subTarefa['descricao'],
        $subTarefa['prazo'],
        $subTarefa['equipe'],
        $subTarefa['prioridade'],
        $subTarefa['status'],
        $subTarefa['project_id'],
        $subTarefa['ID_tarefa']
    );
    $stmt->execute();
}



$stmt->execute();
$stmt->close();
$conn->close();

echo "Subtarefas importadas com sucesso!";

?>