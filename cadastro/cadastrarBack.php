<?php

require "C:\\xampp\\htdocs\\baba-baby2\conn.php";
session_start();
//$db_name = 'babababy_';
//$db_host = 'localhost';
//$db_port = '3306';
//$db_user = 'root';
//$db_password = '';
//$pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_password);
//TIPO DE USUÁRIO
$radioUserType = filter_input(INPUT_POST, 'userType');
function salvarFoto(): ?string {
    //FOTO
    $path = '';
    if(isset($_FILES['foto'])){
        $foto = $_FILES['foto'];
        $pasta = "C:\\xampp\\htdocs\\baba-baby2\\src\\cadastro\\arquivos\\";
        $nomeDaFoto = $foto['name'];
        $extensao = strtolower(pathinfo($nomeDaFoto, PATHINFO_EXTENSION));
        if($extensao != "jpg" && $extensao != "png")
            die('tipo de arquivo não aceito');
        $path = $pasta . $nomeDaFoto . "." . $extensao;
        $deu_certo = move_uploaded_file($foto["tmp_name"], $path);
        if($deu_certo)
            echo"arquivo enviado";
        else
            echo"falha";
    }
    return $path;
}
function cadastrarUsuario(\PDO $pdo): ?int {
    //DADOS GERAIS
    $name = filter_input(INPUT_POST, "name");
    $sobrenome = filter_input(INPUT_POST, "sobrenome");
    $dtaNascimento = filter_input(INPUT_POST, "dtaNascimento");
    $cpf = filter_input(INPUT_POST, "cpf");
    $telefone = filter_input(INPUT_POST, "telefone");
    $cidade = filter_input(INPUT_POST, "cidade");
    $endereco = filter_input(INPUT_POST, "endereco");
    $email = filter_input(INPUT_POST, "email");
    $senha = filter_input(INPUT_POST, "senha");
    $foto = salvarFoto();
    $cpf = str_replace("-", "", str_replace(".", "", $cpf));
    $cadastroUsuarioSQL = $pdo->prepare("INSERT INTO usuario (cpf, nome, sobrenome, dtaNascimento, telefone, endereco, cidade, email, senha, foto) VALUES (:cpf, :nome, :sobrenome, :dtaNascimento, :telefone, :endereco, :cidade, :email, :senha,:foto)");
    $cadastroUsuarioSQL->bindValue(':cpf', $cpf);
    $cadastroUsuarioSQL->bindValue(':nome', $name);
    $cadastroUsuarioSQL->bindValue(':sobrenome', $sobrenome);
    $cadastroUsuarioSQL->bindValue(':dtaNascimento', $dtaNascimento);
    $cadastroUsuarioSQL->bindValue(':telefone', $telefone);
    $cadastroUsuarioSQL->bindValue(':endereco', $endereco);
    $cadastroUsuarioSQL->bindValue(':cidade', $cidade);
    $cadastroUsuarioSQL->bindValue(':email', $email);
    $cadastroUsuarioSQL->bindValue(':senha', $senha);
    $cadastroUsuarioSQL->bindValue(':foto',$foto);
    
    $cadastroUsuarioSQL->execute();
    return $pdo->lastInsertId();
}
function cadastrarPai(\PDO $pdo) {
    //DADOS PAI
    $idUsuario = $pdo->lastInsertId();
    $qntCriancas = filter_input(INPUT_POST, "qtdeCrianca");
    $descricaoFamilia = filter_input(INPUT_POST, "descricao");
    $sql = $pdo->prepare("INSERT INTO pais (qtdeCrianca, descricao, pk_idUsuario) VALUES (:qtdeCrianca, :descricao, :idUsuario);");
    $sql->bindValue(':qtdeCrianca', $qntCriancas);
    $sql->bindValue(':descricao', $descricaoFamilia);
    $sql->bindValue(':idUsuario', $idUsuario);
    $sql->execute();
}
function cadastrarBaba(\PDO $pdo) {
    //DADOS BABA
    $idUsuario = $pdo->lastInsertId();
    $tempoExp = filter_input(INPUT_POST, "tempoExp");
    $foneRef = filter_input(INPUT_POST, "ref");
    $sobre = filter_input(INPUT_POST, "sobre");
    $prefereciaCuidar = filter_input(INPUT_POST, "fk_idFxEtaria");
    $pretencaoSalarial = filter_input(INPUT_POST, "valor");
    $sql = $pdo->prepare("INSERT INTO baba (tempoExp, ref, sobre, valor, fk_idFxEtaria, pk_idUsuario) VALUES (:tempoExp, :ref, :sobre, :valor, :fk_idFxEtaria, :idUsuario);");
    $sql->bindValue(':tempoExp', $tempoExp);
    $sql->bindValue(':ref', $foneRef);
    $sql->bindValue(':sobre', $sobre);
    $sql->bindValue(':fk_idFxEtaria', $prefereciaCuidar);
    $sql->bindValue(':valor', $pretencaoSalarial);
    $sql->bindValue(':idUsuario', $idUsuario);
    $sql->execute();
    cadastrarDisponibilidadeBaba($pdo, $pdo->lastInsertId());
}
function cadastrarDisponibilidadeBaba(\PDO $pdo, int $idBaba) {
    $diasSelecionados = filter_input(INPUT_POST, 'dia', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $turnosSelecionados = filter_input(INPUT_POST, 'turno', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if(!empty($diasSelecionados) && !empty($turnosSelecionados)){
        foreach ($diasSelecionados as $dia) {
            foreach ($turnosSelecionados as $turno) {
                $sql = $pdo->prepare("INSERT INTO disponibilidade (fk_idBaba, fk_idDia, fk_idTurno) VALUES (:idBaba, :dia, :turno);");
                $sql->bindValue(':idBaba', $idBaba);
                $sql->bindValue(':dia', $dia);
                $sql->bindValue(':turno', $turno);
                $sql->execute();
            }
        }
    }
}
//EFETIVAR O CADASTRO
if ($radioUserType == "pai") {
    cadastrarUsuario($pdo);
    cadastrarPai($pdo);
} else {
    cadastrarUsuario($pdo);
    cadastrarBaba($pdo);
}
$url = "Location: ../../../index.php";
header($url);
