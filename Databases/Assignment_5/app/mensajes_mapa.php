<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Mapa simple de OpenStreetMap con Leaflet</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css">
</head>
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
  $sender = intval($_SESSION["id"]);
  $fecha1 = $_POST["fecha_1"];
  $fecha2 = $_POST["fecha_2"];
  $arr = array("fecha1" => $fecha1, "fecha2" => $fecha2);
  $data = json_encode($arr);
  $datos = CallAPI("POST", "https://young-waters-69541.herokuapp.com/mapa/$sender", $data);
  
  ?>
  <h1>Mapa simple de OpenStreetMap con Leaflet</h1>  
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>	  
<div id="map" class="map map-home" style="margin:12px 0 12px 0;height:400px;"></div>
<script>
	var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
		osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});
	var map = L.map('map').setView([23.140445, -82.351644], 2).addLayer(osm);
    var fromPHP=<?php echo $datos ?>;

    for (i=0; i<fromPHP.length; i++) {

        L.marker(fromPHP[i])
		.addTo(map)
		.openPopup();

}
</script>


<form action="login/welcome.php" method="post">
    <input type="submit" value="Volver">
  </form>