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
    <?php include('../templates/navbar_volver.html'); ?>
    <div class="page-header">
        <h1>Historial de senderos.</h1>
    </div>

    <section class="mt-4">
      <div class="container" id="resultados_senderos">
        <div class="row">
          <div class="col">
            <?php
              $user_id = $_SESSION["id"];

              $query = "SELECT S.ids,S.nombre,R.estado FROM registro as R, sendero as S WHERE R.idu='$user_id' AND S.ids = R.ids; ORDER BY S.ids ASC";
              $result = $db -> prepare($query);
              $result -> execute();
              $dataCollected = $result -> fetchAll();
            ?>
            <table class='table'>
            <thead class='thead-dark'>
            <tr>
            <th scope='col'>ID</th>
            <th scope='col'>Nombre</th>
            <th scope='col'>Estado</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($dataCollected as $p):
                echo "<tr>";
                echo  "<th scope='row'>$p[0]</th>";
                echo  "<td>$p[1]</td>";
                echo  "<td>$p[2]</td>";
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