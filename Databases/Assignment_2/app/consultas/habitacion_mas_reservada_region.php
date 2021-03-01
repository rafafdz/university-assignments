<?php include('../templates/header.html');   ?>

<body>
<?php
  require("../config/conexion.php");
  require("../utilities.php");


  // La vista usada en el query es la siguiente:
  // CREATE VIEW Reservas_Habitacion AS
  // SELECT R.habid as habid, COUNT(*) as reservas
  // FROM reservas as R
  // GROUP BY R.habid

  $query = "SELECT R.rid, R.nombre, HAB.habid, HAB.nombre, HAB.precio
            FROM regiones R, habitaciones HAB
            WHERE (SELECT HAB2.habid
                   FROM habitaciones HAB2, hoteles HOT, reservas_habitacion RH
                   WHERE HAB2.hotid = HOT.hotid AND RH.habid = HAB2.habid AND
                         HOT.rid = R.rid
                   ORDER BY RH.reservas DESC
                   LIMIT 1) = HAB.habid
            ORDER BY R.rid";

  $result = $db -> prepare($query);
  call_user_func_array('generate_table_from_query',
                        array($result, "ID Region", "Nombre Region",
                              "ID Hab.", "Nombre Hab.", "Precio"));
  ?>
<?php include('../templates/footer.html'); ?>
