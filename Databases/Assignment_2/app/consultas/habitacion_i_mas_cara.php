<?php include('../templates/header.html');   ?>

<body>
<?php
  require("../config/conexion.php");
  require("../utilities.php");

  $i = $_POST["i"];
  $i = intval($i) - 1; // Ajuste cardinal

  $query = "SELECT HAB1.habid, HAB1.hotid, HAB1.nombre, HAB1.precio
            FROM habitaciones HAB1
            WHERE (SELECT DISTINCT HAB2.precio
                   FROM habitaciones HAB2
                   ORDER BY HAB2.precio DESC
                   LIMIT 1 OFFSET $i) = HAB1.precio";

  $result = $db -> prepare($query);
  call_user_func_array('generate_table_from_query',
                        array($result, "ID Hab.", "ID Hot.",
                              "Nombre", "Precio"));
  ?>
<?php include('../templates/footer.html'); ?>
