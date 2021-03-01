<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_hotel = $_POST["hotel"];
  $id_hotel = intval($id_hotel);

 	$query = "SELECT habid, precio FROM habitaciones WHERE hotid=$id_hotel";

	$result = $db2 -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>id habitacion</th>
        <th>precio</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[0]</td> <td>$p[1]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
