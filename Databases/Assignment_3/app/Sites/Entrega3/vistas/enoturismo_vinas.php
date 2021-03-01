<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $id_tour = $_POST["tour"];
  $id_tour = intval($id_tour);

 	$query = "SELECT * FROM vina AS V, vinatour AS VT WHERE VT.eid=$id_tour AND V.idv=VT.idv";

	$result = $db -> prepare($query);
	$result -> execute();
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
      <th>Nombre |</th>
      <th>Telefono |</th>
      <th>Descripcion</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[1]</td> | <td>$p[2]</td> | <td>$p[3]</td> | </tr>"; ?>
    <?php $id_vina = $p[0] ?>
    <form action="enoturismo_vinas_vinos.php" method="post">
      <input type="hidden" name="vina" value="<?php echo htmlspecialchars($id_vina); ?>" />
      <input type="hidden" name="tour" value="<?php echo htmlspecialchars($id_tour); ?>" />
      <input type="submit" value="Mostrar vinos de esta vina presentes en el tour">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
