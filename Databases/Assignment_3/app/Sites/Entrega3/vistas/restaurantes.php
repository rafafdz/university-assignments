<?php include('../templates/header.html');   ?>

<body>

  <?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT * FROM restaurantes";
  $result = $db2 -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>

  <table>
    <tr>
      <th>Restaurantes</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[2]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="restaurantes_platos.php" method="post">
      <input type="hidden" name="restaurante" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar platos">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
