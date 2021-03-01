from flask import Flask, render_template, request, abort, json
from pymongo import MongoClient, TEXT
import pandas as pd
import matplotlib.pyplot as plt
import os
import atexit
import subprocess
import datetime

USER_KEYS = ['id_usuario1', 'id_usuario2']
MESSAGE_KEYS = ['message', 'sender', 'receptant', 'lat', 'long']
FILTER_KEYS = ["obligatorias", "opcionales", "prohibidas"]
REGISTER_KEYS = ["idu", "nombre", "fecha_nac",
                  "correo", "nacionalidad", "password"]
              
CHANGE_PWD_KEYS = ["idu", "old_password", "new_password"]

FECHA = ["fecha1", "fecha2"]

"""
# Levantamos el servidor de mongo. Esto no es necesario, puede abrir
# una terminal y correr mongod. El DEVNULL hace que no vemos el output
mongod = subprocess.Popen('mongod', stdout=subprocess.DEVNULL)
# Nos aseguramos que cuando el programa termine, mongod no quede corriendo
atexit.register(mongod.kill)
"""
uri = "mongodb://grupo29:grupo29@146.155.13.149/grupo29?authSource=admin"
client = MongoClient(uri)
# Utilizamos la base de datos 'entidades'
db = client['grupo29']
# Seleccionamos la colección de usuarios
usuarios = db.users
mensajes = db.mensajes

# Iniciamos la aplicación de flask
app = Flask(__name__)


@app.route("/")
def home():
    return "<h1>HELLO</h1>"

# Mapeamos esta función a la ruta '/plot' con el método get.


@app.route("/mensaje/<int:id>")
def get_mensaje(id):
    """
    Retorna un mensaje dado un id de mensaje
    """
    msg = list(mensajes.find({"id": id}, {"_id": 0}))
    # Omitir el _id porque no es json serializable
    if not msg:
        return "no existe usuario con ese id"
    return json.jsonify(msg[0])


@app.route("/users/<int:uid>")
def get_user(uid):
    """
    Retorna un usuario y sus mensajes enviados dado un id de usuario
    """
    users = list(usuarios.find({"idu": uid}, {"_id": 0}))
    mensajes_usuario = list(mensajes.find({"sender": uid}, {"_id": 0}))
    dic = {"usuario": users[0], "mensajes": mensajes_usuario}
    return json.jsonify(dic)

@app.route("/user_recibidos/<int:uid>")
def get_user_recibidos(uid):
    """
    Retorna un usuario y sus mensajes enviados dado un id de usuario
    """
    users = list(usuarios.find({"idu": uid}, {"_id": 0}))
    mensajes_usuario = list(mensajes.find({"receptant": uid}, {"_id": 0}))
    dic = {"usuario": users[0], "mensajes": mensajes_usuario}
    return json.jsonify(dic)


@app.route("/mensaje_users")
def get_mensaje_users():
    """
    Retorna todos los mensajes entre 2 usuarios dados sus 2 id
    """
    
    key1, key2 = USER_KEYS
    
    if not key1 in request.json or not key2 in request.json:
        return json.jsonify([])
    
    usuario1 = request.json[key1]
    usuario2 = request.json[key2]
    
    query = {"$or": [{"sender": usuario1,
                      "receptant": usuario2},
                     {"sender": usuario2, 
                      "receptant": usuario1}]}
    
    mensaje_users = list(mensajes.find(query, {"_id": 0}))
    return json.jsonify(mensaje_users)


@app.route('/mensaje/<int:id>', methods=['DELETE'])
def delete_mensaje(id):
    '''
    Elimina un mensaje de la db.
    Se requiere llave id
    '''

    # esto borra el primer resultado. si hay mas, no los borra
    mensajes.delete_one({"id": id})

    message = f'Mensaje con id={id} ha sido eliminado.'

    # Retorno el texto plano de un json
    return json.jsonify({'result': 'success', 'message': message})


@app.route("/mensaje", methods=['POST'])
def create_message():
    '''
    Crea un nuevo mensaje desde un usuario a otro
    '''
    # Si los parámetros son enviados con una request de tipo application/json:
    data = {key: request.json[key] for key in MESSAGE_KEYS}

    # Se genera el id
    number = list(mensajes.find({}, {"id": 1, "_id": 0}).sort([("id", -1)]).limit(1))
    data["id"] = number[0]["id"] + 1
    date = datetime.date.today()
    str_date = date.strftime("%Y-%m-%d")
    data["date"] = str_date

    # Insertar retorna un objeto
    result = mensajes.insert_one(data)
    # Creo el mensaje resultado
    if (result):
        message = "1 mensaje creado"
        success = True
    else:
        message = "No se pudo crear el mensaje"
        success = False

    # Retorno el texto plano de un json
    return json.jsonify({'success': success, 'message': message})


