<?php include('../templates/header.html');   ?>

<body>

  <?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT Sendero.nombre FROM (SELECT ids, COUNT(*) AS total FROM Registro WHERE estado = 'perdido' GROUP BY ids) AS senderos_perdidos, Sendero WHERE Sendero.ids = senderos_perdidos.ids AND senderos_perdidos.total = (SELECT MAX(total) FROM (SELECT ids, COUNT(*) AS total FROM Registro WHERE estado = 'perdido' GROUP BY ids) AS q);";
  $result = $db -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>

  <table>
    <tr>
      <th>Nombre</th>
    </tr>
  <?php
  foreach ($dataCollected as $p) {
    echo "<tr> <td>$p[0]</td> </tr>";
  }
  ?>
  </table>

<?php include('../templates/footer.html'); ?>
