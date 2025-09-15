<?php

global $conn;
require '../conexao.php';

header('Content-Type: application/json');

$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["Message" => "Token não informado"]);
    exit;
}

list($bearer, $token) = explode(" ", $headers['Authorization']);
if ($bearer !== "Bearer" || empty($token)) {
    http_response_code(401);
    echo json_encode(["Message" => "Token inválido"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if(empty($input['titulo']) || empty($input['descricao']) || empty($input['prazo']) || empty($input['equipe']) || empty($input['prioridade']) || empty($input['status']) || empty($input['project_id'])){
    http_response_code(422);
    echo json_encode(["Message" => "Verifique e tente novamente, campos faltando"]);
    exit();
}

if ($input['equipe'] !== "Gerente de Projeto"){
    http_response_code(422);
    echo json_encode(["Message" => "Você não tem privilégio para incluir uma nova tarefa"]);
    exit();
}

$sql = "INSERT INTO tarefas (titulo, descricao, prazo, equipe, prioridade, status, projeto, responsavel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssi", $input['titulo'], $input['descricao'], $input['prazo'], $input['equipe'], $input['prioridade'], $input['status'], $input['project_id'], $input['responsavel']);

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["Message" => "Tarefa já cadastrada"]);
}

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(["Message" => "Nova tarefa registrada com sucesso"]);
    exit();
}
else {
    http_response_code(422);
    echo json_encode(["Message" => "Verifique e tente novamente, dados incorretos"]);
}


?>