<?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT COUNT(*), R.nombre 
  FROM parquesnacionales AS P, parqueregion AS PR, region AS R
  WHERE P.idp = PR.idp AND PR.idr = R.idr
  GROUP BY R.idr;";
  $result = $db -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo


  $data = array();
  foreach($dataCollected as $row){
    $new_arr = array($row["nombre"], $row["count"]);
    $data[] = $new_arr;
  }

  include('../templates/header.html');
  include('../templates/navbar_volver.html');

?>



<html>
    <head>
        <title>Graficos</title>
        <script src="https://d3js.org/d3.v5.min.js"></script>
        <link href="../c3-0.7.1/c3.css" rel="stylesheet" type="text/css" />
        <script src="../c3-0.7.1/c3.min.js"></script><!-- load jquery -->

    </head>
    <body>

        <h2 class="text-center">Proporciones de cantidad de parques nacionales por región</h2>
        <div id="chart"></div>
        <script>
            var dataArr = <?php echo json_encode($data, JSON_NUMERIC_CHECK); ?>;
            var chart = c3.generate({
            data: {
                // iris data from R
                columns: dataArr,
                type : 'pie',
                onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                }
            }); 
        </script>
    
    </body>
</html>