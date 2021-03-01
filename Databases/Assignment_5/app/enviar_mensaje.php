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

<h3>Enviar Mensaje</h3>

<form action="api_mensaje.php" method="post">
    Seleccione el usuario:
    <input list='lista_usuarios' name='idu' id='idu' style='width: 440px;'></label>
    <datalist id='lista_usuarios'>
    <?php
    require("config/conexion.php");
    $sql_q="SELECT idu,nombre FROM usuario";
    foreach ($db->query($sql_q) as $row) {
        echo  "<option value='$row[idu] - $row[nombre]'/>";
    }
    ?>
    </datalist>
    <br/>
    Ingrese el mensaje:
    <input type="text" name="mensaje">
    <br/>
    Ingrese la latitud:
    <input type="text" name="latitud">
    <br/>
    Ingrese la longitud:
    <input type="text" name="longitud">
    <br/>
    <br/>
    <input type="submit" value="Enviar">
</form>