@app.route("/filtro_mensajes", methods=['POST'])
def filtro_mensajes():
    """
    Debe recibir un json que indique palabras obligatorias,
    opcionales y prohibidas
    """
    mensajes.create_index([("message", TEXT)])
    data = {key: request.json[key] for key in FILTER_KEYS}
    obligatorias = ""
    prohibidas = ""
    opcionales = ""
    obli = data.get("obligatorias")
    if obli:
        obligatorias = " ".join(list(map(lambda x: "\""+ x +"\"", obli)))
    prohi = data.get("prohibidas")
    if prohi:
        prohibidas = " -" + " -".join(prohi)
    opcio = data.get("opcionales")
    if opcio:
        opcionales = " " + " ".join(opcio)
    formato_busqueda = obligatorias + prohibidas + opcionales
    msgs = list(mensajes.find({"$text": {"$search": formato_busqueda}}, {"message": 1, "_id": 0}))
    mensajes.drop_index("message_text")
    return json.jsonify(msgs)


@app.route("/filtro_mensajes/<int:idu>", methods=['POST'])
def filtro_mensajes_usuario(idu):
    mensajes.create_index([("message", TEXT)])
    data = {key: request.json[key] for key in FILTER_KEYS}
    obligatorias = ""
    prohibidas = ""
    opcionales = ""
    obli = data.get("obligatorias")
    if obli:
        obligatorias = " ".join(list(map(lambda x: "\""+ x +"\"", obli)))
    prohi = data.get("prohibidas")
    if prohi:
        prohi = " ".join(prohi).split(" ")
        prohibidas = " -" + " -".join(prohi)
    opcio = data.get("opcionales")
    if opcio:
        opcionales = " " + " ".join(opcio)
    formato_busqueda = obligatorias + prohibidas + opcionales
    msgs = list(mensajes.find({"$text": {"$search": formato_busqueda}, "sender": idu}, {"message": 1, "_id": 0}))
    mensajes.drop_index("message_text")
    return json.jsonify(msgs)


@app.route("/register_user", methods=['POST'])
def registro_usuarios():
    data = {key: request.json[key] for key in REGISTER_KEYS}
    # Insertar retorna un objeto
    result = usuarios.insert_one(data)
    # Creo el mensaje resultado
    if (result):
        message = "Usuario registrado"
        success = True
    else:
        message = "No se pudo crear el usuario"
        success = False

    # Retorno el texto plano de un json
    return json.jsonify({'success': success, 'message': message})


@app.route("/change_password", methods=['POST'])
def cambiar_password():
    print(request.json)
    
    data = {key: request.json[key] for key in CHANGE_PWD_KEYS}
    # Insertar retorna un objeto
    query = {"idu" : data["idu"]}
    old_pass = usuarios.find_one(query, {"password" : 1})["password"]

    print("Antigua", old_pass)

    if old_pass != data["old_password"]:
        return json.jsonify({'success': False, 'message': "Old Password doesnt match"})

    result = usuarios.update_one({"idu" : data["idu"]}, { "$set" : { "password" : data["new_password"]}})
    # Retorno el texto plano de un json
    return json.jsonify({'success': True, 'message': 'Contrasena cambiada'})


@app.route("/mapa/<int:idu>", methods=['POST'])
def mapa(idu):
    data = {key: request.json[key] for key in FECHA}
    fecha1 = datetime.datetime.strptime(data["fecha1"], "%Y-%m-%d")
    fecha2 = datetime.datetime.strptime(data["fecha2"], "%Y-%m-%d")
    msgs = mensajes.find({"sender": idu}, {"_id": 0})
    ubicacion = []
    for mensaje in msgs:
        fecha_mensaje = datetime.datetime.strptime(mensaje["date"], "%Y-%m-%d")
        if fecha1 <= fecha_mensaje <= fecha2:
            ubicacion.append((mensaje["lat"], mensaje["long"]))
    return json.jsonify(ubicacion)


if os.name == 'nt' or __name__ == "__main__":
    app.run()