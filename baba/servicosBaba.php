<?php
include_once 'C:\xampp\htdocs\baba-baby2\conn.php';


if ((!isset($_SESSION['idUsuario'])) AND (!isset($_SESSION['nome']))) {
    $_SESSION['msgErro'] = "Necessário realizar o login para acessar a página!";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Serviços Babá</title>
        <link rel="shortcut icon" type="imagex/png" href="../imgIndex/bbbyynew.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="propostas.css">
     </head>
    <body>
        <!-- Início Navbar -->
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
        <!-- Fim Navbar -->

        <!-- Início Conteúdo -->
        <div class="content">
            <!-- Início Sidebar -->
            <div class="sidebar">
                <a href="../menuBaba.php" class="sidebar-nav"><i class="icon fa-solid
                    fa-house" style="color: #000000;"></i><span>Início</span></a>
                <a href="dadosBaba.php" class="sidebar-nav"><i class="icon fa-solid 
                    fa-user" style="color: #000000;"></i><span>Dados</span></a>     
                <a href="servicosBaba.php" class="sidebar-nav active"><i class="icon fa-solid 
                    fa-clock-rotate-left" style="color: #000000;"></i><span>Serviços</span></a>           
                <a href="login/sair.php" class="sidebar-nav"><i class="icon fa-solid 
                    fa-right-from-bracket" style="color: #e90c0c;"></i><span>Sair</span></a> 
            </div>
            
            <!-- Início do conteúdo do administrativo -->
            <div class="wrapper">
            <div class="row">
                <div class="top-list">
                    <span class="title-content">Serviços ativos</span>
                </div>
                <table class="table-list">
                    <thead class="list-head">
                        <tr>
                            <th class="list-head-content">Solicitante</th>
                            <th class="list-head-content">Data</th>
                            <th class="list-head-content">Ações</th>
                            <th class="list-head-content">Status</th>
                        </tr>
                    </thead>
                    <tbody class="list-body">
                    <?php
                    $idUsuario = $_SESSION['idUsuario'];

                    try {
                        $sql_idBaba = $pdo->prepare("SELECT idBaba FROM baba WHERE pk_idUsuario = :idUsuario");
                        $sql_idBaba->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idBaba->execute();
                        $result = $sql_idBaba->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idBaba = $result['idBaba'];

                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.fk_idBaba, p.fk_Ppk_idUsuario, p.data, p.turno, u.nome AS nomeSolicitante, pa.descricao, u.telefone, u.email, p.dataAceite
                                FROM proposta p
                                LEFT JOIN pais pa ON p.fk_Ppk_idUsuario = pa.pk_idUsuario
                                LEFT JOIN usuario u ON pa.pk_idUsuario = u.idUsuario
                                WHERE p.fk_idBaba = :idBaba AND p.estado = 1
                            ");
                            $sql_proposta->bindParam(':idBaba', $idBaba, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    $dataFormatada = (new DateTime($proposta['data']))->format('d/m/Y');
                                    $dataAFormatada = (new DateTime($proposta['dataAceite']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeSolicitante'] ?></td>
                                        <td class='list-body-content'><?= $dataFormatada ?></td>
                                        <td class='list-body-content'><button type='button' class="open-modal botao-visualizar">Visualizar</button></td>
                                        <td class='list-body-content'>Em processo</td>
                                    </tr>
                                    <!-- Modal de Detalhes da Proposta -->
                                    <div class="fade hide"></div>
                                    <div class="modal hide">
                                        <!-- Cabeçalho do Modal -->
                                        <div class="modal-header">
                                            <ul>
                                                <li class="nome">Solicitante: <?=$proposta['nomeSolicitante']?></li>
                                                <li>Dia: <?=$dataFormatada?></li>
                                                <li>Turno: <?=$proposta['turno']?></li>
                                            </ul>
                                            <button class="close-modal">x</button>
                                        </div>
                                        <!-- Corpo do Modal -->
                                        <div class="modal-body"> 
                                            <div class="about">
                                                <h3>Sobre a família:</h3>
                                                <p><?=$proposta['descricao']?></p><br>
                                            </div>
                                            <div class="contact">
                                                <br>
                                                <h3>Contato:</h3>
                                                <p>Telefone: <?=$proposta['telefone']?></p>
                                                <p>Email: <?=$proposta['email']?></p>   
                                                <br>
                                            </div>
                                            <div class="data-aceite">
                                                <br>
                                                <h3>Data de Aceite:</h3>
                                                <p><?=$dataAFormatada?></p> 
                                                <br>
                                            </div>
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
                <div class="down-list">
                    <span class="title-content"><br></br>Serviços Realizados</span>
                </div>
                <table class="table-list">
                    <thead class="list-head">
                        <tr>
                            <th class="list-head-content">Solicitante</th>
                            <th class="list-head-content">Data Finalizada</th>
                            <th class="list-head-content">Status</th>
                        </tr>
                    </thead>
                    <tbody class="list-body">
                    <?php
                    $idUsuario = $_SESSION['idUsuario'];

                    try {
                        $sql_idBaba = $pdo->prepare("SELECT idBaba FROM baba WHERE pk_idUsuario = :idUsuario");
                        $sql_idBaba->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idBaba->execute();
                        $result = $sql_idBaba->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idBaba = $result['idBaba'];

                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.fk_idBaba, p.fk_Ppk_idUsuario, u.nome AS nomeSolicitante, p.dataPronto
                            FROM proposta p
                            LEFT JOIN pais pa ON p.fk_Ppk_idUsuario = pa.pk_idUsuario
                            LEFT JOIN usuario u ON pa.pk_idUsuario = u.idUsuario
                            WHERE p.fk_idBaba = :idBaba AND p.dataPronto IS NOT NULL AND p.dataPronto != ''  
                            ");
                            $sql_proposta->bindParam(':idBaba', $idBaba, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    $dataFormatada = (new DateTime($proposta['dataPronto']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeSolicitante'] ?></td>
                                        <td class='list-body-content'><?= $dataFormatada ?></td>
                                        <td class='list-body-content'>Finalizado</td>
                                    </tr>
                                <?php
                                endforeach;
                            } else {
                                echo "<tr><td colspan='3' class='list-body-content'>Nenhuma proposta encontrada...</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='list-body-content'>Erro (2) ao encontrar o idBaba correspondente.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='3' class='list-body-content'>Erro (2) ao processar dados: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const openModalButtons = document.querySelectorAll(".open-modal");
            const closeModalButtons = document.querySelectorAll(".close-modal");
            const fades = document.querySelectorAll(".fade");
            const modals = document.querySelectorAll(".modal");
            const aceitaButtons = document.querySelectorAll(".btn-aceita");
            const recusaButtons = document.querySelectorAll(".btn-recusa");
            const enviarRecusaButtons = document.querySelectorAll(".btn-enviar-recusa");

            openModalButtons.forEach((button, index) => {
                button.addEventListener("click", () => {
                    modals[index].classList.remove("hide");
                    fades[index].classList.remove("hide");
                });
            });

            closeModalButtons.forEach((button, index) => {
                button.addEventListener("click", () => {
                    modals[index].classList.add("hide");
                    fades[index].classList.add("hide");
                });
            });

            fades.forEach((fade, index) => {
                fade.addEventListener("click", () => {
                    modals[index].classList.add("hide");
                    fade.classList.add("hide");
                });
            });

            aceitaButtons.forEach((button) => {
                button.addEventListener("click", function() {
                    const propostaId = this.getAttribute("data-proposta-id");
                    const estado = this.getAttribute("data-valor");
                    $.ajax({
                        url: 'update_proposal.php',
                        type: 'POST',
                        data: { propostaId: propostaId, estado: estado },
                        success: function(response) {
                            const res = JSON.parse(response);
                            if (res.status === 'success') {
                                $(`tr[data-proposta-id="${propostaId}"]`).hide();
                            } else {
                                alert(res.message);
                            }
                        }
                    });
                });
            });

            recusaButtons.forEach((button) => {
                button.addEventListener("click", function() {
                    const modalBody = this.closest('.modal-body');
                    const modalRecusa = modalBody.nextElementSibling;
                    modalBody.classList.add("hide");
                    modalRecusa.classList.remove("hide");
                });
            });

            enviarRecusaButtons.forEach((button) => {
                button.addEventListener("click", function() {
                    const propostaId = this.getAttribute("data-proposta-id");
                    const estado = 0;
                    const motivoRecusa = this.previousElementSibling.value;
                    $.ajax({
                        url: 'update_proposal.php',
                        type: 'POST',
                        data: { propostaId: propostaId, estado: estado, motivoRecusa: motivoRecusa },
                        success: function(response) {
                            const res = JSON.parse(response);
                            if (res.status === 'success') {
                                $(`tr[data-proposta-id="${propostaId}"]`).hide();
                            } else {
                                alert(res.message);
                            }
                        }
                    });
                });
            });
        });
        </script>
    </body>
</html>
