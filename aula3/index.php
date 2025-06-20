<?php

//conector PDO
$pdo = new PDO('mysql:host=localhost;dbname=Soa2025;port=3308;', 'root', '');

$sql = 'SELECT * FROM Produto WHERE registro = 1'; //não usar $_GET[]
$stm = $pdo->prepare($sql);
$stm->execute([]);
$registros = $stm->fetchAll(PDO::FETCH_OBJ);
//se remover o all do fetch retorna apenas o primeiro item do DB

foreach ($registros as $chave => $valor) {
    echo "Produto: " . $valor->descricao . '<br>';
}

/*

//inserção
$sql = 'INSERT INTO Produto (descricao, valor, estatus) VALUES (?, ?, ?)';
$stm = $pdo->prepare($sql);
$sucesso = $stm->execute(['Produto Teste', 95.00, 'ATIVO']);

if($sucesso){ //apenas o IF($VAR) faz uma verificação booleana
    echo "Produto cadastrado com sucesso! <br>";
}
else{
    echo "Erro ao cadastrar produto! <br>";
}

*/


//update
/*
$sql = 'UPDATE Produto SET registro = 0 WHERE estatus = ? AND registro = ?';
$stm = $pdo->prepare($sql);
$sucesso = $stm->execute(['INATIVO', 1]);

if($sucesso){
    echo "Produto editado com sucesso! <br>";
}
else{
    echo "Erro ao editar produto! <br>";
}
*/

/*
//delete
$sql = 'DELETE FROM Produto WHERE id = ?';
$stm = $pdo->prepare($sql);
$sucesso = $stm->execute([1]); //id do produto que será deletado

if($sucesso){
    echo "Produto deletado com sucesso! <br>";
}
else{
    echo "Erro ao deletar produto! <br>";
}
*/

//exclusão logica
$sql = 'UPDATE Produto SET registro = ? WHERE id = ?';
$stm = $pdo->prepare($sql);
$sucesso = $stm->execute([0, 4]);

if ($sucesso) {
    echo "Produto deletado com sucesso! <br>";
} else {
    echo "Erro ao deletar produto! <br>";
}
