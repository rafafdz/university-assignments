<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");
  require("../utilities.php");

  $uid = $_POST["uid"];
  $uid = intval($uid);

  $fecha_inicio = $_POST["fecha_inicio"];
  $fecha_termino = $_POST["fecha_termino"];

  $query = "SELECT Res.resvid, Res.habid, Res.fecha_inicio, Res.fecha_fin
            FROM Reservas as Res
            WHERE Res.uid=$uid AND Res.fecha_inicio > '$fecha_inicio'
                  AND Res.fecha_fin < '$fecha_termino'";

  $result = $db -> prepare($query);
  call_user_func_array('generate_table_from_query',
                        array($result, "ID Reserva", "ID Habitacion",
                              "Fecha inicio", "Fecha termino"));
  ?>
<?php include('../templates/footer.html'); ?>
