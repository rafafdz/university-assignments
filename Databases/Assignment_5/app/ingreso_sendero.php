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

<h3>Buscar por nombre Parque Nacional:</h3>
<form action="ingreso_sendero/ingresa_sendero.php" method="post">
    Seleccione el Parque:
    <input list='lista_parques' name='idp' id='idp' style='width: 440px;'></label>
    <datalist id='lista_parques'>
    <?php
    require("config/conexion.php");
    $sql_q="SELECT idp,nombre FROM ParquesNacionales";
    foreach ($db->query($sql_q) as $row) {
        echo  "<option value='$row[idp] - $row[nombre]'/>";
    }
    ?>
    </datalist>
    <br/>
    <br/>
    <input type="submit" value="Ingresar">
</form>
