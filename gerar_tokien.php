<?php

use Firebase\JWT\JWT;

require "vendor/autoload.php";

$key = "chave_secreta"; // mesma chave usada no decode

$payload = [
    "iss" => "http://localhost", // emissor
    "aud" => "http://localhost", // audiência
    "iat" => time(),             // emitido em
    "exp" => time() + 3600,      // expira em 1h
    "user_id" => 1               // qualquer dado extra que você quiser
];

$jwt = JWT::encode($payload, $key, 'HS256');

echo json_encode(["token" => $jwt]);
