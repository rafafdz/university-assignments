<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}
?>


<body>
<?php
  require("config/callapi.php");
  $sender = intval($_SESSION["id"]);
  $receptant = $_POST["idu"];
  $receptant = explode (" - ", $receptant);
  $receptant = intval(($receptant[0]));
  $mensaje = $_POST["mensaje"];
  $latitud = $_POST["latitud"];
  $longitud = $_POST["longitud"];

  $arr = array("sender" => $sender, "receptant" => $receptant, "message" => $mensaje, "lat" => $latitud, "long" => $longitud);
  $data = json_encode($arr);
  $datos = json_decode(CallAPI("POST", "https://young-waters-69541.herokuapp.com/mensaje", $data), true);
  

  if ($datos['success'] == 1) {
    echo '<h2>Mensaje creado</h2>';
} else {
    echo '<h2>Mensaje no creado</h2>';
}
  ?>

<form action="login/welcome.php" method="post">
    <input type="submit" value="Volver">
  </form>