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
        <h1>Restaurantes favoritos.</h1>
    </div>

    <section class="mt-4">
      <div class="container" id="resultados_senderos">
        <div class="row">
          <div class="col">
            <?php
              $user_id = $_SESSION["id"];

              $query = "SELECT r.restid, r.nombre, r.direccion, r.telefono, r.descripcion FROM restaurantes as r, favoritos as f WHERE f.uid='$user_id' AND r.restid = f.restid ORDER BY r.restid ASC";
              $result = $db2 -> prepare($query);
              $result -> execute();
              $dataCollected = $result -> fetchAll();
            ?>
            <table class='table'>
            <thead class='thead-dark'>
            <tr>
            <th scope='col'>ID</th>
            <th scope='col'>Nombre</th>
            <th scope='col'>Direccion</th>
            <th scope='col'>Telefono</th>
            <th scope='col'>Descripcion</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($dataCollected as $p):
                echo "<tr>";
                echo  "<th scope='row'>$p[0]</th>";
                echo  "<td>$p[1]</td>";
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
