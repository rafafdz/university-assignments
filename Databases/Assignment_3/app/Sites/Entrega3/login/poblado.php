//CREATE TABLE users (
//    id SERIAL PRIMARY KEY,
//    username VARCHAR(50) NOT NULL UNIQUE,
//    password VARCHAR(255) NOT NULL, ACUERDATE AGREGAR NOT NULL DE PASSWORD
//);
<?php
require_once "../config/conexion.php";
$csvFile = fopen("../datos/usuario.csv", "r");
//$data = [];
while (($row = fgetcsv($csvFile, 0, ",")) !== FALSE) {
    //$data[] = str_getcsv($line);
    $sql = 'UPDATE usuario SET password = :password WHERE correo = :correo';
    $stmt = $db->prepare($sql);

    $pass_sin_hash = trim($row[3]);
    $password = password_hash($pass_sin_hash, PASSWORD_DEFAULT);
    $idu = $row[3];

    // pass values to the statement
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $idu, PDO::PARAM_STR);

    // execute the insert statement
    $stmt->execute();
}
?>
