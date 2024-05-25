<?php
require 'C:\xampp\htdocs\baba-baby2\conn.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idProposta'], $_POST['estado'])) {
        $idProposta = intval($_POST['idProposta']);
        $estado = intval($_POST['estado']);
        $motivoRecusa = isset($_POST['motivoRecusa']) ? trim($_POST['motivoRecusa']) : null;

        try {
            if ($estado === 1) {
                // Atualizar a proposta para aceita e definir a dataAceite
                $stmt = $pdo->prepare("UPDATE proposta SET estado = 1, dataAceite = NOW() WHERE idProposta = :idProposta");
                $stmt->bindParam(':idProposta', $idProposta, PDO::PARAM_INT);
                $stmt->execute();
                $response['success'] = true;
                $response['message'] = 'Proposta aceita com sucesso!';
            } else {
                // Atualizar a proposta para recusada e definir a dataRecusa
                $stmt = $pdo->prepare("UPDATE proposta SET estado = 0, motivoRecusa = :motivoRecusa, dataRecusa = NOW() WHERE idProposta = :idProposta");
                $stmt->bindParam(':idProposta', $idProposta, PDO::PARAM_INT);
                $stmt->bindParam(':motivoRecusa', $motivoRecusa, PDO::PARAM_STR);
                $stmt->execute();
                $response['success'] = true;
                $response['message'] = 'Proposta recusada com sucesso!';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao atualizar a proposta: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Dados da proposta incompletos.';
    }
} else {
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>
