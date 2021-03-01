<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}

include('templates/header.html');
include('templates/navbar_volver_mensajes.html');

?>


<h3>Filtro de mensajes por fecha</h3>

<form action="mensajes_mapa.php" method="post">
    Ingrese fecha de inicio:
    <input type="date" name="fecha_1">
    <br/>
    Ingrese fecha de fin:
    <input type="date" name="fecha_2">
    <br/>
    <br/>
    <input type="submit" value="Enviar">
</form>