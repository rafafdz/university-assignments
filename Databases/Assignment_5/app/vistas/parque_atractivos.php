<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_parque = $_POST["parque"];
  $id_parque = intval($id_parque);

 	$query = "SELECT nombre, descripcion, url FROM atractivo AS A, parqueatractivo AS PA WHERE PA.idp=$id_parque AND A.ida = PA.ida";

	$result = $db -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Descripcion</th>
        <th>Url</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[0]</td> <td>$p[1]</td> <td>$p[2]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
