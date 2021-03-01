<?php include('../templates/header.html');   ?>

<body>

  <?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT U.nombre FROM Registro, (SELECT ids FROM Sendero WHERE largo = (SELECT MAX(largo) FROM Sendero)) AS sendero_largo, Usuario AS U WHERE Registro.idu = U.idu AND sendero_largo.ids = Registro.ids AND Registro.estado = 'en ruta';";
  $result = $db -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>

  <table>
    <tr>
      <th>ID Usuario</th>
    </tr>
  <?php
  foreach ($dataCollected as $p) {
    echo "<tr> <td>$p[0]</td> </tr>";
  }
  ?>
  </table>

<?php include('../templates/footer.html'); ?>
