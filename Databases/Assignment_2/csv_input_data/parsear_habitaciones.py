import pandas as pd

cols = ['habid', 'hid', 'nombre_habitacion', 'precio']

my_filtered_csv = pd.read_csv("datos/hoteles_habitaciones.csv",
                              usecols=cols)
my_filtered_csv.to_csv("Habitaciones.csv", columns=cols, index=False)
