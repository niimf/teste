<?php
require 'C:\xampp\htdocs\baba-baby2\conn.php';

if (isset($_POST['idProposta'])) {
    $idProposta = $_POST['idProposta'];
    $dataAtual = date('Y-m-d H:i:s');

    try {
        $sql = "UPDATE proposta SET dataPronto = :dataPronto WHERE idProposta = :idProposta";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dataPronto', $dataAtual, PDO::PARAM_STR);
        $stmt->bindParam(':idProposta', $idProposta, PDO::PARAM_INT);

        if ($stmt->execute()) {
            http_response_code(200);
            echo 'Proposta atualizada com sucesso';
        } else {
            http_response_code(500);
            echo 'Erro ao atualizar a proposta';
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'Erro: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'ID da proposta não fornecido';
}
?>
