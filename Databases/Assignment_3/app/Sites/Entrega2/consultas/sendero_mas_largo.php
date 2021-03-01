<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $i_esimo = $_POST["i_esimo"];
  $i_esimo = intval($i_esimo);

 	$query = "SELECT S1.nombre FROM Sendero as S1 WHERE (SELECT COUNT(*) FROM Sendero AS S2 WHERE S2.largo > S1.largo) = $i_esimo - 1;";

	$result = $db -> prepare($query);
	$result -> execute();
	$senderos = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombre</th>
    </tr>
  <?php
	foreach ($senderos as $p) {
  		echo "<tr><td>$p[0]</td></tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
