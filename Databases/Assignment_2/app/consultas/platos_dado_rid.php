<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");
  require("../utilities.php");

  $region_id = $_POST["region_id"];
  $region_id = intval($region_id);

  $query = "SELECT DISTINCT P.nombre, P.descripcion, P.precio
            FROM platos as P, restaurantes as Res, regiones as Reg
            WHERE Res.restid=P.restid AND Reg.rid=$region_id AND Reg.rid=Res.rid";

  $result = $db -> prepare($query);
  //$result -> execute();
  //$platos = $result -> fetchAll();
  call_user_func_array('generate_table_from_query',
                        array($result, "Nombre", "Descripcion", "Precio"));
  ?>

<?php include('../templates/footer.html'); ?>
