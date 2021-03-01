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

  $id_habit = $_POST["habid"];
  $id_habit = explode (" - ", $id_habit);
  $id_habit = strval($id_habit[0]);
  $fecha_inicio = $_POST["fecha_inicio"];
  $fecha_fin = $_POST["fecha_termino"];
  $id_usuario = $_SESSION["id"];
  


  $query = "SELECT * FROM Reservas WHERE habid='$id_habit' AND NOT('$fecha_fin'<=fecha_inicio OR fecha_fin<='$fecha_inicio') OR '$fecha_fin' <= '$fecha_inicio'";

  $result = $db2 -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll();
  unset($result);

  if(count($dataCollected)){
      echo "<h2>No es posible reservar la habitacion en esas fechas.</h2>";
      echo "<a href='../reservar.php'>Ir a reservas</a>";
  }
  else{
    $query = "SELECT MAX(resvid) FROM Reservas";
    $result = $db2 -> prepare($query);
    $result -> execute();
    $last_id = $result -> fetchAll();
    $new_id = intval($last_id[0][0]) + 1;
    echo "$new_id";
    unset($result);

    $query = "INSERT INTO Reservas (resvid, uid, habid, fecha_inicio, fecha_fin) VALUES (:resvid, :uid, :habid, :fecha_inicio, :fecha_fin)";
    $stmt = $db2->prepare($query);
    $stmt -> bindParam(':resvid', $new_id, PDO::PARAM_STR);
    $stmt -> bindParam(':uid', $id_usuario, PDO::PARAM_STR);
    $stmt -> bindParam(':habid', $id_habit, PDO::PARAM_STR);
    $stmt -> bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
    $stmt -> bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
    $stmt -> execute();
    unset($stmt);

    echo "<h2>Se ha hecho la reserva exitosamente.</h2>";
  }

?>

<?php include('../templates/footer.html'); ?>
