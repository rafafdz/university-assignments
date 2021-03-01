import pandas as pd

cols = ['pid', 'restid', 'nombre_plato', 'descripcion', 'precio']

my_filtered_csv = pd.read_csv("datos/restaurante_platos.csv", usecols=cols)

my_filtered_csv.to_csv("Platos.csv", columns=cols, index=False)
