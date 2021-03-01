<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}
?>


<?php include('templates/header.html');   ?>

<h3>Marcar restaurante:</h3>

<form action="favoritos/dejar_favorito.php" method="post">
    Seleccione el restaurante:
    <input list='lista_restaurantes' name='restid' id='restid' style='width: 440px;'></label>
    <datalist id='lista_restaurantes'>
    <?php
    require("config/conexion.php");
    $sql_q="SELECT restid,nombre FROM restaurantes";
    foreach ($db2->query($sql_q) as $row) {
        echo  "<option value='$row[restid] - $row[nombre]'/>";
    }
    ?>
    </datalist>
    <br/>
    <br/>
    <input type="submit" value="Favorito">
</form>
