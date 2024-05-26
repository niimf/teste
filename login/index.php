<?php
    if(!isset($_SESSION)){
        session_start(); // Inicia a sessão
    }
    if(array_key_exists("msgErro",$_SESSION)){
        $msgErro = $_SESSION["msgErro"];
    }else{
        $msgErro = false;
    }
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BabáBaby</title>
    <link rel="shortcut icon" type="imagex/png" href="imgIndex/bbbyynew.ico">
    <link rel="stylesheet" href="login/style.css">
</head>
<body>
    <div class="navbar">
        <a class="nav-button" href="#sobre">Sobre</a>
        <a class="nav-button" href="#contato">Contato</a>
    </div>

    <div class="content">
        <div class="carrossel">
            <div class="imagens-carrossel">
                <img src="imgIndex/bannerbaba0.jpg" alt="Imagem 1">
                <img src="imgIndex/bannerbaba2.jpg" alt="Imagem 2">
                <img src="imgIndex/bannerbaba3.jpg" alt="Imagem 3">
                <img src="imgIndex/bannerbaba4.jpg" alt="Imagem 4">
            </div>
        </div>

        <div class="logo">
            <img src="imgIndex/logo.png" alt="Logo Babababy">
        
            <button id="open-modal" class="enter-button">Entrar</button>
            <div id="fade" class="hide"></div>
            <div id="modal" class="hide">
                <div id="modal-header">
                    <h1>Faça seu login!</h1>
                    <button id="close-modal">x</button>
                </div>
                <div id="modal-body">
                    <form method="POST" action="login\loginBack.php">
                        <!-- Exibir a mensagem de erro aqui -->
                        <?php if(isset($_SESSION['msgErro'])): ?>
                        <div class="msg_erro"><?php echo $_SESSION['msgErro']; ?></div>
                        <?php unset($_SESSION['msgErro']); ?>
                        <!-- Remove a mensagem de erro da sessão -->
                        <?php endif; ?>

                        <input type="email" name="email" placeholder="Digite seu Email" required>
                        <input class="passw_area" type="password" name="senha" placeholder="Digite sua Senha" required>
                        
                        <p>Não possui conta? <a href="src/cadastro/cadastro.php">Cadastre-se</a><p>


                        <button class="btn_login" name="SendLogin">Logar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script> 
        //Script para funcionamento do Carrossel
        let index = 0;
        const images = document.querySelectorAll('.imagens-carrossel img');

        function updateCarousel() {
            document.querySelector('.imagens-carrossel').style.transform = `translateX(${-index * 100}%)`;
        }

        setInterval(() => {
            index = index < images.length - 1 ? index + 1 : 0;
            updateCarousel();
        }, 5500); // Velocidade de rotação do carrossel


        //Script para funcionamento do Modal
        const openModalButton = document.querySelector("#open-modal");
        const closeModalButton = document.querySelector("#close-modal");
        const modal = document.querySelector("#modal");
        const fade = document.querySelector("#fade");

        const toggleModal = () => {
            //modal.classList.toggle("hide");
            //fade.classList.toggle("hide");
            [modal, fade].forEach((el) => el.classList.toggle("hide"));
        }

        [openModalButton, closeModalButton, fade].forEach((el) => {
            el.addEventListener("click", () => toggleModal())
        })
    </script>
</body>
</html>
