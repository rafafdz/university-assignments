<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_vina = $_POST["vina"];
  $id_vina = intval($id_vina);

 	$query = "SELECT * FROM vina WHERE idv=$id_vina";

	$result = $db -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Telefono</th>
        <th>Descripcion</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[1]| </td> <td>$p[2]| </td> <td>$p[3]| </td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
