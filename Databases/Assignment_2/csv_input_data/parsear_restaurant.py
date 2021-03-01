import pandas as pd

cols = ['restid', 'rid', 'nombre_restaurant',
        'direccion', 'telefono', 'descripcion_restaurant']

my_filtered_csv = pd.read_csv("datos/restaurante_platos.csv", usecols=cols)

my_filtered_csv = my_filtered_csv.drop_duplicates(subset=['restid'])

my_filtered_csv.to_csv("Restaurantes.csv", columns=cols, index=False)
