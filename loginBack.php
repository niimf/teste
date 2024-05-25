<?php
require 'C:\\xampp\\htdocs\\baba-baby2\\conn.php';

session_start(); // Inicia a sessão
define("BASE_URL","http://localhost/baba-baby2/");
define("BASE_URL_INDEX","http://localhost/baba-baby2/index.php");
define("BASE_URL_PAIS","http://localhost/baba-baby2/menuPais.php");
define("BASE_URL_BABA","http://localhost/baba-baby2/menuBaba.php");
define("BASE_URL_ADMIN","http://localhost/baba-baby2/menuAdmin.php");



// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o email e a senha foram enviados
    if (isset($_POST['email']) && isset($_POST['senha'])) {
        // Obtém o email e a senha enviados pelo formulário
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        try {
            // Consulta SQL para verificar se o email e senha estão presentes na tabela usuario
            $sql = $pdo->prepare("SELECT idUsuario, nome, adm FROM usuario WHERE email = :email AND senha = :senha");
            $sql->bindValue(':email', $email);
            $sql->bindValue(':senha', $senha);
            $sql->execute();
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            // Verifica se a consulta retornou algum resultado
            if ($row !== false) {
                $id = $row['idUsuario'];
                $isAdmin = $row['adm']; // Verifica se o usuário é um administrador
                
                // Define a variável de sessão com o ID do usuário e o nome
                $_SESSION['idUsuario'] = $row['idUsuario'];
                $_SESSION['nome'] = $row['nome'];

                // Se o usuário for um administrador, redireciona para uma página específica
                if ($isAdmin == 1) {
                    header("Location:".BASE_URL_ADMIN);
                    exit();
                }

                // Verifica se o mesmo ID está presente apenas na tabela baba
                $sql = $pdo->prepare("SELECT COUNT(*) AS total FROM baba WHERE pk_idUsuario = :idUsuario");
                $sql->bindValue(':idUsuario', $id);
                $sql->execute();
                $result = $sql->fetch(PDO::FETCH_ASSOC);
                
                // Se o mesmo ID estiver presente em baba, redireciona para página menu-babá
                if ($result['total'] > 0) {
                    // Define a variável de sessão com o ID do usuário
                    header("Location:".BASE_URL_BABA);
                    exit();

                } else {
                    // Se o ID não estiver presente em baba, redireciona para página menu-pais
                    header("Location:".BASE_URL_PAIS);
                    exit();
                }
            } else {
                // Se o email e senha não estiverem presentes na tabela usuario, define a mensagem de erro
                $_SESSION['msgErro'] = "Email ou senha inválidos.";
                header("Location:".BASE_URL_INDEX);
                exit();
                
            }

            if(isset($_SESSION['msgErro'])){
                echo $_SESSION['msgErro'];
                unset($_SESSION['msgErro']);
            }

        } catch(PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }
    }
}

// Fecha a conexão com o banco de dados
$pdo = null;
?>
