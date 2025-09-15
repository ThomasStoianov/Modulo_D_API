<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "../vendor/autoload.php";

global $conn;
require '../conexao.php';

$headers = getallheaders();

if(!isset($headers['Authorization'])) {
    http_response_code(422);
    echo json_encode(["Message" => "Token não informado"]);
    exit;
}

// Pega o token do header
list($bearer, $token) = explode(" ", $headers['Authorization']);
if ($bearer !== "Bearer" || empty($token)) {
    http_response_code(401);
    echo json_encode(["Message" => "Atenção, token inválido"]);
    exit;
}


$sql = "SELECT * FROM tarefas";
$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

$dados = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


?>