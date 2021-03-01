<?php
include('../templates/header.html');
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<head>
    <title>Perfil</title>
    <style type="text/css">
        body {
            font: 14px sans-serif;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <form class="form-inline">
            <a href="reset-password.php" class="btn btn-sm btn-primary mr-2">Resetear contraseña</a>
            <a href="logout.php" class="btn btn-sm btn-danger" type="button">Cerrar Sesión</a>
        </form>
    </nav>
    <div class="page-header">
        <!-- <?php echo http_build_query($_SESSION, '', ', ');?> -->
        <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>. Bienvenido a nuestro sitio.</h1>
    </div>
    <p>
        <a href="../index.php" class="btn btn-warning" type="button">Ir al menu principal</a>
    </p>

    <section class="mt-4">
        <div class="container" id="mis_cards">
            <div class="row">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Reservas Habitaciones
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Historial Reservas</h5>
                            <p class="card-text">Obten informacion de las reservas de habitaciones realizadas.</p>
                            <a href="../historial/historial_reservas.php" class="btn btn-primary mt-auto">Ver reservas</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Senderos
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Historial Senderos</h5>
                            <p class="card-text">Obten informacion de los senderos realizados.</p>
                            <a href="../historial/historial_senderos.php" class="btn btn-primary mt-auto">Ver senderos</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Restaurantes favoritos
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Restaurantes favoritos</h5>
                            <p class="card-text">Ve tus restaurantes favoritos</p>
                            <a href="../historial/restaurantes_favoritos.php" class="btn btn-primary mt-auto">Ver favoritos</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Mensajes
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Mensajes recibidos</h5>
                            <p class="card-text">Ve tus mensajes recibidos</p>
                            <a href="../mensajes_recibidos.php" class="btn btn-primary mt-auto">Ver mensajes</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Mensajes
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Mensajes enviados</h5>
                            <p class="card-text">Ve tus mensajes enviados</p>
                            <a href="../mensajes_enviados.php" class="btn btn-primary mt-auto">Ver mensajes</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Mensajes
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Enviar Mensaje</h5>
                            <p class="card-text">Envia un mensaje</p>
                            <a href="../enviar_mensaje.php" class="btn btn-primary mt-auto">Enviar Mensaje</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Mensajes
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Buscar mensajes con filtros</h5>
                            <p class="card-text">Filtrar mensajes</p>
                            <a href="../busqueda_texto.php" class="btn btn-primary mt-auto">Filtrar</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Mensajes
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Buscar mensajes con filtros e id de usuario</h5>
                            <p class="card-text">Filtrar mensajes con id de usuario</p>
                            <a href="../busqueda_texto_id.php" class="btn btn-primary mt-auto">Filtrar</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Mensajes
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Ver la ubicacion de donde haz mandado tus mensajes</h5>
                            <p class="card-text">Ver ubicacion</p>
                            <a href="../mensajes_entre_fechas.php" class="btn btn-primary mt-auto">Ver ubicacion</a>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <br/>
    <hr/>
    <div>
        <h2> Estadísticas </h2>
    </div>
    <section class="mt-4">
        <div class="container" id="mas_cards">
            <div class="row">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Estadísticas
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Ver proporciones de parques nacionales por región </h5>
                            <a href="../graficos/torta_parques.php" class="btn btn-primary mt-auto">Ver Parques</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Estadísticas
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Ver cantidad de habitaciones reservadas por región </h5>
                            <a href="../graficos/grafico_habitaciones.php" class="btn btn-primary mt-auto">Ver Habitaciones</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Estadísticas
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Ver número de cepas de vino por región </h5>
                            <a href="../graficos/grafico_cepas.php" class="btn btn-primary mt-auto">Ver Cepas</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            Estadísticas
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Ver precios promedio de tours por región </h5>
                            <a href="../graficos/grafico_promedio_tours.php" class="btn btn-primary mt-auto">Ver Precios</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

</body>

</html>