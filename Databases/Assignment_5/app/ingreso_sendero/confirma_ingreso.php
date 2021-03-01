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
  $id_sendero = $_POST["ids"];
  $id_sendero = explode (" - ", $id_sendero);
  $id_sendero = strval($id_sendero[0]);
  $fecha_inicio = $_POST["fecha_inicio"];
  $fecha_fin = $_POST["fecha_termino"];
  $id_usuario = $_SESSION["id"];

  $query = "SELECT * FROM registro where ids = '$id_sendero' AND NOT('$fecha_fin'<=fecha_ent OR fecha_sal<='$fecha_inicio') OR '$fecha_fin' <= '$fecha_inicio';";
  $result = $db -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll();
  unset($result);

  if(count($dataCollected)){
      echo "<h2>No es posible ingresar al sendero en esas fechas.</h2>";
      echo "<a href='../ingreso_sendero.php'>Ir a ingresos</a>";
  }
  else{
    $query = "SELECT MAX(regid) FROM registro";
    $result = $db -> prepare($query);
    $result -> execute();
    $last_id = $result -> fetchAll();
    $new_id = intval($last_id[0][0]) + 1;

    unset($result);
    $estado = "en ruta";

    $query = "INSERT INTO registro (regid, fecha_ent, fecha_sal, estado, idu, ids) VALUES (:regid, :fecha_inicio, :fecha_fin, :estado, :idu, :ids)";
    $stmt = $db->prepare($query);
    $stmt -> bindParam(':regid', $new_id, PDO::PARAM_STR);
    $stmt -> bindParam(':idu', $id_usuario, PDO::PARAM_STR);
    $stmt -> bindParam(':ids', $id_sendero, PDO::PARAM_STR);
    $stmt -> bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
    $stmt -> bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
    $stmt -> bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt -> execute();
    unset($stmt);

    echo "<h2>Se ha registrado el ingreso exitosamente.</h2>";
  }

?>

<?php include('../templates/footer.html'); ?>
