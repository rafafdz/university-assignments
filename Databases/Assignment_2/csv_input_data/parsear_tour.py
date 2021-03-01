import pandas as pd

cols = ['tid', 'aid', 'descripcion', 'precio']

my_filtered_csv = pd.read_csv("datos/agencia_agenciaregion_tour.csv",
                              usecols=cols)

my_filtered_csv = my_filtered_csv.drop_duplicates(subset=['tid'])

my_filtered_csv.to_csv("Tour.csv", columns=cols, index=False)
