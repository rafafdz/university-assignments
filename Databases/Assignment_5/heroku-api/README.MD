Entrega 4 Base de Datos

Requests que acepta la API:
- Ruta: "/mensaje/<int:id>" recibe un id de mensaje y retorna el mensaje
- Ruta: "/users/<int:uid>" recibe uid de usuario y retorna el usuario y los mensajes enviados por él
- Ruta: "/mensaje_users" recibe un diccionario con las llaves "id_usuario1" y "id_usuario2" y retorna todos los mensajes entre ellos dos
- Ruta: "/mensaje/<int:id>", Metodo = "DELETE" recibe un id de mensaje y lo borra
- Ruta: "/mensaje", Metodo = "POST" recibe un diccionario con las llaves 'message', 'sender', 'receptant', 'lat', 'long' y lo crea si es posible
- Ruta: "/filtro_mensajes" recibe un diccionario con las llaves "obligatorias", "opcionales", "prohibidas" en las cuales se pone una lista de palabras por filtrar. Retornando todos los mensajes que cumplan con el filtro
- Ruta: "/filtro_mensajes/<int:idu>" recibe un diccionario con las llaves "obligatorias", "opcionales", "prohibidas" en las cuales se pone una lista de palabras por filtrar y un id de usuario. Retornando todos los mensajes que el usuario a enviado y que cumplan con el filtro