<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_region = $_POST["region"];
  $id_region = intval($id_region);

 	$query = "SELECT A.aid, A.nombre, A.direccion, A.telefono FROM agencias as A, agencias_regiones as R WHERE R.rid=$id_region AND R.aid=A.aid";

	$result = $db2 -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
      <th>Nombre agencia</th>
      <th>Direccion</th>
      <th>Telefono</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[1]</td> | <td>$p[2]</td> | <td>$p[3]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="agencias_tours.php" method="post">
      <input type="hidden" name="agencia" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar tours">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

<?php include('../templates/footer.html'); ?>
