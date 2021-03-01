<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $nombre_agencia = $_POST["nombre"];
  $nombre_agencia = strval($nombre_agencia);

 	$query = "SELECT * FROM agencias WHERE LOWER(agencias.nombre) LIKE LOWER(?)";

	$result = $db2 -> prepare($query);
	$result -> execute(array("%$nombre_agencia%"));
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
      <th>Nombre</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[1]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="agencia_elegida.php" method="post">
      <input type="hidden" name="agencia" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar datos de la agencia">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
