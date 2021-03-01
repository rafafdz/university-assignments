<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_parque = $_POST["parque"];
  $id_parque = intval($id_parque);

 	$query = "SELECT nombre, largo, dificultad, duracion FROM sendero AS S, parquesendero AS PS WHERE PS.idp=$id_parque AND S.ids = PS.ids";

	$result = $db -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Largo</th>
        <th>Dificultad</th>
        <th>Duracion</th>
    </tr>
  <?php
	foreach ($dataCollected as $p) {
  		echo "<tr><td>$p[0]</td> <td>$p[1]</td> <td>$p[2]</td> <td>$p[3]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
