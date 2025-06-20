<?php
/* 
!LL       EEEEEE   II   AAAAA      OS COMENTARIOS
!LL       EE           AA   AA         
!LL       EEEE     II  AAAAAAA     TEM UMAS DICAS
!LL       EE       II  AA   AA     AQUI*    
!LLLLLLL  EEEEEE   II  AA   AA
*/

$pdo = new PDO('mysql:host=localhost;dbname=Soa2025;port=3308;', 'root', '');

//^ dica prova: comecar fazendo deste de conexão com o banco de dados

/*
insert = 'post'
update = 'put'
delete = 'delete'
select = 'get'
*/

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $headers = getallheaders();
    $cript = $headers['Authorization'];

    $stringLimpa = str_replace('Basic ', '', $cript);
    $dados = base64_decode($stringLimpa);

    list($email, $senha) = explode(':', $dados);

    $sql = 'SELECT * FROM USUARIO WHERE email = ? and senha = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$email, $senha]);
    $usuario = $stm->fetch(PDO::FETCH_OBJ);

    if (empty($usuario)) {
        echo json_encode(['mensagem' => 'O usuario não encontrado']);
        http_response_code(404);
        exit;
    }
    if ($usuario->consulta != 1) {
        echo json_encode(['mensagem' => 'O usuario não tem permissão para inserir produtos']);
        http_response_code(403);
        exit;
    }

    $registro = (isset($_GET['registro'])) ? $_GET['registro'] : '';
    $privilegio = (isset($_GET['privilegio'])) ? $_GET['privilegio'] : '';

    if ($privilegio = 'admin') {
        echo json_encode('Acesso Permitido');
    } else {
        echo json_encode('Acesso Negado');
    }

    $sql = 'SELECT * FROM Produto WHERE registro = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$registro]);
    $registro = $stm->fetchAll(PDO::FETCH_OBJ);

    echo json_encode([
        'produtos' => $registro
    ]);
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $headers = getallheaders();
    $cript = $headers['Authorization'];

    $stringLimpa = str_replace('Basic ', '', $cript);
    $dados = base64_decode($stringLimpa);

    list($email, $senha) = explode(':', $dados);

    $sql = 'SELECT * FROM USUARIO WHERE email = ? and senha = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$email, $senha]);
    $usuario = $stm->fetch(PDO::FETCH_OBJ);

    if (empty($usuario)) {
        echo json_encode(['mensagem' => 'O usuario não encontrado']);
        http_response_code(404);
        exit;
    }
    if ($usuario->insert != 1) {
        echo json_encode(['mensagem' => 'O usuario não tem permissão para inserir produtos']);
        http_response_code(403);
        exit;
    }


    //*POST PASSA PELO 'BODY - FORM DATA' NO INSOMNIA
    //RECEPÇÃO DOS DADOS VIA POST
    $descricao = (isset($_POST['descricao'])) ? $_POST['descricao'] : '';
    $valor = (isset($_POST['valor'])) ? $_POST['valor'] : '';
    $estatus = (isset($_POST['estatus'])) ? $_POST['estatus'] : '';
    $registro = (isset($_POST['registro'])) ? $_POST['registro'] : '';
    $grupo = (isset($_POST['grupo'])) ? $_POST['grupo'] : '';

    //VALIDAÇÃO
    if (!is_float($valor + 0) || $valor <= 0) { //*ESSA BUCETA SÓ ACEITA COM . NO FINAL DO POST 10. 10.0 E BLABLABLA
        echo json_encode(['mensagem' => 'Valor inválido']);
        http_response_code(406);
        exit;
    }

    if (strlen($descricao) < 3) {
        echo json_encode(['mensagem' => 'Descrição inválida']);
        http_response_code(406);
        exit;
    }

    //VERIFICAÇÃO EM NIVEL DE BANCO DE DADOS
    $sql = 'SELECT * FROM Produto WHERE descricao = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$descricao]);
    $produto = $stm->fetchAll(PDO::FETCH_OBJ);

    if (!empty($produto)) {
        echo json_encode(['mensagem' => 'Descrição já existe']);
        http_response_code(406);
        exit;
    }

    //VERIFICAÇÃO DA EXISTENCIA GRUPO
    $sql = 'SELECT id_ FROM GRUPO WHERE descricao_ = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$grupo]);
    $dadosGrupo = $stm->fetchAll(PDO::FETCH_OBJ);

    $idGrupo = 0;
    if (!empty($dadosGrupo)) {
        $idGrupo = $dadosGrupo[0]->id_;
    } else {
        $sql = 'INSERT INTO GRUPO (descricao_, estatus_) VALUES (?, ?)';
        $stm = $pdo->prepare($sql);
        $stm->execute([$grupo, 'ATIVO']);

        $sql = 'SELECT id_ FROM GRUPO WHERE descricao_ = ?';
        $stm = $pdo->prepare($sql);
        $stm->execute([$grupo]);
        $dadosGrupo = $stm->fetchAll(PDO::FETCH_OBJ);

        $idGrupo = $dadosGrupo[0]->id_;
    }

    //INSTRUÇÃO SQL DE INSERÇÃO
    $sql = 'INSERT INTO Produto (descricao, valor, registro, estatus, grupo_id) VALUES (?, ?, ?, ?, ?)';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$descricao, $valor, $registro, $estatus, $idGrupo]);

    if ($sucesso) {
        echo json_encode(['Produto Cadastrado com SUCESSO']);
        http_response_code(201);
    } else {
        echo json_encode('Erro ao cadastrar produto');
        http_response_code(500);
    };
};

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $headers = getallheaders();
    $cript = $headers['Authorization'];

    $stringLimpa = str_replace('Basic ', '', $cript);
    $dados = base64_decode($stringLimpa);

    list($email, $senha) = explode(':', $dados);

    $sql = 'SELECT * FROM USUARIO WHERE email = ? and senha = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$email, $senha]);
    $usuario = $stm->fetch(PDO::FETCH_OBJ);

    if (empty($usuario)) {
        echo json_encode(['mensagem' => 'O usuario não encontrado']);
        http_response_code(404);
        exit;
    }
    if ($usuario->update != 1) {
        echo json_encode(['mensagem' => 'O usuario não tem permissão para inserir produtos']);
        http_response_code(403);
        exit;
    }

    //PUT PASSA PELO 'BODY - FORM URL ENCODED' NO INSOMNIA

    parse_str(file_get_contents('php://input') ?? '', $_PUT);

    $descricao = (isset($_PUT['descricao'])) ? $_PUT['descricao'] : '';
    $valor = (isset($_PUT['valor'])) ? $_PUT['valor'] : '';
    $estatus = (isset($_PUT['estatus'])) ? $_PUT['estatus'] : '';
    $id = (isset($_PUT['id'])) ? $_PUT['id'] : '';

    $sql = 'UPDATE produto SET descricao = ?, valor = ?, estatus = ? WHERE id = ?';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$descricao, $valor, $estatus, $id]);

    if ($sucesso) {
        echo json_encode('Produto Atualizado com SUCESSO');
        http_response_code(201);
    } else {
        echo json_encode('Erro ao atualizar produto');
        http_response_code(500);
    };
};

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $headers = getallheaders();
    $cript = $headers['Authorization'];

    $stringLimpa = str_replace('Basic ', '', $cript);
    $dados = base64_decode($stringLimpa);

    list($email, $senha) = explode(':', $dados);

    $sql = 'SELECT * FROM USUARIO WHERE email = ? and senha = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$email, $senha]);
    $usuario = $stm->fetch(PDO::FETCH_OBJ);

    if (empty($usuario)) {
        echo json_encode(['mensagem' => 'O usuario não encontrado']);
        http_response_code(404);
        exit;
    }
    if ($usuario->delete != 1) {
        echo json_encode(['mensagem' => 'O usuario não tem permissão para inserir produtos']);
        http_response_code(403);
        exit;
    }



    $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';

    $sql = 'DELETE FROM Produto WHERE id = ?';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$id]); //id do produto que será deletado

    if ($sucesso) {
        echo "Produto deletado com sucesso!";
        http_response_code(200);
    } else {
        echo "Erro ao deletar produto!";
        http_response_code(500);
    }
};
