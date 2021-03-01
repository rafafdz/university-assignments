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



<h3>Filtro de mensajes</h3>

<form action="api_filtro_id.php" method="post">
    Ingrese el id del usuario para filtrar sus mensajes:
    <input type="text" name="id">
    <br/>
    Ingrese las frases que deben ir si o si en el mensaje(separadas por comas):
    <input type="text" name="obligatorias">
    <br/>
    Ingrese las palabras que deben estar deseablemente en el mensaje(separadas por comas):
    <input type="text" name="opcionales">
    <br/>
    Ingrese las palabras que no deben estar en el mensaje(separadas por comas):
    <input type="text" name="prohibidas">
    <br/>
    <br/>
    <input type="submit" value="Enviar">
</form>