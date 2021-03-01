<?php include('../templates/header.html');   ?>

<body>
<?php
  require("../config/conexion.php");
  require("../utilities.php");

  $query = "SELECT U.uid, U.nombre, U.nacimiento, U.correo, U.nacionalidad
            FROM usuarios U, reservas RES
            WHERE RES.uid = U.uid AND
                   (SELECT HAB2.habid
                   FROM habitaciones HAB2, hoteles HOT
                   WHERE HAB2.hotid = HOT.hotid AND HOT.rid = 1
                   ORDER BY HAB2.precio
                   LIMIT 1) = RES.habid";

  $result = $db -> prepare($query);
  call_user_func_array('generate_table_from_query',
                        array($result, "ID Usuario", "Nombre",
                              "Nacimiento", "Correo", "Naconalidad"));
  ?>
<?php include('../templates/footer.html'); ?>
