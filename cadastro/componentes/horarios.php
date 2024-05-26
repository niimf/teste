<?php

include 'C:\xampp\htdocs\baba-baby2\conn.php';
//$db_name = 'babababy_';
//$db_host = 'localhost';
//$db_port = '3306';
//$db_user = 'root';
//$db_password = '';
//$pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_password);

$querySQL = "SELECT * FROM turno";
$queryPreparada = $pdo->prepare($querySQL);
$queryPreparada->execute();
$queryPreparada->setFetchMode(PDO::FETCH_ASSOC);
$turnos = $queryPreparada->fetchAll();
?>

<div>
    <label for="horario">Disponibilidade de Horário:</label>
    <?php foreach ($turnos as $turno): ?>
        <div id='<?= $turno['idDia']; ?>'>
            <span for=""><?= $turno['nome']; ?></span>
            <input type="checkbox" name="turno[]" value="<?= $turno['idTurno']; ?>" />
        </div>
    <?php endforeach; ?>
</div>
