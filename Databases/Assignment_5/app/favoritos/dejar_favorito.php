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

  $id_rest = $_POST["restid"];
  $id_rest = explode (" - ", $id_rest);
  $id_rest = strval($id_rest[0]);
  $id_usuario = $_SESSION["id"];



  $query = "SELECT * FROM favoritos WHERE restid='$id_rest' AND uid = '$id_usuario'";

  $result = $db2 -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll();
  unset($result);

  if(count($dataCollected)){
      echo "<h2>Ya tienes en favoritos este restaurant</h2>";
      echo "<a href='../index.php'>Ir a inicio</a>";
  }
  else{

    $query = "INSERT INTO favoritos (restid, uid) VALUES (:restid, :uid)";
    $stmt = $db2->prepare($query);
    $stmt -> bindParam(':restid', $id_rest, PDO::PARAM_STR);
    $stmt -> bindParam(':uid', $id_usuario, PDO::PARAM_STR);
    $stmt -> execute();
    unset($stmt);

    echo "<h2>Se ha hecho la reserva exitosamente.</h2>";
  }

?>

<?php include('../templates/footer.html'); ?>
