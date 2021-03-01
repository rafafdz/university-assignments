<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_vina = $_POST["vina"];
  $id_vina = intval($id_vina);

 	$query = "SELECT nombre, descripcion, cepa, precio FROM vino, vinavino WHERE vinavino.idv=$id_vina AND vino.idvino=vinavino.idvino";

	$result = $db -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Descripcion</th>
        <th>Cepa</th>
        <th>Precio</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[0]</td> <td>$p[1]</td> <td>$p[2]</td> <td>$p[3]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
