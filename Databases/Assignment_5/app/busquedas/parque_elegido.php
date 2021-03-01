<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_parque = $_POST["parque"];
  $id_parque = intval($id_parque);

 	$query = "SELECT * FROM parquesnacionales WHERE idp=$id_parque";

	$result = $db -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Hectareas</th>
        <th>Descripcion</th>
        <th>Tarifa</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[1]| </td> <td>$p[2]| </td> <td>$p[3]| </td> <td>$p[4]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
