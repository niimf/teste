<?php
session_start(); // Inicia a sessão

require 'C:\xampp\htdocs\baba-baby2\conn.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['nome'])) {
    $_SESSION['msgErro'] = "Necessário realizar o login para acessar a página!";
    header("Location: index.php");
    exit();
}

$data = filter_input(INPUT_POST, 'dateInput');
$turno = filter_input(INPUT_POST, 'turno');
$idBaba = filter_input(INPUT_POST, 'idBaba');
$pk_idUsuarioB = filter_input(INPUT_POST, 'pk_idUsuario');
$idUsuario = $_SESSION['idUsuario']; // Usuário logado

try {
    // Passo 1: Inserir a nova proposta na tabela 'Proposta'
    $sql_proposta = $pdo->prepare("INSERT INTO Proposta (fk_idBaba, fk_Bpk_idUsuario, fk_Ppk_idUsuario, data, turno) VALUES (:fk_idBaba, :fk_Bpk_idUsuario, :fk_Ppk_idUsuario, :data, :turno)");
    $sql_proposta->bindParam(':fk_idBaba', $idBaba, PDO::PARAM_INT);
    $sql_proposta->bindParam(':fk_Bpk_idUsuario', $pk_idUsuarioB, PDO::PARAM_INT);
    $sql_proposta->bindParam(':fk_Ppk_idUsuario', $idUsuario, PDO::PARAM_INT);
    $sql_proposta->bindParam(':data', $data, PDO::PARAM_STR);
    $sql_proposta->bindParam(':turno', $turno, PDO::PARAM_STR);

    if ($sql_proposta->execute()) {
        // Obtém o ID da proposta recém-inserida
        $idProposta = $pdo->lastInsertId();

        // Passo 2: Obter o idPais correspondente ao usuário logado
        $sql_idPais = $pdo->prepare("SELECT idPais FROM pais WHERE pk_idUsuario = :idUsuario");
        $sql_idPais->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $sql_idPais->execute();
        $result = $sql_idPais->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $idPais = $result['idPais'];

            // Passo 3: Atualizar a proposta recém-inserida com fk_idPais
            $sql_atualizar_proposta = $pdo->prepare("UPDATE Proposta SET fk_idPais = :fk_idPais WHERE idProposta = :idProposta");
            $sql_atualizar_proposta->bindParam(':fk_idPais', $idPais, PDO::PARAM_INT);
            $sql_atualizar_proposta->bindParam(':idProposta', $idProposta, PDO::PARAM_INT);
            $sql_atualizar_proposta->execute();

            $_SESSION['mensagem'] = "Proposta criada e atualizada com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao encontrar o idPais correspondente.";
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao inserir a proposta.";
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro ao processar dados: " . $e->getMessage();
}

// Redireciona de volta para menuPais.php
header("Location: ../menuPais.php");
exit();
?>
