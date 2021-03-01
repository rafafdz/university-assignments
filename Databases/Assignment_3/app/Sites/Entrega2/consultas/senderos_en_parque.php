<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_parque = $_POST["id_parque"];
  $id_parque = intval($id_parque);

 	$query = "SELECT PN.nombre, COUNT(S.ids), SUM(S.largo) FROM ParquesNacionales AS PN, Sendero AS S, ParqueSendero AS PS WHERE PN.idp = PS.idp AND S.ids = PS.ids AND PN.idp = $id_parque GROUP BY PN.nombre;";

	$result = $db -> prepare($query);
	$result -> execute();
	$senderos = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
        <th>Cantidad de senderos</th>
        <th>Total de kilómetros</th>
    </tr>
  <?php
	foreach ($senderos as $p) {
  		echo "<tr><td>$p[0]</td> <td>$p[1]</td> <td>$p[2]</td> </tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
