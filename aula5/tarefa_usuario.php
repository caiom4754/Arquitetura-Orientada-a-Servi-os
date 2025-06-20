<?php
$pdo = new PDO('mysql:host=localhost;dbname=Soa2025;port=3308;', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $nome = (isset($_GET['nome'])) ? $_GET['nome'] : '';

    $sql = 'SELECT * FROM USUARIO WHERE nome = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$nome]);
    $nome = $stm->fetchAll(PDO::FETCH_OBJ);

    echo json_encode([
        'USUARIO' => $nome,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //recepção dos dados via post
    $nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
    $email = (isset($_POST['email'])) ? $_POST['email'] : '';
    $senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';

    //! verificação se já existe o e-mail no db
    $verificaEmail = $pdo->prepare('SELECT email FROM USUARIO WHERE email = ?');
    $verificaEmail->execute([$email]);

    if ($verificaEmail->rowCount() > 0) {
        echo json_encode(['mensagem' => 'E-mail já cadastrado']);
        http_response_code(409);
        exit;
    }

    //instrução sql de inserção parametrizada
    $sql = 'INSERT INTO USUARIO (nome, email, senha) VALUES (?, ?, ?)';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$nome, $email, $senha]);

    if ($sucesso) {
        echo json_encode('Usuário registrado com sucesso');
        http_response_code(406);
        exit;
    } else {
        echo json_encode('Erro ao registrar produto');
        http_response_code(406);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents('php://input') ?? '', $_PUT);

    $nome = (isset($_PUT['nome'])) ? $_PUT['nome'] : '';
    $email = (isset($_PUT['email'])) ? $_PUT['email'] : '';
    $senha = (isset($_PUT['senha'])) ? $_PUT['senha'] : '';
    $id = (isset($_PUT['id'])) ? $_PUT['id'] : '';


    $sql = "UPDATE USUARIO SET nome = ?, email = ?, senha = ? WHERE id = ?";
    $stm = $pdo->prepare($sql);
    $stm->execute([$nome, $email, $senha, $id]);

    // Verifica se alguma linha foi afetada
    if ($stm->rowCount() > 0) {
        echo json_encode(['sucesso: ' => 'Usuário atualizado com sucesso']);
        http_response_code(200);
    } else {
        echo json_encode(['mensagem: ' => 'Nenhum dado foi alterado (valores idênticos)']);
        http_response_code(200);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';

    $sql = 'DELETE FROM USUARIO WHERE id = ?';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$id]); //id do usuario que será deletado

    if ($sucesso) {
        echo "Usuario deletado com sucesso!";
        http_response_code(200);
    } else {
        echo "Erro ao deletar usuario!";
        http_response_code(500);
    }
}