<?php

include 'C:\\xampp\\htdocs\\baba-baby2\\conn.php';
//$db_name = 'babababy_';
//$db_host = '127.0.0.1';
//$db_port = '3306';
//$db_user = 'root';
//$db_password = 'PUC@1234';
//$pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_password);

//OBTEM DIAS DA SEMANA
$querySQL = "SELECT * FROM dia";
$queryPreparada = $pdo->prepare($querySQL);
$queryPreparada->execute();
$queryPreparada->setFetchMode(PDO::FETCH_ASSOC);
$dias = $queryPreparada->fetchAll();
?>

<div>
    <label for="diasSemana">Tenho disponibilidade nos dias: </label>
    <?php foreach ($dias as $dia): ?>
        <div id='<?= $dia['idDia']; ?>'>
            <span for=""><?= $dia['nome']; ?></span>
            <input type="checkbox" name="dia[]" value="<?= $dia['idDia']; ?>" /><br>
        </div>
    <?php endforeach; ?>
</div>
