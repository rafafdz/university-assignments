<?php
  require("../config/conexion.php"); #Llama a conexión, crea el objeto PDO y obtiene la variable $db

  $query = "SELECT ROUND(AVG(tours.precio)), regiones.nombre
  FROM regiones, agencias, agencias_regiones, tours
  WHERE regiones.rid = agencias_regiones.rid AND
  agencias_regiones.aid = agencias.aid AND
  tours.aid = agencias.aid
  GROUP BY regiones.rid;"
  ;
  $result = $db2 -> prepare($query);
  $result -> execute();
  $dataCollected = $result -> fetchAll(); #Obtiene todos los resultados de la consulta en forma de un arreglo

  $cuenta[] = 'Precio de Tours';
  $rid[] = 'nombre';
  foreach($dataCollected as $row){
      $cuenta[] = $row['round'];
      $rid[] = $row['nombre'];
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
        <h2 class="text-center">Precio promedio de Tours por región</h2>
        <div id="chart"></div>
        <script>
            var xAxisArr = <?php echo json_encode($rid); ?>;
            var dataArr = <?php echo json_encode($cuenta, JSON_NUMERIC_CHECK); ?>;
            var chart = c3.generate({
            bindto: '#chart',
            data: {
                x: 'nombre',
                columns: [
                    xAxisArr,
                    dataArr
                ],
                type: "bar"
            },
            axis: {
                    x: {
                        type: 'category',
                        categories: xAxisArr
                    },
                    y: {
                        }
            },
            legend:{
                hide: true
            }
            });
        </script>
    </body>
</html>