<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}
?>

<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");
  $reg_id = $_POST["regid"];
  $reg_id = explode (" - ", $reg_id);
  $reg_id = strval($reg_id[0]);
  $estado = $_POST["estado"];

  $opciones_estado = array("en ruta", "finalizado", "perdido");

  if (in_array ($estado , $opciones_estado)){
      $query = "UPDATE registro SET estado = '$estado' WHERE regid = '$reg_id';";
      $stmt = $db->prepare($query);
      $stmt -> execute();
      unset($stmt);
      echo "<h2>Se ha modificado el registro exitosamente.</h2>";
  }
  else{
      echo "<h2>No es posible agregar este estado al registro.</h2>";
      echo "<a href='../cambiar_estado.php'>Ir a cambiar estado</a>";
  }
?>

<?php include('../templates/footer.html'); ?>
