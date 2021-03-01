<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $nombre_vina = $_POST["nombre"];
  $nombre_vina = strval($nombre_vina);

 	$query = "SELECT * FROM vina WHERE LOWER(vina.nombre) LIKE LOWER(?)";

	$result = $db -> prepare($query);
	$result -> execute(array("%$nombre_vina%"));
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
    <form action="vina_elegida.php" method="post">
      <input type="hidden" name="vina" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar datos de la vina">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
