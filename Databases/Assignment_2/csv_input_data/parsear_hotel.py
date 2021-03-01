import pandas as pd

cols = ['hid', 'rid', 'nombre_hotel',
         'direccion', 'telefono',
         'descripcion_hotel', 'estrellas']

my_filtered_csv = pd.read_csv("datos/hoteles_habitaciones.csv",
                              usecols=cols)

my_filtered_csv = my_filtered_csv.drop_duplicates(subset=['hid', 'rid'])

my_filtered_csv.to_csv("Hoteles.csv", columns=cols, index=False)
