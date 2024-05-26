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
    <!-- Favicon -->
    <link rel="shortcut icon" type="imagex/png" href="imgIndex">
    <!-- Estilo customizado -->
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- modal -->
    <link rel="stylesheet" href="propostas.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->
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
            <a href="../menuBaba.php" class="sidebar-nav"><i class="icon fa-solid fa-house" style="color: #000000;"></i><span>Início</span></a>
            <a href="dadosBaba.php" class="sidebar-nav"><i class="icon fa-solid fa-user" style="color: #000000;"></i><span>Dados</span></a>     
            <a href="servicosBaba.php" class="sidebar-nav active"><i class="icon fa-solid fa-clock-rotate-left" style="color: #000000;"></i><span>Serviços</span></a>        
            <a href="../index.php" class="sidebar-nav"><i class="icon fa-solid fa-right-from-bracket" style="color: #e90c0c;"></i><span>Sair</span></a>
        </div>
        <!-- Início do conteúdo do administrativo -->
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
                    $idUsuario = $_SESSION['idUsuario']; // Usuário logado

                    try {
                        // Passo 1: Obter o idBaba correspondente ao usuário logado
                        $sql_idBaba = $pdo->prepare("SELECT idBaba FROM baba WHERE pk_idUsuario = :idUsuario");
                        $sql_idBaba->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idBaba->execute();
                        $result = $sql_idBaba->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idBaba = $result['idBaba'];

                            // Passo 2: Obter as propostas para esse idBaba e incluir o nome do solicitante
                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.fk_idBaba, p.fk_Ppk_idUsuario, p.data, p.turno, u.nome AS nomeSolicitante, pa.descricao, u.telefone, u.email, p.estado
                                FROM proposta p
                                LEFT JOIN pais pa ON p.fk_Ppk_idUsuario = pa.pk_idUsuario
                                LEFT JOIN usuario u ON pa.pk_idUsuario = u.idUsuario
                                WHERE p.fk_idBaba = :idBaba AND p.estado IS NULL
                            ");
                            $sql_proposta->bindParam(':idBaba', $idBaba, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            // Verifica se há propostas e exibe-as
                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    // Formatar a data
                                    $dataFormatada = (new DateTime($proposta['data']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeSolicitante'] ?></td>
                                        <td class='list-body-content'><?= $dataFormatada ?></td>
                                        <td class='list-body-content'><button type='button' class="open-modal botao-visualizar">Visualizar</button></td>
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
                                            <button type="button" class="btn-aceita" data-valor="1" data-proposta-id="<?= $proposta['idProposta'] ?>">Aceitar</button> 
                                            <button type="button" class="btn-recusa" data-valor="0" data-proposta-id="<?= $proposta['idProposta'] ?>">Recusar</button>
                                        </div>
                                        <!-- Formulário de Recusa -->
                                        <div class="modal-recusa hide">
                                            <h3>Motivo da Recusa</h3>
                                            <form>
                                                <textarea name="motivo" rows="4" cols="50" placeholder="Digite o motivo da recusa"></textarea><br>
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
        <!-- Fim do conteúdo do administrativo -->
    </div>
    <!-- Fim Conteúdo -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const aceitaButtons = document.querySelectorAll(".btn-aceita");
            const recusaButtons = document.querySelectorAll(".btn-recusa");
            const enviarRecusaButtons = document.querySelectorAll(".btn-enviar-recusa");

            aceitaButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const propostaId = this.getAttribute("data-proposta-id");
                    $.ajax({
                        url: 'atualizar_proposta.php',
                        type: 'POST',
                        data: { idProposta: propostaId, estado: 1 },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                document.querySelector(`tr[data-proposta-id="${propostaId}"]`).style.display = 'none';
                            } else {
                                alert("Erro ao aceitar a proposta: " + response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert("Erro ao aceitar a proposta: " + textStatus + " - " + errorThrown);
                        }
                    });
                });
            });

            recusaButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const modalBody = this.closest(".modal-body");
                    const modalRecusa = modalBody.nextElementSibling;
                    modalBody.classList.add("hide");
                    modalRecusa.classList.remove("hide");
                });
            });

            enviarRecusaButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const propostaId = this.getAttribute("data-proposta-id");
                    const motivo = this.previousElementSibling.value;
                    $.ajax({
                        url: 'atualizar_proposta.php',
                        type: 'POST',
                        data: { idProposta: propostaId, estado: 0, motivoRecusa: motivo },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                document.querySelector(`tr[data-proposta-id="${propostaId}"]`).style.display = 'none';
                            } else {
                                alert("Erro ao recusar a proposta: " + response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert("Erro ao recusar a proposta: " + textStatus + " - " + errorThrown);
                        }
                    });
                });
            });

            const openModalButtons = document.querySelectorAll(".open-modal");
            const closeModalButtons = document.querySelectorAll(".close-modal");
            const fades = document.querySelectorAll(".fade");
            const modals = document.querySelectorAll(".modal");

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
        });

    </script>
</body>
</html>
