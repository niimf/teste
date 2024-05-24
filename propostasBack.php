<?php
session_start();
require 'C:\xampp\htdocs\baba-baby2\conn.php';

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idBaba = $_SESSION['idUsuario'];
    $propostaId = $_POST['propostaId'];
    $estado = $_POST['estado'];
    $dataAtual = date('Y-m-d');
    $motivoRecusa = isset($_POST['motivoRecusa']) ? $_POST['motivoRecusa'] : null;

    try {
        if ($estado == 1) {
            $sql_update = $pdo->prepare("UPDATE proposta SET estado = :estado, dataAceite = :dataAtual WHERE idProposta = :propostaId AND fk_idBaba = :idBaba");
        } else {
            $sql_update = $pdo->prepare("UPDATE proposta SET estado = :estado, dataRecusa = :dataAtual, motivoRecusa = :motivoRecusa WHERE idProposta = :propostaId AND fk_idBaba = :idBaba");
            $sql_update->bindParam(':motivoRecusa', $motivoRecusa, PDO::PARAM_STR);
        }
        $sql_update->bindParam(':estado', $estado, PDO::PARAM_INT);
        $sql_update->bindParam(':dataAtual', $dataAtual, PDO::PARAM_STR);
        $sql_update->bindParam(':propostaId', $propostaId, PDO::PARAM_INT);
        $sql_update->bindParam(':idBaba', $idBaba, PDO::PARAM_INT);
        $sql_update->execute();

        if ($sql_update->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Proposta não encontrada ou sem permissão para atualizar.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar proposta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}
?>
