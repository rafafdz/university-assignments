<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");
  require("../utilities.php");

  $query = "SELECT T.tid, T.descripcion, T.precio, A.aid, A.nombre
            FROM tours as T, agencias as A
            WHERE T.aid = A.aid AND
                 (SELECT COUNT(DISTINCT AR.rid)
                  FROM agencias_regiones as AR
                  WHERE AR.aid = A.aid) = 1";

  $result = $db -> prepare($query);
  call_user_func_array('generate_table_from_query',
                        array($result, "ID Tour", "Descripcion",
                              "Precio", "ID agencia", "Nombre Agencia"));
  ?>
<?php include('../templates/footer.html'); ?>
