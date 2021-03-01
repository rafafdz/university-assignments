<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}
?>

<?php include('../templates/header.html'); ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_hotel = $_POST["hotid"];
  $id_hotel = explode (" - ", $id_hotel);
  $id_hotel = strval($id_hotel[0]);

  $query = "SELECT habid,nombre,precio FROM habitaciones WHERE hotid='$id_hotel';";
  ?>

<h3>Seleccion de habitacion y fecha:</h3>

<form action="reserva_habitacion.php" method="post">
    Seleccione la habitacion:
    <input list='lista_habit' name='habid' id='habid' style='width: 440px;'></label>
    <datalist id='lista_habit'>
    <?php
    foreach ($db2->query($query) as $row) {
        echo  "<option value='$row[habid] - $row[nombre] - \$$row[precio]'/>";
    }
    ?>
    </datalist>
    <br/>
    <br/>
    Seleccione la fecha de inicio:
    <input type="date" id="fecha_inicio" name="fecha_inicio" required>
    <br/>
    <br/>
    Seleccione la fecha de termino:
    <input type="date" id="fecha_termino" name="fecha_termino" min="fecha_inicio" required>
    <input type="submit" value="Reservar">
</form>

<?php include('../templates/footer.html'); ?>