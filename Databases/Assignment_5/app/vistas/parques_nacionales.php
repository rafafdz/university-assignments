<?php include('../templates/header.html');   ?>

<body>

  <?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT * FROM parquesnacionales";
  $result = $db -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>

  <table>
    <tr>
      <th>Nombre parque |</th>
      <th>hectareas |</th>
      <th>descripcion |</th>
      <th>tarifa</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[1]</td> | <td>$p[2]</td> | <td>$p[3]</td> | <td>$p[4]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="parque_senderos.php" method="post">
      <input type="hidden" name="parque" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar senderos">
    </form>
    <form action="parque_atractivos.php" method="post">
      <input type="hidden" name="parque" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar atractivos">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
