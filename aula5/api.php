<?php
//! no insomnia ir na aba """AUTH""" e selecionar """BASIC""" !//
$headers = getallheaders();
$cript = $headers['Authorization'];

$stringLimpa = str_replace('Basic ', '', $cript);
$dados = base64_decode($stringLimpa);

list($email, $senha) = explode(':', $dados);

// echo $email ,' - ', $senha;

$pdo = new PDO('mysql:host=localhost;dbname=Soa2025;port=3308;', 'root', '');

    $sql = 'SELECT * FROM USUARIO WHERE email = ? and senha = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$email, $senha]);
    $usuario = $stm->fetch(PDO::FETCH_OBJ);

    if(!empty($usuario)){
        echo json_encode(['mensagem' => 'O usuario '. $usuario->nome . ' já está logado']);
        http_response_code(406);
        exit;
    }
