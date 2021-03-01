<?php
// Include config file
require_once "../config/conexion.php";

// Define variables and initialize with empty values
$mail = $password = $confirm_password = $nombre = $fecha = $nacio = "";
$mail_err = $password_err = $confirm_password_err = $nombre_err = $fecha_err = $nacio_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["mail"]))){
        $mail_err = "Please enter a mail.";
    } else{
        // Prepare a select statement
        $sql = "SELECT idu FROM usuario WHERE correo = :mail";

        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":mail", $param_mail, PDO::PARAM_STR);

            // Set parameters
            $param_mail = trim($_POST["mail"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $mail_err = "This mail is already taken.";
                } else{
                    $mail = trim($_POST["mail"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate nombre
    if(empty(trim($_POST["nombre"]))){
        $nombre_err = "Please enter a name";
    }  else{
        $nombre = trim($_POST["nombre"]);
    }

    if(empty(trim($_POST["fecha"]))){
        $fecha_err = "Please enter a date";
    }  else{
        $fecha = trim($_POST["fecha"]);
    }

    if(empty(trim($_POST["nacio"]))){
        $nacio_err = "Please enter a nacionalidad";
    }  else{
        $nacio = trim($_POST["nacio"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($mail_err) && empty($password_err) && empty($confirm_password_err) && empty($name_err) && empty($fecha_err) && empty($nacio_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO usuario (idu, nombre, fecha_nac, correo, nacionalidad, password) VALUES (:id, :nombre, :fecha, :mail, :nacionalidad, :password)";

        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":mail", $param_mail, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $param_nombre, PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $param_fecha, PDO::PARAM_STR);
            $stmt->bindParam(":nacionalidad", $param_nacio, PDO::PARAM_STR);

            // Set parameters
            $param_mail = $mail;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            //$param_password = $password;
            $param_nombre = $nombre;
            $param_fecha = $fecha;
            $param_nacio = $nacio;

            $query = "SELECT MAX(idu) FROM usuario";

          	$result = $db -> prepare($query);
          	$result -> execute();
          	$idu = $result -> fetchAll();
            echo $idu[0][0];
            $param_id = intval($idu[0][0]) + 1;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{


                echo "\nPDOStatement::errorInfo():\n";
                $arr = $stmt->errorInfo();
                print_r($arr);
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                <span class="help-block"><?php echo $nombre_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($fecha_err)) ? 'has-error' : ''; ?>">
                <label>Fecha nacimiento</label>
                <input type="date" name="fecha" class="form-control" value="<?php echo $fecha; ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                <span class="help-block"><?php echo $fecha_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($nacio_err)) ? 'has-error' : ''; ?>">
                <label>Nacionalidad</label>
                <input type="text" name="nacio" class="form-control" value="<?php echo $nacio; ?>">
                <span class="help-block"><?php echo $nacio_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($mail_err)) ? 'has-error' : ''; ?>">
                <label>Mail</label>
                <input type="text" name="mail" class="form-control" value="<?php echo $mail; ?>">
                <span class="help-block"><?php echo $mail_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
