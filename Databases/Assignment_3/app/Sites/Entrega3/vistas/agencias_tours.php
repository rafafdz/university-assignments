<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_agencia = $_POST["agencia"];
  $id_agencia = intval($id_agencia);

 	$query = "SELECT descripcion, precio FROM tours WHERE aid=$id_agencia";

	$result = $db2 -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>descripcion</th>
        <th>precio</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[0]</td> <td>$p[1]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
