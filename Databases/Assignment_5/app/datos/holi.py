with open("parques_nacionales_senderos.csv", "r", encoding="utf-8") as ark:
    lineas = []
    for linea in ark:
        l = linea.split(",")
        lil = [l[6], l[7], l[8], l[9], l[10]]
        lineas.append(lil)

with open("senderos2.csv", "w", encoding="utf-8") as ark:
    for y in lineas:
        print(y)
        o = ";".join(y)
        ark.write(o)
