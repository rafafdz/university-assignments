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

<h3>Buscar Registro a cambiar:</h3>
<form action="ingreso_sendero/cambiar_sendero.php" method="post">
    Seleccione el Registro:
    <input list='lista_registros' name='regid' id='regid' style='width: 440px;'></label>
    <datalist id='lista_registros'>
    <?php
    require("config/conexion.php");
    $id_usuario = $_SESSION["id"];
    $sql_q="SELECT registro.regid, registro.estado, sendero.nombre FROM registro, sendero WHERE registro.idu = '$id_usuario' AND sendero.ids = registro.ids;";
    foreach ($db->query($sql_q) as $row) {
        echo  "<option value='$row[regid] - $row[estado] - $row[nombre]'/>";
    }
    ?>
    </datalist>
    <br/>
    <br/>
    Seleccione el nuevo estado:
    <input list='lista estados' type="text" id="estado" name="estado" required>
    <datalist id="lista estados">
    <option value="en ruta">
    <option value="finalizado">
    <option value="perdido">
    </datalist>
    <br/>
    <br/>
    <input type="submit" value="Seleccionar">
</form>
