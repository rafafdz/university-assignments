<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $nombre = $_POST["nombre"];
  $nombre = strval($nombre);

 	$query = "SELECT * FROM parquesnacionales WHERE LOWER(parquesnacionales.nombre) LIKE LOWER(?)";

	$result = $db -> prepare($query);
	$result -> execute(array("%$nombre%"));
	$dataCollected = $result -> fetchAll();
  ?>
  <h3> Parques nacionales </h3>
  <table>
    <tr>
      <th>Nombre</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[1]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="parque_elegido.php" method="post">
      <input type="hidden" name="parque" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar datos del parque">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php
    #Llama a conexión, crea el objeto PDO y obtiene la variable $db

   	$query = "SELECT * FROM sendero WHERE LOWER(sendero.nombre) LIKE LOWER(?)";

  	$result = $db -> prepare($query);
  	$result -> execute(array("%$nombre%"));
  	$dataCollected = $result -> fetchAll();
    ?>
    <h3> Senderos </h3>
    <table>
      <tr>
        <th>Nombre</th>
      </tr>
    </table>

    <?php foreach ($dataCollected as $p): ?>
      <?php echo "<tr> <td>$p[1]</td> </tr>"; ?>
      <?php $id = $p[0] ?>
      <form action="sendero_elegido.php" method="post">
        <input type="hidden" name="sendero" value="<?php echo htmlspecialchars($id); ?>" />
        <input type="submit" value="Mostrar datos del sendero">
      </form>
      <br>
      <br>
      <br>
    <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
