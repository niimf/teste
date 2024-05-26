<?php
require 'C:\xampp\htdocs\baba-baby2\conn.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['nome'])) {
    $_SESSION['msgErro'] = "Necessário realizar o login para acessar a página!";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Propostas Baba</title>
    <link rel="shortcut icon" type="imagex/png" href="imgIndex">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="propostas.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="proposta.js" defer></script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <div class="bars">
                <i class="fa-solid fa-bars" style="color: #000000;"></i>
            </div>
            <img src="../imgIndex/Babababypng.png" alt="Logo BabáBaby" class="logo-img">
        </div>
        <div class="navbar-ola">
            <p>Olá, <?php echo $_SESSION['nome']; ?></p>
        </div>
    </nav>

    <div class="content">
        <div class="sidebar">
            <a href="../menuBaba.php" class="sidebar-nav"><i class="icon fa-solid fa-house" style="color: #000000;"></i><span>Início</span></a>
            <a href="dadosBaba.php" class="sidebar-nav"><i class="icon fa-solid fa-user" style="color: #000000;"></i><span>Dados</span></a>     
            <a href="servicosBaba.php" class="sidebar-nav active"><i class="icon fa-solid fa-clock-rotate-left" style="color: #000000;"></i><span>Serviços</span></a>        
            <a href="../index.php" class="sidebar-nav"><i class="icon fa-solid fa-right-from-bracket" style="color: #e90c0c;"></i><span>Sair</span></a>
        </div>

        <div class="wrapper">
            <div class="row">
                <div class="top-list">
                    <span class="title-content">Propostas de Serviços</span>
                </div>
                <table class="table-list">
                    <thead class="list-head">
                        <tr>
                            <th class="list-head-content">Solicitante</th>
                            <th class="list-head-content">Data</th>
                            <th class="list-head-content">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="list-body">
                    <?php
                    $idUsuario = $_SESSION['idUsuario'];

                    try {
                        // Obter o idBaba correspondente ao usuário logado
                        $sql_idBaba = $pdo->prepare("SELECT idBaba FROM baba WHERE pk_idUsuario = :idUsuario");
                        $sql_idBaba->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idBaba->execute();
                        $result = $sql_idBaba->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idBaba = $result['idBaba'];

                            // Obter as propostas para esse idBaba e incluir o nome do solicitante
                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.data, p.turno, u.nome AS nomeSolicitante, pa.descricao, u.telefone, u.email
                                FROM proposta p
                                LEFT JOIN pais pa ON p.fk_Ppk_idUsuario = pa.pk_idUsuario
                                LEFT JOIN usuario u ON pa.pk_idUsuario = u.idUsuario
                                WHERE p.fk_idBaba = :idBaba AND p.estado IS NULL
                            ");
                            $sql_proposta->bindParam(':idBaba', $idBaba, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    $dataFormatada = (new DateTime($proposta['data']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeSolicitante'] ?></td>
                                        <td class='list-body-content'><?= $dataFormatada ?></td>
                                        <td class='list-body-content'><button type='button' class="open-modal botao-visualizar">Visualizar</button></td>
                                    </tr>

                                    <!-- Modal de Detalhes da Proposta -->
                                    <div class="fade hide"></div>
                                    <div class="modal hide" id="modal-<?= $proposta['idProposta'] ?>">
                                        <div class="modal-header">
                                            <ul>
                                                <li class="nome">Solicitante: <?=$proposta['nomeSolicitante']?></li>
                                                <li>Dia: <?=$dataFormatada?></li>
                                                <li>Turno: <?=$proposta['turno']?></li>
                                            </ul>
                                            <button class="close-modal">x</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="about">
                                                <h3>Sobre a família:</h3>
                                                <p><?= htmlspecialchars($proposta['descricao']) ?></p><br>
                                            </div>
                                            <div class="contact">
                                                <br>
                                                <h3>Contato:</h3>
                                                <p>Telefone: <?= htmlspecialchars($proposta['telefone']) ?></p>
                                                <p>Email: <?= htmlspecialchars($proposta['email']) ?></p>
                                                <br>
                                            </div>
                                            <button type="button" class="btn-aceita" data-valor="1" data-proposta-id="<?= $proposta['idProposta'] ?>">Aceitar</button>
                                            <button type="button" class="btn-recusa" data-valor="0" data-proposta-id="<?= $proposta['idProposta'] ?>">Recusar</button>
                                        </div>

                                        <div class="modal-recusa hide">
                                            <h3>Motivo da Recusa</h3>
                                            <form id="form-recusa">
                                                <textarea name="motivo" rows="4" cols="50" placeholder=" Digite o motivo da recusa" id="motivo-recusa-<?= $proposta['idProposta'] ?>"></textarea><br>
                                                <input type="hidden" name="idProposta" value="<?= $proposta['idProposta'] ?>">
                                                <button type="button" class="btn-enviar-recusa" data-proposta-id="<?= $proposta['idProposta'] ?>">Enviar</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                            } else {
                                echo "<tr><td colspan='3' class='list-body-content'>Nenhuma proposta encontrada...</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='list-body-content'>Erro ao encontrar o idBaba correspondente.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='3' class='list-body-content'>Erro ao processar dados: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
