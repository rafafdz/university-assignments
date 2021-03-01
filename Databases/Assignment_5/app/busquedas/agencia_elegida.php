<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_agencia = $_POST["agencia"];
  $id_agencia = intval($id_agencia);

 	$query = "SELECT * FROM agencias WHERE aid=$id_agencia";

	$result = $db2 -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Direccion</th>
        <th>Telefono</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[1]| </td> <td>$p[2]| </td> <td>$p[3] </td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
