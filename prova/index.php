<?php
$pdo = new PDO('mysql:host=localhost;dbname=Soa2025;port=3308;', 'root', '');

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

    $uf = (isset($_GET['uf'])) ? $_GET['uf'] : '';
    $seguimento = (isset($_GET['seguimento'])) ? $_GET['seguimento'] : '';

    if (empty($seguimento) && empty($uf)) {
        echo json_encode(['mensagem' => 'Informe um seguimento ou uma UF para a busca.']);
        http_response_code(400);
        exit;
    }

    // Se buscar apenas por seguimento
    if (!empty($seguimento)) {
        $sql = 'SELECT RESTAURANTE.id, RESTAURANTE.nome, RESTAURANTE.uf 
                FROM RESTAURANTE 
                JOIN SEGUIMENTO ON RESTAURANTE.seguimento_id = SEGUIMENTO.id
                WHERE SEGUIMENTO.seguimento = ?';
        $params = [$seguimento];
    } 
    // Se buscar apenas por UF
    elseif (!empty($uf)) {
        $sql = 'SELECT id, nome, uf FROM RESTAURANTE WHERE uf = ?';
        $params = [$uf];
    }

    $stm = $pdo->prepare($sql);
    $stm->execute($params);
    $restaurantes = $stm->fetchAll(PDO::FETCH_OBJ);

    echo json_encode([
        'RESTAURANTES' => $restaurantes
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
    $nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
    $id_seguimento = (isset($_POST['seguimento_id'])) ? $_POST['seguimento_id'] : '';
    $uf = (isset($_POST['uf'])) ? $_POST['uf'] : '';
    $seguimento = (isset($_POST['seguimento'])) ? $_POST['seguimento'] : '';
    
    
    //VALIDAÇÃO
    if ($nome == '' || $seguimento == '' || $uf == '') { //*ESSA BUCETA SÓ ACEITA COM . NO FINAL DO POST 10. 10.0 E BLABLABLA
        echo json_encode(['mensagem' => 'Valor inválido']);
        http_response_code(406);
        exit;
    }


    //VERIFICAÇÃO EM NIVEL DE BANCO DE DADOS
    $sql = 'SELECT * FROM RESTAURANTE WHERE nome = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$nome]);
    $restaurante = $stm->fetchAll(PDO::FETCH_OBJ);

    if (!empty($restaurante)) {
        echo json_encode(['mensagem' => 'RESTAURANTE já existe']);
        http_response_code(406);
        exit;
    }

    //VERIFICAÇÃO DA EXISTENCIA GRUPO
    $sql = 'SELECT id FROM SEGUIMENTO WHERE seguimento = ?';
    $stm = $pdo->prepare($sql);
    $stm->execute([$seguimento]);
    $nome_seguimento = $stm->fetchAll(PDO::FETCH_OBJ);

    $idGrupo = 0;
    if (!empty($nome_seguimento)) {
        $idGrupo = $nome_seguimento[0]->id;
    } else {
        $sql = 'INSERT INTO SEGUIMENTO (seguimento) VALUES (?)';
        $stm = $pdo->prepare($sql);
        $stm->execute([$seguimento]);

        $sql = 'SELECT id FROM SEGUIMENTO WHERE seguimento = ?';
        $stm = $pdo->prepare($sql);
        $stm->execute([$seguimento]);
        $nome_seguimento = $stm->fetchAll(PDO::FETCH_OBJ);

        $idGrupo = $nome_seguimento[0]->id;
    }

    //INSTRUÇÃO SQL DE INSERÇÃO
    $sql = 'INSERT INTO RESTAURANTE (seguimento_id, nome, uf) VALUES (?,?,?)';
    $stm = $pdo->prepare($sql);
    $sucesso = $stm->execute([$idGrupo,$nome, $uf]);

    if ($sucesso) {
        echo json_encode([ 'mensagem' =>'RESTAURANTE Cadastrado com SUCESSO']);
        http_response_code(201);
    } else {
        echo json_encode('Erro ao cadastrar produto');
        http_response_code(500);
    };
};
