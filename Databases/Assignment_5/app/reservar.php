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

<h3>Buscar por nombre de hotel:</h3>

<form action="reservar/reserva_hotel.php" method="post">
    Seleccione el hotel:
    <input list='lista_hoteles' name='hotid' id='hotid' style='width: 440px;'></label>
    <datalist id='lista_hoteles'>
    <?php
    require("config/conexion.php");
    $sql_q="SELECT hotid,nombre FROM hoteles";
    foreach ($db2->query($sql_q) as $row) {
        echo  "<option value='$row[hotid] - $row[nombre]'/>";
    }
    ?>
    </datalist>
    <br/>
    <br/>
    <input type="submit" value="Reservar">
</form>