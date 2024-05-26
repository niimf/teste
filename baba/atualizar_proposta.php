<?php
require 'C:\xampp\htdocs\baba-baby2\conn.php';

// Verifica se os dados necessários foram enviados pelo método POST
if (isset($_POST['idProposta']) && isset($_POST['estado'])) {
    $idProposta = $_POST['idProposta'];
    $estado = $_POST['estado'];

    try {
        // Verifica se a proposta está sendo aceita ou recusada
        if ($estado == 1) {
            $dataAceite = $_POST['dataAceite'];
            $sql = "UPDATE Proposta SET estado = 1, dataAceite = ? WHERE idProposta = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$dataAceite, $idProposta]);
        } else {
            if (isset($_POST['dataRecusa']) && isset($_POST['motivoRecusa'])) {
                $dataRecusa = $_POST['dataRecusa'];
                $motivoRecusa = $_POST['motivoRecusa'];
                $sql = "UPDATE Proposta SET estado = 0, dataRecusa = ?, motivoRecusa = ? WHERE idProposta = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(1, $dataRecusa, PDO::PARAM_STR);
                $stmt->bindParam(2, $motivoRecusa, PDO::PARAM_STR);
                $stmt->bindParam(3, $idProposta, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                echo "Dados incompletos para recusar a proposta.";
                exit;
            }
        }

        // Verifica se a consulta foi bem-sucedida
        if ($stmt->rowCount() > 0) {
            echo "success";
        } else {
            echo "Erro ao atualizar a proposta.";
        }
    } catch (PDOException $e) {
        echo "Erro ao processar dados: " . $e->getMessage();
    }
} else {
    echo "Dados incompletos.";
}
?>
