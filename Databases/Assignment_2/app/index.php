<?php include('templates/header.html');?>

<body>

    <h1>Servicio de Hoteleria, Restaurantes, y Agencias de turismo</h1>
    <p>Consultas entrega 2:</p>
    <br />

    <h3>Consulta de platos según ID de región:</h3>
    <form action="consultas/platos_dado_rid.php" method="post">
        Region id:
        <input type="int" step="1" pattern="\d+" required="required" name="region_id">
        <input type="submit" value="Buscar">
    </form>
    <br />

    <h3>Consulta de Habitaciones de Hoteles según cantidad de estrellas:</h3>
    <form action="consultas/habitaciones_dado_estrella.php" method="post">
        Numero de estrellas:
        <input type="int" step="1" pattern="\d+" required="required" name="estrellas">
        <br /><br />
        <input type="submit" value="Buscar">
    </form>
    <br />

    <h3>Consulta de Reservas segun ID de usuario y fechas:</h3>
    <form action="consultas/reservas_dado_uid_fechas.php" method="post">
        ID de usuario:
        <input type="int" step="1" pattern="\d+" required="required" name="uid">
        <br />
        Fecha de inicio:
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        <br />
        Fecha de termino:
        <input type="date" id="fecha_termino" name="fecha_termino" required>
        <!-- <span class="validity"></span> -->
        <br /><br />
        <input type="submit" value="Buscar">
    </form>
    <br />

    <h3>Consulta de Tours de Agencias de sólo una región:</h3>
    <form action="consultas/tours_agencias_una_region.php" method="post">
        <br />
        <input type="submit" value="Buscar">
    </form>
    <br />

    <h3>Consulta de Habitacion más reservada por región:</h3>
    <form action="consultas/habitacion_mas_reservada_region.php" method="post">
        <br />
        <input type="submit" value="Buscar">
    </form>
    <br />

    <h3>Consulta de Usuarios arrendatarios de Habitación más barata en II region:</h3>
    <form action="consultas/usuarios_habitacion_mas_barata_segunda_region.php" method="post">
        <br />
        <input type="submit" value="Buscar">
    </form>
    <br />


    <h3>Consulta de Usuarios dado ID de reserva:</h3>
    <form action="consultas/usuarios_dado_resvid.php" method="post">
        ID de reserva:
        <input type="int" step="1" pattern="\d+" required="required" name="resvid">
        <br /><br />
        <input type="submit" value="Buscar">
    </form>
    <br />

    <h3>Consulta de i-esima habitación más cara:</h3>
    <form action="consultas/habitacion_i_mas_cara.php" method="post">
        i:
        <input type="int" step="1" pattern="\d+" required="required" name="i">
        <br /><br />
        <input type="submit" value="Buscar">
    </form>
    <br />


</body>

</html>
