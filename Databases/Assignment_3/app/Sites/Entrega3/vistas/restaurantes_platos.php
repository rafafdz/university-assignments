<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_restaurant = $_POST["restaurante"];
  $id_restaurant = intval($id_restaurant);

 	$query = "SELECT nombre, precio, descripcion FROM platos WHERE restid=$id_restaurant";

	$result = $db2 -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>nombre plato</th>
        <th>precio</th>
        <th>descripcion</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[0]</td> <td>$p[1]</td> <td>$p[2]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
