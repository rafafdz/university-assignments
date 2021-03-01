<?php include('../templates/header.html');   ?>

<body>

  <?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT * FROM hoteles";
  $result = $db2 -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>

  <table>
    <tr>
      <th>Hoteles</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[2]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="hotel_habitaciones.php" method="post">
      <input type="hidden" name="hotel" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar habitaciones">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
