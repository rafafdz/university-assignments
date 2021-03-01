<?php include('../templates/header.html');   ?>

<body>

  <?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT * FROM tour";
  $result = $db -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>

  <table>
    <tr>
      <th>Nombre |</th>
      <th>Precio</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[1]</td> | <td>$p[2]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="enoturismo_vinas.php" method="post">
      <input type="hidden" name="tour" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar vinas de el tour">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
