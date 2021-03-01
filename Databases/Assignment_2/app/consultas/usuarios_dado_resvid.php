<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");
  require("../utilities.php");

  $resvid = $_POST["resvid"];
  $resvid = intval($resvid);

  $query = "SELECT U.nombre, H.precio * (R.fecha_fin - R.fecha_inicio)
            FROM usuarios U, reservas R, habitaciones H
            WHERE U.uid = R.uid AND R.habid = H.habid AND R.resvid=$resvid";

  $result = $db -> prepare($query);
  call_user_func_array('generate_table_from_query',
                        array($result, "Nombre del usuario",
                        "Precio a pagar"));
  ?>
<?php include('../templates/footer.html'); ?>
