<?php
include('../templates/header.html');
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require("../config/conexion.php");
?>
<head>
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <form class="form-inline">
            <a href="../login/welcome.php" class="btn btn-sm btn-primary mr-2" type="button">Volver</a>
        </form>
    </nav>

    <div class="page-header">
        <h1>Historial de reservas hasta la fecha: <?php echo date("Y/m/d"); ?></h1>
    </div>
    <section class="mt-4">
      <div class="container" id="resultados">
        <div class="row">
          <div class="col">
            <?php
              $user_id = $_SESSION["id"];
              $fecha_hoy = date("Y/m/d");
              $query = "SELECT Hab.nombre,Hab.habid,Res.fecha_inicio,Res.fecha_fin,(Res.fecha_fin - Res.fecha_inicio)*Hab.precio AS costo_total  FROM reservas as Res, habitaciones as Hab WHERE Res.habid=Hab.habid AND Res.uid='$user_id' AND fecha_fin < $fecha_hoy ORDER BY Res.habid DESC;";
              $result = $db2 -> prepare($query);
              $result -> execute();
              $dataCollected = $result -> fetchAll();
            ?>
            <table class='table'>
            <thead class='thead-dark'>
            <tr>
            <th scope='col'>ID</th>
            <th scope='col'>Nombre</th>
            <th scope='col'>Fecha inicio</th>
            <th scope='col'>Fecha termino</th>
            <th scope='col'>Monto pagado</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($dataCollected as $p):
                echo "<tr>";
                echo  "<th scope='row'>$p[1]</th>";
                echo  "<td>$p[0]</td>";
                echo  "<td>$p[2]</td>";
                echo  "<td>$p[3]</td>";
                echo  "<td>$p[4]</td>";
                echo "</tr>";
            endforeach;
            ?>
            </tbody>
            </table>                   
          </div>
        </div>
      </div>
    </section>
</body>
</html>