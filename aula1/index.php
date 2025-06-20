<?php

// echo '<pre>';
// print_r($_SERVER);
// echo '</pre>';

if($_SERVER['REQUEST_METHOD']== 'GET'){
    $cod = (isset($_GET['codigo'])) ? $_GET['codigo']: '';
    $ean13 = $_GET['gtin']; 

    echo "$cod - $ean13";
    // echo json_encode([
    //     'Status' => 200, 
    //     'messege' => 'Consultado com Sucesso',
    //     'clientes' => [
    //         'nome' => 'Aaaa',
    //         'email' => '@email.com'
    //     ]
    // ]);
    // http_response_code(200);
}
if($_SERVER['REQUEST_METHOD']== 'POST'){
    echo json_encode([
        'Status' => 201, 
        'messege' => 'Gravado com Sucesso'
    ]);
    http_response_code(201);
}
if($_SERVER['REQUEST_METHOD']== 'PUT'){
    echo json_encode([
        'Status' => 202, 
        'messege' => 'Editado com Sucesso'
    ]);
    http_response_code(202);
}
if($_SERVER['REQUEST_METHOD']== 'DELETE'){
    echo json_encode([
        'Status' => 202, 
        'messege' => 'Deletado com Sucesso'
    ]);
    http_response_code(202);
}