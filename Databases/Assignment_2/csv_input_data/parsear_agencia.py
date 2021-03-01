import pandas as pd

my_filtered_csv = pd.read_csv("datos/agencia_agenciaregion_tour.csv",
                              usecols=['aid', 'nombre', 'direccion', 'telefono'])

my_filtered_csv = my_filtered_csv.drop_duplicates(subset=['aid'])

my_filtered_csv.to_csv("Agencias.csv",
                       columns=['aid', 'nombre', 'direccion', 'telefono'],
                       index=False)
