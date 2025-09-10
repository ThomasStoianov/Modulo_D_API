<?php

global $conn;
require '../conexao.php';

header("content-type: application/json");

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['nome']) || empty($input['email']) || empty($input['equipe']) || empty($input['username']) || empty($input['senha'])) {
    http_response_code(422);
    echo json_encode(["Message" => "Verifique novamente, campos faltando"]);
    exit();
}

// Validar email
if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(["Message" => "Verifique o e-mail, tente novamente"]);
    exit();
}

// Ver se o usuario já exista no banco de dados
$sql = "SELECT email FROM usuarios WHERE email = ? OR username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $input['email'], $input['username']);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0) {
    http_response_code(422);
    echo json_encode(["Message" => "Usuário já cadastrado!"]);
}
else {
    $senhaHash = password_hash($input['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, equipe, username, senha) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $input['nome'], $input['email'], $input['equipe'], $input['username'], $senhaHash);
    $stmt->execute();

    echo json_encode(["Message" => "Cadastro efetuado com sucesso!"]);
}

$stmt->close();
$conn->close();

?>