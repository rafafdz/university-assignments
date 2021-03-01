<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

	$cepa = strval($_POST["cepa_vino"]);
	$nombre = strval($_POST["nombre_tour"]);

 	$query = "SELECT V.nombre FROM Vino AS V, VinoTour AS VT, Tour AS T WHERE V.idvino = VT.idvino AND VT.eid = T.eid AND T.nombre = '$nombre' AND V.cepa = '$cepa';";
	$result = $db -> prepare($query);
	$result -> execute();
	$vinos = $result -> fetchAll();
  ?>

	<table>
    <tr>
      <th>Nombre</th>
    </tr>
  <?php
	foreach ($vinos as $vino) {
  		echo "<tr> <td>$vino[0]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
