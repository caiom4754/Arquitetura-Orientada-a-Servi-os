<?php

// echo '<pre>';
// print_r($_SERVER);
// echo '</pre>';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $cod = (isset($_GET['codigo'])) ? $_GET['codigo'] : '';

    try{
        
    if ($cod == '') throw new \Exception('Código não informado');

    if (!is_numeric($cod)) throw new \Exception('Código deve ser numérico');

    if (strlen($cod) != 13) throw new \Exception('Código deve ter 13 caracteres');
    
        
    echo json_encode([
        'Status' => 200,
        'messege' => 'Consultado com Sucesso: ' . $cod,
    ]);
    http_response_code(200);
    }
     catch(\Exception $erro){
        echo json_encode([
            'Status' => 200,
            'messege' => $erro->getMessage(),
        ]);
     }

    echo json_encode([
        'Status' => 200,
        'messege' => 'Consultado com Sucesso: ' . $cod,
    ]);
    http_response_code(200);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero = (isset($_POST['numero'])) ? $_POST['numero'] : '';
    $nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
    $cpf = (isset($_POST['cpf'])) ? $_POST['cpf'] : '';
    $rua = (isset($_POST['rua'])) ? $_POST['rua'] : '';

    if (!is_numeric($numero)) {
        echo json_encode([
            'status' => 406,
            'message' => 'Parâmetro $numero deve ser um numérico'
        ]);

        http_response_code(406);
        exit;
    }
    
    $cpfLimpo = str_replace(['.', '-', ' '], '', $cpf);

    if (!is_numeric($cpfLimpo)) {
        echo json_encode([
            'status' => 406,
            'message' => 'Parâmetro $numero deve ser um numérico'
        ]);

        http_response_code(406);
        exit;
    }
 
    if (strlen($cpfLimpo) != 11) {
        echo json_encode([
            'status' => 406,
            'message' => 'Parâmetro deve ter 11 caracteres'
        ]);

        http_response_code(406);
        exit;
    }

    if (strlen(trim($rua) < 20)) {
        echo json_encode([
            'status' => 406,
            'message' => 'Parâmetro deve ter 20 caracteres'
        ]);

        http_response_code(406);
        exit;
    }

    if (!strpos($nome, ' ')) {
        echo json_encode([
            'status' => 406,
            'message' => 'Insira nome e sobrenome'
        ]);

        http_response_code(406);
        exit;
    }

    if (strlen($nome < 20)) {
        echo json_encode([
            'status' => 406,
            'message' => 'Parâmetro deve ter no mínimo 20 caracteres'
        ]);

        http_response_code(406);
        exit;
    }
    echo json_encode([
        'Status: ' => 201,
        'messege: ' => 'Gravado com Sucesso: ',
        'Número: ' . $numero,
        'CPF: ' . $cpfLimpo,
        'Nome: ' . $nome,
        'Rua: ' . $rua
    ]);
    http_response_code(406);
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    echo json_encode([
        'Status' => 202,
        'messege' => 'Editado com Sucesso'
    ]);
    http_response_code(202);
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    echo json_encode([
        'Status' => 202,
        'messege' => 'Deletado com Sucesso'
    ]);
    http_response_code(202);
}
