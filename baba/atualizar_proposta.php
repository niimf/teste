<?php
require 'C:\xampp\htdocs\baba-baby2\conn.php';

session_start();

if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['nome'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit();
}

if (isset($_POST['idProposta']) && isset($_POST['estado'])) {
    $idProposta = $_POST['idProposta'];
    $estado = $_POST['estado'];
    $dataAtual = date('Y-m-d H:i:s');
    $motivoRecusa = isset($_POST['motivoRecusa']) ? $_POST['motivoRecusa'] : null;

    try {
        $sql = "UPDATE proposta SET estado = :estado, dataAceite = :dataAceite WHERE idProposta = :idProposta";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':dataAceite', $dataAtual, PDO::PARAM_STR);
        $stmt->bindParam(':idProposta', $idProposta, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a proposta']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados insuficientes']);
}
?>
