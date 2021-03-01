<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");
  require("../utilities.php");

  $estrellas = $_POST["estrellas"];
  $estrellas = intval($estrellas);

  $query = "SELECT DISTINCT H.habid, H.nombre, Ho.nombre
            FROM Habitaciones as H, Hoteles as Ho
            WHERE Ho.estrellas=$estrellas AND H.hotid=Ho.hotid;";

  $result = $db -> prepare($query);
  call_user_func_array('generate_table_from_query',
                        array($result, "ID Habitacion",
                        "Nombre Habitacion", "Nombre Hotel"));
  ?>
<?php include('../templates/footer.html'); ?>
