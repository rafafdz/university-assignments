<?php include('../templates/header.html');   ?>

<body>
<?php

  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

    $query = "SELECT Vina.nombre, V.* FROM (SELECT * FROM Vino WHERE precio = (SELECT MAX(precio) FROM VINO)) AS V, VinaVino AS VV, Vina WHERE V.idvino = VV.idvino AND VV.idv = Vina.idv;";
    $result = $db -> prepare($query);
    $result -> execute();
    $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo
  ?>
    <table>
    <tr>
      <th>Nombre Viña</th>
      <th>ID Vino</th>
      <th>Nombre Vino</th>
      <th>Descripcion</th>
      <th>Cepa</th>
      <th>Precio</th>
    </tr>
  <?php
    foreach ($dataCollected as $p) {
        echo "<tr> <td>$p[0]</td> <td>$p[1]</td> <td>$p[2]</td> <td>$p[3]</td> <td>$p[4]</td> <td>$p[5]</td> </tr>";
    }
  ?>
    </table>

<?php include('../templates/footer.html'); ?>
