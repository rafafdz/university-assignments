<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_hotel = $_POST["hotel"];
  $id_hotel = intval($id_hotel);

 	$query = "SELECT * FROM hoteles WHERE hotid=$id_hotel";

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
        <th>Estrellas</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[2]|</td> <td>$p[3]|</td> <td>$p[4]|</td> <td>$p[5]|</td> <td>$p[6]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
