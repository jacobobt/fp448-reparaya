<h1><?php echo APP_NAME; ?></h1>
<p>Proyecto base en PHP sin framework</p>

<?php
$sql = "SELECT COUNT(*) AS total FROM especialidades";
$stmt = $pdo->query($sql);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<p>Total de especialidades en la base de datos: <?php echo $resultado['total']; ?></p>