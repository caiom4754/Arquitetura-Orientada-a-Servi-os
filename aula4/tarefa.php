<?php
$pdo = new PDO('mysql:host=localhost;dbname=Soa2025;port=3308;', 'root', '');
/*
regras de negocio:
- não permitir registrar um produto mais de uma vez no db
- não permitir registrar um produto com preço menor que 0
- não permitir registrar um produto com nome vazio
*/

if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $estatus = (isset($_GET['estatus_'])) ? $_GET['estatus_'] : '';

    $sql = 'SELECT * FROM GRUPO WHERE estatus_ = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$estatus]);
    $estatus = $stm->fetchAll(PDO::FETCH_OBJ);

    echo json_encode([
        'produtos' => $estatus
    ]);

}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //recepção dos dados via post
    $id = (isset($_POST['id_'])) ? $_POST['id_'] : '';
    $descricao = (isset($_POST['descricao_'])) ? $_POST['descricao_'] : '';
    $estatus = (isset($_POST['estatus_'])) ? $_POST['estatus_'] : '';

    //instrução sql de inserção parametrizada
    $sql = 'INSERT INTO GRUPO (id_, descricao_, estatus_) VALUES (?, ?, ?)';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$id, $descricao, $estatus]);



    if($sucesso){
        echo json_encode('Produto registrado com sucesso');
    }
    else{
        echo json_encode('Erro ao registrar produto');
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    parse_str(file_get_contents('php://input') ?? '', $_PUT);

    $id = (isset($_PUT['id_'])) ? $_PUT['id_'] : '';
    $descricao = (isset($_PUT['descricao_'])) ? $_PUT['descricao_'] : '';
    $estatus = (isset($_PUT['estatus_'])) ? $_PUT['estatus_'] : '';

    $sql = 'UPDATE GRUPO set descricao_ = ?, estatus_ = ? WHERE id_ = ?';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$descricao, $estatus, $id]);

    if($sucesso){
        echo json_encode('Produto atualizado com sucesso');
    }
    else{
        echo json_encode('Erro ao atualizar produto');
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $id = (isset($_REQUEST['id_'])) ? $_REQUEST['id_'] : '';

    $sql = 'DELETE FROM GRUPO WHERE id_ = ?';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$id]); //id do produto que será deletado

    if ($sucesso) {
        echo "Produto deletado com sucesso!";
        http_response_code(200);
    } else {
        echo "Erro ao deletar produto!";
        http_response_code(500);
    }
}