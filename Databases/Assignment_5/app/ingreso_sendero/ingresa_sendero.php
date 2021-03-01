<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}
?>

<?php include('../templates/header.html'); ?>

<body>
    <?php
    #Llama a conexión, crea el objeto PDO y obtiene la variable $db
    require("../config/conexion.php");

    $id_parque = $_POST["idp"];
    $id_parque = explode (" - ", $id_parque);
    $id_parque = strval($id_parque[0]);


    $query = "SELECT sendero.ids, sendero.nombre, sendero.largo, sendero.dificultad, sendero.duracion  FROM parquesendero as ps, sendero WHERE ps.idp='$id_parque' AND ps.ids = sendero.ids;";
    //$result = $db -> prepare($query);
    //$result -> execute();
    //$arr = $result->errorInfo();
    //print_r($arr);
    ?>

    <h3>Seleccione el sendero:</h3>

    <form action="confirma_ingreso.php" method="post">
        Seleccione el sendero:
        <input list='lista_send' name='ids' id='ids' style='width: 440px;'></label>
        <datalist id='lista_send'>
        <?php
        foreach ($db->query($query) as $row) {
            echo  "<option value='$row[ids] - $row[nombre] - $row[largo] - $row[dificultad] - $row[duracion]'/>";
        }
        ?>
        </datalist>
        <br/>
        <br/>
        Seleccione la fecha de ingreso:
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        <br/>
        <br/>
        Seleccione la fecha de salida:
        <input type="date" id="fecha_termino" name="fecha_termino" min="fecha_inicio" required>
        <input type="submit" value="Ingresar">
    </form>

<?php include('../templates/footer.html'); ?>
