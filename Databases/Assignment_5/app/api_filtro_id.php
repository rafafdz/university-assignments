<?php include('templates/header.html');   ?>
<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}
?>

<h2 align="center">Filtro Mensajes</h2>
<body>
<?php
  require("config/callapi.php");
  $id = intval($_POST["id"]);
  $obligatorias = explode (",", $_POST["obligatorias"]);
  if ($obligatorias) {
    '';
} else {
    $obligatorias = $_POST["obligatorias"];
}
  $opcionales = explode("," , $_POST["opcionales"]);
  if ($opcionales) {
    '';
} else {
    $opcionales = $_POST["opcionales"];
}
  $prohibidas = explode(",", $_POST["prohibidas"]);
  if ($prohibidas) {
    '';
} else {
    $prohibidas = $_POST["prohibidas"];
}
  $arr = array("obligatorias" => $obligatorias, "opcionales" => $opcionales, "prohibidas" => $prohibidas);
  $data = json_encode($arr);
  $datos = json_decode(CallAPI("POST", "https://young-waters-69541.herokuapp.com/filtro_mensajes/$id", $data), true);
  ?>
  <?php foreach ($datos as $d): ?>
    <p>Mensaje:
    <?php print_r($d['message']); ?></p>
    <br>
    <br>
    <br>
    <?php endforeach; ?>


<form action="login/welcome.php" method="post">
    <input type="submit" value="Volver">
  </form>