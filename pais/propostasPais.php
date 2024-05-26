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
        <title>Propostas Pais</title>
        <link rel="shortcut icon" type="imagex/png" href="../imgIndex/bbbyynew.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="propostasPais.css">
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
                <a href="../menuPais.php" class="sidebar-nav"><i class="icon fa-solid
                    fa-house" style="color: #000000;"></i><span>Início</span></a>
                <a href="dadosPais.php" class="sidebar-nav"><i class="icon fa-solid 
                    fa-user" style="color: #000000;"></i><span>Dados</span></a>     
                <a href="propostasPais.php" class="sidebar-nav active"><i class="icon fa-solid 
                    fa-clock-rotate-left" style="color: #000000;"></i><span>Serviços</span></a>           
                <a href="login/sair.php" class="sidebar-nav"><i class="icon fa-solid 
                    fa-right-from-bracket" style="color: #e90c0c;"></i><span>Sair</span></a> 
            </div>
            
            <!-- Início do conteúdo do administrativo -->
            <div class="wrapper">
            <div class="row">
            <div class="top-list">
                    <span class="title-content">Propostas Ativas</span>
                </div>
                <table class="table-list">
                    <thead class="list-head">
                        <tr>
                            <th class="list-head-content">Babá</th>
                            <th class="list-head-content">Data</th>
                            <th class="list-head-content">Ações</th>
                            <th class="list-head-content">Status</th>
                        </tr>
                    </thead>
                    <tbody class="list-body">
                    <?php
                    $idUsuario = $_SESSION['idUsuario'];

                    try {
                        // Passo 1: Obter o idPais correspondente ao usuário logado
                        $sql_idPais = $pdo->prepare("SELECT idPais FROM pais WHERE pk_idUsuario = :idUsuario");
                        $sql_idPais->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idPais->execute();
                        $result = $sql_idPais->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idPais = $result['idPais'];

                            // Passo 2: Obter as propostas para esse idPais e incluir o nome da babá
                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.fk_idPais, p.fk_Bpk_idUsuario, p.data, p.estado, p.turno, u.nome AS nomeBaba, ba.sobre, u.telefone, u.email, p.dataAceite, p.dataPronto
                                FROM proposta p
                                LEFT JOIN baba ba ON p.fk_Bpk_idUsuario = ba.pk_idUsuario
                                LEFT JOIN usuario u ON ba.pk_idUsuario = u.idUsuario
                                WHERE p.fk_idPais = :idPais AND p.estado = 1 AND p.dataAceite IS NOT NULL
                            ");
                            $sql_proposta->bindParam(':idPais', $idPais, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            // Verifica se há propostas e exibe-as
                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    $dataFormatada = (new DateTime($proposta['data']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeBaba'] ?></td>
                                        <td class='list-body-content'><?= $dataFormatada ?></td>
                                        <td class='list-body-content'>
                                            <button type='button' class="open-modal botao-visualizar">Visualizar</button>
                                            <button type='button' class="botao-finalizar" data-id="<?= $proposta['idProposta'] ?>">Finalizado</button>
                                        </td>
                                        <td class='list-body-content'>Em processo</td>
                                    </tr>

                                    <!-- Modal de Detalhes da Proposta -->
                                    <div class="fade hide"></div>
                                    <div class="modal hide">
                                        <!-- Cabeçalho do Modal -->
                                        <div class="modal-header">
                                            <ul>
                                                <li class="nome">Babá: <?=$proposta['nomeBaba']?></li>
                                                <li>Dia: <?=$dataFormatada?></li>
                                                <li>Turno: <?=$proposta['turno']?></li>
                                            </ul>
                                            <button class="close-modal">x</button>
                                        </div>
                                        <!-- Corpo do Modal -->
                                        <div class="modal-body"> 
                                            <div class="about">
                                                <h3>Sobre a babá:</h3>
                                                <p><?=$proposta['sobre']?></p><br>
                                            </div>
                                            <div class="contact">
                                                <br>
                                                <h3>Contato:</h3>
                                                <p>Telefone: <?=$proposta['telefone']?></p>
                                                <p>Email: <?=$proposta['email']?></p>   
                                                <br>
                                            </div>
                                            <?php if ($proposta['dataAceite'] !== null): ?>
                                                <div class="data-aceite">
                                                    <br>
                                                    <h3>Data de Aceite:</h3>
                                                    <p><?= (new DateTime($proposta['dataAceite']))->format('d/m/Y') ?></p> 
                                                    <br>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                            } else {
                                echo "<tr><td colspan='3' class='list-body-content'>Nenhuma proposta encontrada...</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='list-body-content'>Erro ao encontrar o idPais correspondente.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='3' class='list-body-content'>Erro ao processar dados: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="middle-list">
                    <span class="title-content"><br></br>Propostas enviadas</span>
                </div>
                <table class="table-list">
                    <thead class="list-head">
                        <tr>
                            <th class="list-head-content">Babá</th>
                            <th class="list-head-content">Data</th>
                            <th class="list-head-content">Ações</th>
                            <th class="list-head-content">Status</th>
                        </tr>
                    </thead>
                    <tbody class="list-body">
                    <?php
                    $idUsuario = $_SESSION['idUsuario']; // Usuário logado

                    try {
                        // Passo 1: Obter o idPais correspondente ao usuário logado
                        $sql_idPais = $pdo->prepare("SELECT idPais FROM pais WHERE pk_idUsuario = :idUsuario");
                        $sql_idPais->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idPais->execute();
                        $result = $sql_idPais->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idPais = $result['idPais'];

                            // Passo 2: Obter as propostas para esse idPais e incluir o nome do solicitante
                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.fk_idPais, p.fk_Bpk_idUsuario, p.data, p.turno, u.nome AS nomeBaba, ba.sobre, u.telefone, u.email, p.dataAceite
                                FROM proposta p
                                LEFT JOIN baba ba ON p.fk_Bpk_idUsuario = ba.pk_idUsuario
                                LEFT JOIN usuario u ON ba.pk_idUsuario = u.idUsuario
                                WHERE p.fk_idPais = :idPais AND p.dataAceite IS NULL
                            ");
                            $sql_proposta->bindParam(':idPais', $idPais, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            // Verifica se há propostas e exibe-as
                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    // Formatar a data
                                    $dataFormatada = (new DateTime($proposta['data']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeBaba'] ?></td>
                                        <td class='list-body-content'><?= $dataFormatada ?></td>
                                        <td class='list-body-content'><button type='button' class="open-modal botao-visualizar">Visualizar</button></td>
                                        <td class='list-body-content'>Enviada</td>
                                    </tr>

                                    <!-- Modal de Detalhes da Proposta -->
                                    <div class="fade hide"></div>
                                    <div class="modal hide">
                                        <!-- Cabeçalho do Modal -->
                                        <div class="modal-header">
                                            <ul>
                                                <li class="nome">Babá: <?=$proposta['nomeBaba']?></li>
                                                <li>Dia: <?=$dataFormatada?></li>
                                                <li>Turno: <?=$proposta['turno']?></li>
                                            </ul>
                                            <button class="close-modal">x</button>
                                        </div>
                                        <!-- Corpo do Modal -->
                                        <div class="modal-body"> 
                                            <div class="about">
                                                <h3>Sobre a babá:</h3>
                                                <p><?=$proposta['sobre']?></p><br>
                                            </div>
                                            <div class="contact">
                                                <br>
                                                <h3>Contato:</h3>
                                                <p>Telefone: <?=$proposta['telefone']?></p>
                                                <p>Email: <?=$proposta['email']?></p>   
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
                            echo "<tr><td colspan='3' class='list-body-content'>Erro.2) ao encontrar o idPais correspondente.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='3' class='list-body-content'>Erro.2) ao processar dados: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="down-list">
                    <span class="title-content"><br></br>Propostas Finalizadas</span>
                </div>
                <table class="table-list">
                    <thead class="list-head">
                        <tr>
                            <th class="list-head-content">Babá</th>
                            <th class="list-head-content">Data Finalizada</th>
                            <th class="list-head-content">Status</th>
                        </tr>
                    </thead>
                    <tbody class="list-body">
                    <?php
                    $idUsuario = $_SESSION['idUsuario'];

                    try {
                        $sql_idPais = $pdo->prepare("SELECT idPais FROM pais WHERE pk_idUsuario = :idUsuario");
                        $sql_idPais->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idPais->execute();
                        $result = $sql_idPais->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idPais = $result['idPais'];

                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.fk_idPais, p.fk_Bpk_idUsuario, u.nome AS nomeBaba, p.dataPronto
                            FROM proposta p
                            LEFT JOIN baba ba ON p.fk_Bpk_idUsuario = ba.pk_idUsuario
                            LEFT JOIN usuario u ON ba.pk_idUsuario = u.idUsuario
                            WHERE p.fk_idPais = :idPais AND p.dataPronto IS NOT NULL AND p.dataPronto != ''  
                            ");
                            $sql_proposta->bindParam(':idPais', $idPais, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    $dataFormatada = (new DateTime($proposta['dataPronto']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeBaba'] ?></td>
                                        <td class='list-body-content'><?= $dataFormatada ?></td>
                                        <td class='list-body-content'>Finalizado</td>
                                    </tr>
                                <?php
                                endforeach;
                            } else {
                                echo "<tr><td colspan='3' class='list-body-content'>Nenhuma proposta encontrada...</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='list-body-content'>Erro.3) ao encontrar o idPais correspondente.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='3' class='list-body-content'>Erro.3) ao processar dados: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="toodown-list">
                    <span class="title-content"><br></br>Propostas Recusadas</span>
                </div>
                <table class="table-list">
                    <thead class="list-head">
                        <tr>
                            <th class="list-head-content">Babá</th>
                            <th class="list-head-content">Data</th>
                            <th class="list-head-content">Status</th>
                            <th class="list-head-content">Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="list-body">
                    <?php
                    $idUsuario = $_SESSION['idUsuario'];

                    try {
                        $sql_idPais = $pdo->prepare("SELECT idPais FROM pais WHERE pk_idUsuario = :idUsuario");
                        $sql_idPais->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
                        $sql_idPais->execute();
                        $result = $sql_idPais->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $idPais = $result['idPais'];

                            $sql_proposta = $pdo->prepare("SELECT p.idProposta, p.fk_idPais, p.fk_Bpk_idUsuario, p.data, p.estado, p.turno, u.nome AS nomeBaba, ba.sobre, u.telefone, u.email, p.dataRecusa, p.motivoRecusa
                                FROM proposta p
                                LEFT JOIN baba ba ON p.fk_Bpk_idUsuario = ba.pk_idUsuario
                                LEFT JOIN usuario u ON ba.pk_idUsuario = u.idUsuario
                                WHERE p.fk_idPais = :idPais AND p.estado = 0
                            ");
                            $sql_proposta->bindParam(':idPais', $idPais, PDO::PARAM_INT);
                            $sql_proposta->execute();
                            $propostas = $sql_proposta->fetchAll(PDO::FETCH_ASSOC);

                            if (count($propostas) > 0) {
                                foreach ($propostas as $proposta):
                                    $dataFormatada = (new DateTime($proposta['data']))->format('d/m/Y');
                                    $dataRFormatada = (new DateTime($proposta['dataRecusa']))->format('d/m/Y');
                            ?>
                                    <tr data-proposta-id="<?= $proposta['idProposta'] ?>">
                                        <td class='list-body-content'><?= $proposta['nomeBaba'] ?></td>
                                        <td class='list-body-content'><?= $dataRFormatada ?></td>
                                        <td class='list-body-content'>Recusada</td>
                                        <td class='list-body-content'><?= $proposta['motivoRecusa'] ?></td>
                                    </tr>
                                <?php
                                endforeach;
                            } else {
                                echo "<tr><td colspan='3' class='list-body-content'>Nenhuma proposta encontrada...</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='list-body-content'>Erro.1) ao encontrar o idPais correspondente.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='3' class='list-body-content'>Erro.1) ao processar dados: " . $e->getMessage() . "</td></tr>";
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
            const finalizarButtons = document.querySelectorAll(".botao-finalizar");

            // Função para abrir modal
            openModalButtons.forEach((button, index) => {
                button.addEventListener("click", () => {
                    modals[index].classList.remove("hide");
                    fades[index].classList.remove("hide");
                });
            });

            // Função para fechar modal
            closeModalButtons.forEach((button, index) => {
                button.addEventListener("click", () => {
                    modals[index].classList.add("hide");
                    fades[index].classList.add("hide");
                });
            });

            // Função para fechar modal ao clicar fora
            fades.forEach((fade, index) => {
                fade.addEventListener("click", () => {
                    modals[index].classList.add("hide");
                    fade.classList.add("hide");
                });
            });

            // Função para aceitar proposta
            aceitaButtons.forEach((button) => {
                button.addEventListener("click", function() {
                    const propostaId = this.getAttribute("data-proposta-id");
                    const estado = this.getAttribute("data-valor");
                    const request = new XMLHttpRequest();
                    request.open('POST', 'update_proposal.php', true);
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    request.onload = function() {
                        if (this.status >= 200 && this.status < 400) {
                            const res = JSON.parse(this.responseText);
                            if (res.status === 'success') {
                                document.querySelector(`tr[data-proposta-id="${propostaId}"]`).style.display = 'none';
                            } else {
                                alert(res.message);
                            }
                        } else {
                            alert('Erro ao atualizar a proposta. Tente novamente.');
                        }
                    };
                    request.onerror = function() {
                        alert('Erro ao atualizar a proposta. Tente novamente.');
                    };
                    request.send(`propostaId=${propostaId}&estado=${estado}`);
                });
            });

            // Função para exibir modal de recusa
            recusaButtons.forEach((button) => {
                button.addEventListener("click", function() {
                    const modalBody = this.closest('.modal-body');
                    const modalRecusa = modalBody.nextElementSibling;
                    modalBody.classList.add("hide");
                    modalRecusa.classList.remove("hide");
                });
            });

            // Função para finalizar proposta
            finalizarButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const propostaId = this.getAttribute('data-id');
                    const request = new XMLHttpRequest();
                    request.open('POST', 'atualiza_propostaP.php', true);
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    request.onload = function() {
                        if (this.status >= 200 && this.status < 400) {
                            location.reload();
                        } else {
                            alert('Erro ao atualizar a proposta. Tente novamente.');
                        }
                    };
                    request.onerror = function() {
                        alert('Erro ao atualizar a proposta. Tente novamente.');
                    };
                    request.send('idProposta=' + propostaId);
                });
            });
        });
        </script>

    </body>
</html>
