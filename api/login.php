<?php

global $conn;
require "../conexao.php";
require "../vendor/autoload.php";

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if(empty($input['username']) || empty($input['senha'])) {
    http_response_code(422);
    echo json_encode(["Message" => "Verifique novamente, campos faltando"]);
    exit();
}

$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $input['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(["Message" => "Login inválido, tente novamente"]);
    exit();
}

$user = $result->fetch_assoc();

if(!password_verify($input['senha'], $user['senha'])) {
    http_response_code(401);
    echo json_encode(["Message" => "Login inválido, tente novamente"]);
    exit();
}

// Gera token JWT

// Chave secreta usada para criptografar e validar o token
$secret_key = "chave_secreta";

// Quem tá criando o token
$issuer_claim = "localhost";

// quem pode usar o token
$audience_claim = "usuarios";

// Hora que foi criado
$issuedat_claim = time();

// Só é valido a partir desse horario
$notbefore_claim = $issuedat_claim;

// Expira em uma hora
$expire_claim = $issuedat_claim + 3600;

//token com todas as informações
$token = [
    "iss" => $issuer_claim,       // Emissor
    "aud" => $audience_claim,     // Destinatário
    "iat" => $issuedat_claim,     // Hora de emissão
    "nbf" => $notbefore_claim,    // Válido a partir de
    "exp" => $expire_claim,       // Expiração
    "data" => [                   // Dados do usuário que serão codificados no token
        "id" => $user['id'],      // ID do usuário
        "nome" => $user['nome'],  // Nome do usuário
        "username" => $user['username'] // Nome de usuário
    ]
];

// Codifica o array $token em uma string JWT segura, usando a chave secreta e o algoritmo HS256
$jwt = JWT::encode($token, $secret_key, 'HS256');

$update = $conn->prepare("UPDATE usuarios SET token = ? WHERE id = ?");
$update->bind_param("si", $jwt, $user['id']);
$update->execute();

// Retorna token
echo json_encode(["token" => $jwt]);

?>