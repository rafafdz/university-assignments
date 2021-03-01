<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_usuario = $_POST["id_usuario"];
  $id_usuario = intval($id_usuario);

 	$query = "SELECT S.nombre FROM Sendero AS S, Registro AS R, Usuario AS U WHERE R.idu = $id_usuario AND S.ids = R.ids AND R.idu = U.idu;";

	$result = $db -> prepare($query);
	$result -> execute();
	$senderos = $result -> fetchAll();
  ?>

  <table>
    <tr>
        <th>Nombres de senderos</th>
    </tr>
  <?php
	foreach ($senderos as $p) {
  		echo "<tr><td>$p[0]</td></tr>";
	}
  ?>
	</table>

<?php include('../templates/footer.html'); ?>
