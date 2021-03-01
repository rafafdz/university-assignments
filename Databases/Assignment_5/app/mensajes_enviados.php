<?php
require("config/conexion.php");
require("config/callapi.php");
include('templates/header.html');
include('templates/navbar_volver_mensajes.html');
session_start();
$user_id = $_SESSION["id"];
$datos = json_decode(CallAPI("GET", "https://young-waters-69541.herokuapp.com/users/$user_id"), true);
?>
<h2 align="center">Tus Mensajes Enviados</h2>
<?php foreach ($datos['mensajes'] as $d): ?>
<p>Mensaje para:
<?php 
    $idu = print_r($d['receptant'], true);
    $query = "SELECT correo FROM usuario WHERE idu = $idu";

	$result = $db -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
?>
<?php print_r($dataCollected[0][0]); ?></p>
<p>Fecha:
<?php print_r($d['date']); ?></p>
<p>Mensaje:
<?php print_r($d['message']); ?></p>
<br>
<br>
<br>
<?php endforeach; ?>
</body>
</html>