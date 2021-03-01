<?php include('templates/header.html');   ?>

<body>

  <h1>Parques Nacionales y Enoturismo</h1>
  <p>Aquí podran encontrar información sobre Parques Nacionales y Enoturismo.</p>

  <br>

  <h3>Consulta por senderos realizado por un usuario:</h3>

  <form action="consultas/senderos_por_usuario.php" method="post">
    ID usuario:
    <input type="text" name="id_usuario">
    <br/>
    <br/>
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

  <h3>Consulta Viñas y Parques de la VI Región:</h3>

  <form action="consultas/parques_vinas_6_region.php" method="post">
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

  <h3>Consulta de Vinos por cepa y tour:</h3>

  <form action="consultas/vino_por_cepa_tour.php" method="post">
    Cepa:
    <input type="text" name="cepa_vino">
    <br/>
    Nombre del Tour:
    <input type="text" name="nombre_tour">
    <br/><br/>
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

  <h3>Consulta Viña con el vino más caro:</h3>

  <form action="consultas/vina_con_vino_mas_caro.php" method="post">
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

  <h3>Consulta todos los usuarios que están "en ruta" en el sendero mas largo:</h3>

  <form action="consultas/en_ruta_mas_larga.php" method="post">
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

  <h3>Consulta sendero con más gente perdida:</h3>

  <form action="consultas/sendero_con_mas_perdidos.php" method="post">
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

  <h3>Consulta por senderos en un Parque Nacional:</h3>

  <form action="consultas/senderos_en_parque.php" method="post">
    ID Parque:
    <input type="text" name="id_parque">
    <br/>
    <br/>
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

  <h3>Consulta por el i-ésimo sendero más largo:</h3>

  <form action="consultas/sendero_mas_largo.php" method="post">
    i:
    <input type="text" name="i_esimo">
    <br/>
    <br/>
    <input type="submit" value="Buscar">
  </form>
  <br>
  <br>
  <br>

</body>
</html>
