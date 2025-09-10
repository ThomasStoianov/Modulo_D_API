<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "sp_skills";

$conn = mysqli_connect($host, $user, $password, $database);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$json = file_get_contents('Incluir_usuarios1.json');
$usuarios = json_decode($json, true);

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, equipe, username, senha) VALUES (?, ?, ?, ?, ?)");

foreach($usuarios as $usuario){
    $senhaHash = password_hash($usuario['senha'], PASSWORD_DEFAULT);

    $stmt->bind_param(
        'sssss',
        $usuario['nome'],
        $usuario['email'],
        $usuario['equipe'],
        $usuario['username'],
        $senhaHash
    );

    $stmt->execute();
}

echo  "Importado com sucesso";

$stmt->close();
$conn->close();

?>