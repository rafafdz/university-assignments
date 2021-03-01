<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_restaurant = $_POST["restaurant"];
  $id_restaurant = intval($id_restaurant);

 	$query = "SELECT * FROM restaurantes WHERE restid=$id_restaurant";

	$result = $db2 -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Direccion</th>
        <th>Telefono</th>
        <th>Descripcion</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[2]| </td> <td>$p[3]| </td> <td>$p[4]| </td> <td>$p[5]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
