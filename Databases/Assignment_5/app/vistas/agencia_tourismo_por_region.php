<?php include('../templates/header.html');   ?>

<body>

  <?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT * FROM regiones";
  $result = $db2 -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>

  <table>
    <tr>
      <th>Regiones</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[1]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="agencias_turismo.php" method="post">
      <input type="hidden" name="region" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar agencias">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
